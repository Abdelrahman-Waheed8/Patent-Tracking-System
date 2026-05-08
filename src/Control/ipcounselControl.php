<?php

class IpcounselControl extends IPcounselModel
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
        // Require at least an action and a disclosure identifier. Description is optional (used when arguing).
        if(empty($this->action) || empty($this->discID))
        {
            $this->errors["empty"] = "Action or Disclosure ID missing";
            return false;
        }
        return true;
    }

    private function StoreIpCounsel()
    {
        // Store the action; description is optional.
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
        // Validate required inputs (action and disclosure id). Description may be empty for some actions.
        if(!$this->validateInput()){
            return false;
        }
        $this->StoreIpCounsel();
        return true;
    }
}