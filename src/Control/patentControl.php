<?php
class patentControl extends patentModel
{
    public function filterData($uid)
    {
        $result = $this->viewPatent($uid);

        $filtered = [];

        while($row = $result->fetch(PDO::FETCH_ASSOC))
            {
            $filtered[] = [
                "id" => htmlspecialchars($row["Patent_ID"]),
                "number" => htmlspecialchars($row["Patent_Number"]),
                "grantDate" => htmlspecialchars($row["GrantDate"]),
                "status" => htmlspecialchars($row["Patent_Status"]),
            ];
            }
        

        return $filtered;
    }
}