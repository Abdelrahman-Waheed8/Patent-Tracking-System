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

    public function getExchangeRate()
    {
        // For now, using a static rate as requested "only deal with usd and egp"
        // In a real app, this could call an API.
        return 48.50; // 1 USD = 48.50 EGP
    }

    public function patentFee($patentId, $baseAmountUSD)
    {
        $pdo = $this->connect();
        
        // 1. Check if a fee record already exists for this patent to avoid duplicates
        $checkQuery = "SELECT feeScheduleID FROM patentfee WHERE Patent_ID = :pid AND feeType = 'Maintenance 3.5'";
        $checkStmt = $pdo->prepare($checkQuery);
        $checkStmt->bindParam(":pid", $patentId);
        $checkStmt->execute();
        
        if ($checkStmt->rowCount() > 0) {
            return; // Fee record already exists, no need to insert again
        }

        // 2. Fetch the actual GrantDate from the patent table
        $dateQuery = "SELECT GrantDate FROM patent WHERE Patent_ID = :pid";
        $dateStmt = $pdo->prepare($dateQuery);
        $dateStmt->bindParam(":pid", $patentId);
        $dateStmt->execute();
        $patent = $dateStmt->fetch(PDO::FETCH_ASSOC);
        
        $grantDateStr = $patent ? $patent['GrantDate'] : date('Y-m-d');
        $grantDate = new DateTime($grantDateStr);
        
        // Calculate dates: Deadline is 3.5 years (42 months), Due Date is 1 month later (43 months)
        $deadlineDate = clone $grantDate;
        $deadlineDate->modify("+42 months");
        
        $dueDate = clone $deadlineDate;
        $dueDate->modify("+1 month");

        $exchangeRate = $this->getExchangeRate();
        $amountEGP = $baseAmountUSD * $exchangeRate;

        // Insert into feeschedule for both currencies
        $query = "INSERT INTO feeschedule (currencyCode, baseAmount, dueDate) VALUES 
                 ('USD', :amountUSD, :dueD),
                 ('EGP', :amountEGP, :dueD);";
        
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(":amountUSD", $baseAmountUSD);
        $stmt->bindValue(":amountEGP", $amountEGP);
        $stmt->bindValue(":dueD", $dueDate->format('Y-m-d'));
        $stmt->execute();

        $query2 = "INSERT INTO patentfee (feeScheduleID,Patent_ID,`status`,feeType,calculatedAmount)
        VALUES (:feeID, :pid, 'pending' ,'Maintenance 3.5',:calculate);";
        $feeid = $pdo->lastInsertId();
        $stmt2 = $pdo->prepare($query2);
        $stmt2->bindParam(":feeID", $feeid);
        $stmt2->bindParam(":pid", $patentId);
        $stmt2->bindParam(":calculate", $baseAmountUSD);
        $stmt2->execute();

    }

    public function updatePaymentStatus($patentId, $transactionId) 
    {
        $pdo = $this->connect();
        $query = "UPDATE patentfee 
                  SET `status` = 'Paid', transactionID = :txId 
                  WHERE Patent_ID = :pid AND status != 'Paid'";
        
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":txId", $transactionId);
        $stmt->bindParam(":pid", $patentId);
        $stmt->execute();

        $query2 = "UPDATE patent SET `status` = 'Active' WHERE Patent_ID = :pid;";
        $stmt2 = $pdo->prepare($query2);
        $stmt2->bindParam(":pid", $patentId);
        $stmt2->execute();

        $query3 = "UPDATE patent SET Expiration = DATE_ADD(NOW(), INTERVAL 42 MONTH) WHERE Patent_ID = :pid;";
        $stmt3 = $pdo->prepare($query3);
        $stmt3->bindParam(":pid", $patentId);
        $stmt3->execute();
        // Return true if at least one row was updated (i.e., a record existed and was changed)
        return $stmt->rowCount() > 0;
    }
}