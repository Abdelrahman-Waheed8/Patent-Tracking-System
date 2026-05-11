<?php 

class IPcounselModel extends DBH
{
    public function fetchTable()
    {
        $pdo = $this->connect();
        $query = "SELECT * FROM patentapplication WHERE `Status` = 'rejected' GROUP BY AppID;";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRejectionDetails()
    {
        $pdo = $this->connect();
        $query = "SELECT 
                    pa.AppID, 
                    pa.disc_ID,
                    pa.appNum, 
                    pa.Status AS ApplicationStatus, 
                    d.Title, 
                    d.Description AS DisclosureDescription, 
                    oa.ActionID, 
                    oa.DateReceived, 
                    oa.Deadline, 
                    oa.Type AS OfficeActionType, 
                    oa.Status AS OfficeActionStatus,
                    pr.Description AS RejectionReason
                  FROM patentapplication pa
                  JOIN disclosure d ON pa.disc_ID = d.disc_ID
                  LEFT JOIN officeaction oa ON pa.AppID = oa.AppID
                  LEFT JOIN prior_art pr ON pa.disc_ID = pr.disc_ID
                  WHERE pa.Status = 'rejected'
                  ORDER BY pa.AppID DESC;";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function storeAction($uid,$action,$discID,$description = null)
    {
        $pdo = $this->connect();
        if($description)
            {
                $query = "INSERT INTO logs (usr_ID, `Action`, `Timestamp`, `type`, `description`)
                          VALUES (:ud, :actn, NOW(), 'Reject Examiner Rejection', :desciption);";
                $stmt1 = $pdo->prepare($query);
                $stmt1->bindParam(":ud", $uid);
                $stmt1->bindParam(":actn", $action);
                $stmt1->bindParam(":desciption", $description);
                $stmt1->execute();

                $query4 = "UPDATE patentapplication SET `Status` = 'Legal_Review' WHERE disc_ID = :disc;";
                $stmt4 = $pdo->prepare($query4);
                $stmt4->bindParam(":disc", $discID);
                $stmt4->execute();

                $query6 = "SELECT AppID FROM patentapplication WHERE disc_ID = :discID;";
                $stmt6 = $pdo->prepare($query6);
                $stmt6->bindParam(":discID", $discID);
                $stmt6->execute();
                $appIDRow = $stmt6->fetch(PDO::FETCH_ASSOC);
                $appID = $appIDRow['AppID'];

                $query5 = "INSERT INTO officeaction (AppID, DateReceived, Deadline, `Type`, `Status`)
                           VALUES (:appid, NOW(), DATE_ADD(NOW(), INTERVAL 3 MONTH), 'Legal Review', 'Legal_Review');";
                $stmt5 = $pdo->prepare($query5);
                $stmt5->bindParam(":appid", $appID);
                $stmt5->execute();
            }
        else {
                $query2 = "INSERT INTO logs (usr_ID, `Action`, `Timestamp`, `type`, `description`)
                           VALUES (:ud, :actn, NOW(), 'Accept Examiner Rejection', 'Accepted Rejection');";
                $stmt2 = $pdo->prepare($query2);
                $stmt2->bindParam(":ud", $uid);
                $stmt2->bindParam(":actn", $action);
                $stmt2->execute();
        }
    }
}