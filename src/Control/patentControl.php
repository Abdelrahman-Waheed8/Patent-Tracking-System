<?php
class patentControl extends patentModel
{
    public function calculateMaitenanceWindows($grantDateString)
    {
        $grantDate = new DateTime($grantDateString);
        
        $endDate = new DateTime($grantDateString);
        $endDate->modify("+42 months");

        $difference = $endDate->diff($grantDate);
        $deadline  = [
            "deadline" => $endDate->format('Y-m-d'),
            "daysleft" => $difference->format("%R%a")
        ];

        return $deadline;
    }


    public function filterData($uid)
    {
        $result = $this->viewPatent($uid);
        $count = $result->rowCount();

        $filtered = [];

        while($row = $result->fetch(PDO::FETCH_ASSOC))
            {
            $renewal = $this->calculateMaitenanceWindows($row["GrantDate"]);

            $filtered[] = [
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