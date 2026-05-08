<?php 

class IPcounselModel extends DBH
{
    public function fetchTable()
    {
        $pdo = $this->connect();
        $query = "SELECT * FROM patentapplication WHERE `Status` IN ('rejected', 'Legal_Review') GROUP BY AppID;";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRejectionDetails($appID)
    {
        $pdo = $this->connect();
        $query = "SELECT 
                    oa.ActionID, 
                    oa.DateReceived, 
                    oa.Deadline, 
                    oa.Type AS OfficeActionType, 
                    oa.Status AS OfficeActionStatus,
                    l.Description AS RejectionReason,
                    l.Timestamp AS RejectionLogDate,
                    pa.appNum,
                    pa.Status AS ApplicationStatus
                  FROM officeaction oa
                  JOIN patentapplication pa ON oa.AppID = pa.AppID
                  LEFT JOIN logs l ON l.Action = 'Rejection' AND l.type = CAST(pa.AppID AS CHAR)
                  WHERE pa.AppID = :appID;";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':appID', $appID, PDO::PARAM_INT);
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

                $query5 = "INSERT INTO officeaction (AppID, DateReceived, Deadline, `Type`, `Status`)
                           VALUES ((SELECT AppID FROM patentapplication WHERE disc_ID = :discID), NOW(), DATE_ADD(NOW(), INTERVAL 3 MONTH), 'Legal Review', 'Legal_Review');";
                $stmt5 = $pdo->prepare($query5);
                $stmt5->bindParam(":discID", $discID);
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