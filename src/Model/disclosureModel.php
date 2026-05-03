<?php

class disclosureModel extends DBH
{
    protected function setDisclosure($title, $description, $contributors, $files, $priorArt, $companyNames, $jurisdictionalType, $scope)
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

            if (!empty($priorArt['link']) || !empty($priorArt['desc'])) {
                $stmtPrior = $pdo->prepare("INSERT INTO prior_art (disc_ID, `Description`, link) VALUES (:disc_id, :descr, :link)");
                $stmtPrior->bindParam(":disc_id", $newId);
                $stmtPrior->bindParam(":link", $priorArt['link']);
                $stmtPrior->bindParam(":descr", $priorArt['desc']);
                $stmtPrior->execute();
            }



            if (!empty($company)) {
                $stmtAgreement->bindParam(":userId", $userId);
                $stmtAgreement->bindParam(":company", $company);
                $stmtAgreement->execute();
            }
        }


        $appNum = 'APP' . str_pad($newId, 6, '0', STR_PAD_LEFT);
        $status = "pending";

        $stmt5 = $pdo->prepare("
            insert into patentapplication (disc_ID, appNum, FilingDate, Status) VALUES (:disc_id, :app_num, :filing_date, :status)
        ");
        $stmt5->bindParam(":disc_id", $newId);
        $stmt5->bindParam(":app_num", $appNum);
        $stmt5->bindParam(":filing_date", $dateRow['FilingDate']);
        $stmt5->bindParam(":status", $status);
        $stmt5->execute();





        $countryCodeValue = "#" . $scope;

        $stmt6 = $pdo->prepare("
            INSERT INTO jursidiction (JurisdictionalType, name, countryCode) VALUES (:jurisdictional_type, :scope, :country_code)
        ");
        
        $stmt6->bindParam(":jurisdictional_type", $jurisdictionalType);
        $stmt6->bindParam(":scope", $scope);
        $stmt6->bindParam(":country_code", $countryCodeValue);
        $stmt6->execute();
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
