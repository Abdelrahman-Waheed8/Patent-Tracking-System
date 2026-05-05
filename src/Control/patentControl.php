<?php
class patentControl extends patentModel
{
    public function calculateMaitenanceWindows($grantDateString)
    {
        $grantDate = new DateTime($grantDateString);
        $now = new DateTime();
        
        // Deadline is 3.5 years (42 months)
        $deadlineDate = clone $grantDate;
        $deadlineDate->modify("+42 months");

        // Due date is 1 month after deadline
        $dueDate = clone $deadlineDate;
        $dueDate->modify("+1 month");

        $difference = $now->diff($deadlineDate);
        $deadlineInfo = [
            "deadline" => $deadlineDate->format('Y-m-d'),
            "dueDate" => $dueDate->format('Y-m-d'),
            "daysleft" => $difference->format("%R%a")
        ];

        return $deadlineInfo;
    }

    public function calculateFees($baseFeeUSD = 1000.00)
    {
        $exchangeRate = $this->getExchangeRate();
        return [
            'USD' => number_format($baseFeeUSD, 2),
            'EGP' => number_format($baseFeeUSD * $exchangeRate, 2)
        ];
    }

    public function filterData($uid)
    {
        $result = $this->viewPatent($uid);

        $filtered = [
            'summary' => [
                'total' => 0,
                'dueSoon' => 0,
                'overdue' => 0
            ],
            'list' => []
        ];

        while($row = $result->fetch(PDO::FETCH_ASSOC))
            {
            $maintenance = $this->calculateMaitenanceWindows($row["GrantDate"]);

            $daysleft = $maintenance["daysleft"];
            $fees = [];

            if ($daysleft <= 30)
            {
                $fees = $this->calculateFees();
            }

            $filtered['summary']['total']++;

            if($daysleft < 0)
            {
                $filtered['summary']['overdue']++;
            }

            if($daysleft > 0 && $daysleft <= 30)
                {
                    $filtered['summary']['dueSoon']++;
                }

            $filtered['list'][] = [
                "id" => htmlspecialchars($row["Patent_ID"]),
                "number" => htmlspecialchars($row["Patent_Number"]),
                "grantDate" => htmlspecialchars($row["GrantDate"]),
                "status" => htmlspecialchars($row["Patent_Status"]),
                "deadline" => $maintenance["deadline"],
                "dueDate" => $maintenance["dueDate"],
                "daysLeft" => $maintenance["daysleft"],
                "fees" => $fees
            ];
            }
        

        return $filtered;
    }

    public function processPayment($patentId, $transactionId, $uid) 
    {
        if (empty($patentId) || empty($transactionId)) {
            return false;
        }

        // Ensure the fee record exists in the database before updating it
        // We use 1000.00 as the default base fee as per our fee calculation logic
        $this->patentFee($patentId, 1000.00);
        $this->viewPatent($uid);

        return $this->updatePaymentStatus($patentId, $transactionId);
    }

    
}