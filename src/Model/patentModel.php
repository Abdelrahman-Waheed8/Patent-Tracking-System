<?php

class patentModel extends DBH
{
    public function viewPatent($uid)
    {
        $pdo = $this->connect();
        $query = "SELECT 
            p.Patent_ID,
            p.Number AS Patent_Number,
            p.GrantDate,
            p.Status AS Patent_Status,
            p.Expiration,
            d.Title AS Invention_Title,
            pa.appNum AS Application_Number,
            oi.ContributionPercentage
        FROM 
            patent p
        JOIN 
            grantedpatents gp ON p.Patent_ID = gp.Patent_ID
        JOIN 
            patentapplication pa ON gp.AppID = pa.AppID
        JOIN 
            disclosure d ON pa.disc_ID = d.disc_ID
        JOIN 
            ownershipofinvention oi ON d.disc_ID = oi.disc_ID
        WHERE 
            oi.usr_ID = :uid;";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":uid", $uid);
        $stmt->execute();

        return $stmt;
    }   
}