<?php


class disclosureModel extends DBH
{

    protected function setDisclosure($title, $description)
    {
        $query = "INSERT INTO disclosure (Title, FilingDate, Description) VALUES (:title, NOW(), :description);";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":description", $description);
        $stmt->execute();


        $newId = $this->connect()->lastInsertId();

        $stmt2 = $this->connect()->prepare("SELECT FilingDate FROM disclosure WHERE disc_ID = :id");
        $stmt2->bindParam(":id", $newId);
        $stmt2->execute();

        $dateRow = $stmt2->fetch(PDO::FETCH_ASSOC);
        $fillingDate = $dateRow['FilingDate'];

        //Move fingerprint logic to Controller later
        $fingerprint = hash('sha256', $newId . $fillingDate . $title);

        $stmt3 = $this->connect()->prepare("UPDATE disclosure SET Unique_fgrPrint = :fp WHERE disc_ID = :id");
        $stmt3->execute([':fp' => $fingerprint, ':id' => $newId]);

        $stmt = null;
        return $newId;
    }
}
