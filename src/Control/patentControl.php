<?php

class patentControl extends patentModel
{
    public function filterData()
    {
        $uid = $_SESSION["user_id"];
        $result = $this->viewPatent($uid);

        $filtered = [];

        while($row = $result->fetch(PDO::FETCH_ASSOC))
            {
            $filtered[] = [
                "id" => htmlspecialchars($row["Patent_ID"]),
                "number" => htmlspecialchars($row["Number"]),
                "grantDate" => htmlspecialchars($row["GrantDate"]),
                "status" => htmlspecialchars($row["Status"]),
            ];
            }
        

        return $filtered;
    }
}