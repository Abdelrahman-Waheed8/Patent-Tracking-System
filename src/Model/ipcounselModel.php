<?php 

class IPcounselModel extends DBH
{
    public function fetchTable()
    {
        $pdo = $this->connect();
        $query = "SELECT * FROM patentapplication WHERE `Status` IN ('rejected', 'Legal_Review') GROUP BY AppID;";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}