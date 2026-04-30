<?php

class disclosureModel extends DBH
{
    protected function setDisclosure($title, $description, $contributors, $files)
    {
        $pdo = $this->connect();

        $stmt = $pdo->prepare("
            INSERT INTO disclosure (Title, FilingDate, Description) 
            VALUES (:title, NOW(), :description)
        ");

        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":description", $description);
        $stmt->execute();

        $newId = $pdo->lastInsertId();

        $stmt2 = $pdo->prepare("SELECT FilingDate FROM disclosure WHERE disc_ID = :id");
        $stmt2->bindParam(":id", $newId);
        $stmt2->execute();
        $dateRow = $stmt2->fetch(PDO::FETCH_ASSOC);

        $fingerprint = hash('sha256', $newId . $dateRow['FilingDate']);

        $stmt3 = $pdo->prepare("
            UPDATE disclosure 
            SET Unique_fgrPrint = :fp 
            WHERE disc_ID = :id
        ");

        $stmt3->bindParam(":fp", $fingerprint);
        $stmt3->bindParam(":id", $newId);
        $stmt3->execute();

        $stmtVault = $pdo->prepare("INSERT INTO evidence_vault (disc_ID, evidence_type) VALUES (:disc_id, 'Invention Documents')");
        $stmtVault->bindParam(":disc_id", $newId);
        $stmtVault->execute();
        $evidenceID = $pdo->lastInsertId();

        if (!empty($files)) {
            $stmtDoc = $pdo->prepare("INSERT INTO document (evidence_ID, filePath, docType) VALUES (:ev_id, :f_path, :ext)");
            foreach ($files as $path) {
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $stmtDoc->bindParam(":ev_id", $evidenceID);
                $stmtDoc->bindParam(":f_path", $path);
                $stmtDoc->bindParam(":ext", $ext);
                $stmtDoc->execute();
            }
        }

        $stmt4 = $pdo->prepare("
            INSERT INTO ownershipofinvention 
            (disc_ID, usr_ID, ContributionPercentage) 
            VALUES (:disc_id, :userId, :percentage)
        ");

        $stmtAgreement = $pdo->prepare("
            INSERT INTO external_agreements (usr_ID, company_name)
            VALUES (:userId, :company)
        ");



        $ids = $contributors['ContributorIDs'] ?? [];
        $percentages = $contributors['contributionPercentages'] ?? [];
        $companies = $contributors['companyNames'] ?? [];

        $invalidUsers = [];

        for ($i = 0; $i < count($ids); $i++) {

            $userId = $ids[$i];
            $percentage = $percentages[$i];
            $company = $companies[$i] ?? null;

            if (empty($userId) || empty($percentage)) {
                continue;
            }

            if (!$this->userExists($userId)) {
                $invalidUsers[] = $userId;
                continue;
            }

            $stmt4->bindParam(":disc_id", $newId);
            $stmt4->bindParam(":userId", $userId);
            $stmt4->bindParam(":percentage", $percentage);
            $stmt4->execute();

            if (!empty($company)) {
                $stmtAgreement->bindParam(":userId", $userId);
                $stmtAgreement->bindParam(":company", $company);
                $stmtAgreement->execute();
            }
        }
    }

    public function userExists($userId)
    {
        $stmt = $this->connect()->prepare("
            SELECT 1 FROM user WHERE usr_ID = ?
        ");

        $stmt->bindParam(1, $userId);
        $stmt->execute();

        return $stmt->fetchColumn() !== false;
    }
}
