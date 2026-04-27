<?php


class disclosureModel extends DBH
{

    protected function setDisclosure($title, $description)
    {
        $pdo = $this->connect();

        $query = "INSERT INTO disclosure (Title, FilingDate, `Description`) VALUES (:title, NOW(), :description);";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":description", $description);
        $stmt->execute();


        $newId = $pdo->lastInsertId();

        $stmt2 = $pdo->prepare("SELECT FilingDate FROM disclosure WHERE disc_ID = :id");
        $stmt2->bindParam(":id", $newId);
        $stmt2->execute();

        $dateRow = $stmt2->fetch(PDO::FETCH_ASSOC);
        $fillingDate = $dateRow['FilingDate'];

        //Move fingerprint logic to Controller later
        $fingerprint = hash('sha256', $newId . $fillingDate );

        $stmt3 = $pdo->prepare("UPDATE disclosure SET Unique_fgrPrint = :fp WHERE disc_ID = :id");
        $stmt3->bindParam(":fp", $fingerprint);
        $stmt3->bindParam(":id", $newId);
        $stmt3->execute();

        $stmt = null;
    }
}
