<?php

<<<<<<< HEAD
class IpcounselControl extends IPcounselModel
=======
class Ipcounsel extends IPcounselModel
>>>>>>> 464c9faf0a2c3baf7a27a165fdfb66d77c292f3e
{
    private $uid;
    private $action;
    private $description;
    private $discID;
    public $errors = [];

    public function __construct($uid=null,$action=null,$discID=null, $description = null)
    {
        $this->uid = $uid;
        $this->action = $action;
        $this->description = $description;
        $this->discID = $discID;
    }

    public function showPatentApps()
    {
        $result = $this->fetchTable();
        return $result;
    }

    public function filterData()
    {
        $result = [];
        $data = $this->showPatentApps();

        foreach($data as $row)
        {
            if ($row['Status'] == 'rejected' || $row['Status'] == 'Legal_Review')
            {
                $result[] = [
                    'AppID' => htmlspecialchars($row['AppID']),
                    'disc_ID' => htmlspecialchars($row['disc_ID']),
                    'appNum' => htmlspecialchars($row['appNum']),
                    'FilingDate' => htmlspecialchars($row['FilingDate']),
                    'Status' => htmlspecialchars($row['Status'])
                ];
            }
        }
        return $result;
    }

    private function validateInput()
    {
        if(empty($this->action) || empty($this->discID))
        {
            $this->errors["empty"] = "Action or Disclosure ID missing";
            return false;
        }
        return true;
    }

    private function StoreIpCounsel()
    {
        if(empty($this->description))
        {
            $this->storeAction($this->uid, $this->action, $this->discID);
        }
        else
        {
            $this->storeAction($this->uid, $this->action, $this->discID, $this->description);
        }
        return true;
    }

    public function submitIpCounselAction()
    {
        if(!$this->validateInput()){
            return false;
        }
        $this->StoreIpCounsel();
        return true;
    }
}