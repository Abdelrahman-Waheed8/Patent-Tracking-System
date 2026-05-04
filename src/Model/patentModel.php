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
        
        // Calculate dates: Deadline is 3.5 years (42 months), Due Date is 1 month later (43 months)
        $grantDate = new DateTime(); // Assuming current date as grant date for this example, or fetch from DB
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

        $query2 = "INSER INTO patentfee (feeScheduleID,Patent_ID,status,fee Type,calculatedAmount)
        VALUES (:feeID, :pid, 'Maintenance 3.5',:calculate);";
        $feeid = $pdo->lastInsertId();
        $stmt2 = $pdo->prepare($query2);
        $stmt2->bindParam(":feeID", $feeid);
        $stmt2->bindParam(":pid", $patentId);
        $stmt2->bindParam(":calculate", $baseAmountUSD);
    }
}