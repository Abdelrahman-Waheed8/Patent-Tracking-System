<?php

class IpcounselControl extends IPcounselModel
{
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
}