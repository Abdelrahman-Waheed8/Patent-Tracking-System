<?php

class examinerModel extends DBH {

    
    protected function getPendingDisclosures() {
        $pdo = $this->connect();

        $sql = "SELECT 
                    p.AppID,
                    p.disc_ID,
                    d.Title,
                    d.Description,
                    d.Classification_ID,
                    p.appNum,
                    p.FilingDate,
                    p.Status
                FROM patentapplication p
                JOIN disclosure d ON p.disc_ID = d.disc_ID
                WHERE p.Status = 'pending'";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    protected function updateStatus($discID, $status) {
        $pdo = $this->connect();

        $stmt = $pdo->prepare("UPDATE patentapplication SET Status = :status WHERE disc_ID = :id");
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":id", $discID);

        return $stmt->execute();
    }
}