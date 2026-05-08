<?php

class IPcounselView extends IpcounselControl
{
    public function ViewTable()
    {
    $data = $this->filterData();
        foreach($data as $row)
            {
                echo "<tr>
                        <td><strong>{$row['AppID']}</strong></td>
                        <td><strong>{$row['disc_ID']}</strong></td>
                        <td>{$row['appNum']}</td>
                        <td>{$row['FilingDate']}</td>
                        <td class='status-badge'>{$row['Status']}</td>
                        <td><button class='review-btn'>Review</button></td>
                    </tr>";
            }
    }
}