<?php
class patentControl extends patentModel
{
    public function calculateMaitenanceWindows($grantDateString)
    {
        $grantDate = new DateTime($grantDateString);
        $now = new DateTime();
        
        $endDate = new DateTime($grantDateString);
        $endDate->modify("+42 months");

        $difference = $now->diff($endDate);
        $deadline  = [
            "deadline" => $endDate->format('Y-m-d'),
            "daysleft" => $difference->format("%R%a")
        ];

        return $deadline;
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
            $renewal = $this->calculateMaitenanceWindows($row["GrantDate"]);
            $daysleft = $renewal["daysleft"];

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
                "deadline" => $renewal["deadline"],
                "daysLeft" => $renewal["daysleft"]
            ];
            }
        

        return $filtered;
    }

    
}