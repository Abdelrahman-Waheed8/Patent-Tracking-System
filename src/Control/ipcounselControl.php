<?php

class IpcounselControl extends IPcounselModel
{
    private $uid;
    private $action;
    private $description;
    private $discID;
    public $errors = [];

    public function __counstruct($uid,$action,$discID, $description = null)
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
        if(empty($this->action) || empty($this->description))
        {
            $this->errors["empty"] = "Fill in all fields";
            return false;
        }
        return true;
    }

    private function StoreIpCounsel()
    {
        if(empty($this->description))
            {
                $this->storeAction($this->uid,$this->action, $this->discID);
                return true;
            }
        $this->storeAction($this->uid,$this->action, $this->discID,$this->description);
    }

    public function submitIpCounselAction()
    {
        if(!$this->validateInput())
        {
            return false;
        }
        $this->StoreIpCounsel();
        return true;
    }
}