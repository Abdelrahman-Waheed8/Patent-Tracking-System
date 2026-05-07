<?php

class examinerModel extends DBH
{


    protected function getPendingDisclosures()
    {
        $pdo = $this->connect();

        $sql = "SELECT 
        p.AppID,
        p.disc_ID,
        d.Title,
        d.Description,
        p.appNum,
        p.FilingDate,
        p.Status,
        GROUP_CONCAT(DISTINCT CONCAT(u.first_name, ' ', u.last_name, ' (', own.ContributionPercentage, '%)') SEPARATOR ', ') AS Inventors,
        GROUP_CONCAT(DISTINCT ext.company_name SEPARATOR ', ') AS ExternalCompanies,
        GROUP_CONCAT(DISTINCT doc.filePath SEPARATOR ', ') AS FilePaths,
        GROUP_CONCAT(DISTINCT CONCAT(jur.name, ' (', jur.JurisdictionalType, ')') SEPARATOR ', jur.name') AS Jurisdictions
        FROM patentapplication p
        JOIN disclosure d ON p.disc_ID = d.disc_ID
        LEFT JOIN ownershipofinvention own ON d.disc_ID = own.disc_ID
        LEFT JOIN user u ON own.usr_ID = u.usr_ID
        LEFT JOIN external_agreements ext ON u.usr_ID = ext.usr_ID
        LEFT JOIN evidence_vault ev ON d.disc_ID = ev.disc_ID
        LEFT JOIN document doc ON ev.evidence_ID = doc.evidence_ID
        LEFT JOIN coverdterritory ct ON p.AppID = ct.PatentAppID
        LEFT JOIN jursidiction jur ON ct.JurisdictionalID = jur.JurisdictionalID
        WHERE p.Status = 'pending'
        GROUP BY p.AppID;";


        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    protected function updateStatus($discID, $status)
    {
        $pdo = $this->connect();

        $stmt = $pdo->prepare("UPDATE patentapplication SET Status = :status WHERE disc_ID = :id");
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":id", $discID);

        $queryGetAppNumber = "SELECT appNum, AppID FROM patentapplication WHERE disc_ID = :id;" ;
        $stmt2 = $pdo->prepare($queryGetAppNumber);
        $stmt2->bindParam(":id",$discID);
        $stmt2->execute();
        $patentApp = $stmt2->fetch(PDO::FETCH_ASSOC);

        
        
        return $stmt->execute();
    }
}
