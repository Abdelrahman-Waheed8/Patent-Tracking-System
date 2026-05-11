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

    public function displayErrors()
    {
        if (isset($_SESSION['errorIpcounsel']) && !empty($_SESSION['errorIpcounsel'])) {
            echo "<div class='error-messages'>";
            foreach ($_SESSION['errorIpcounsel'] as $error) {
                echo "<p>$error</p>";
            }
            echo "</div>";

            // Clear errors after displaying
            unset($_SESSION['errorIpcounsel']);
        }
    }

    public function showRejectedApps()
    {
        $rejections = $this->getRejectionDetails();
        foreach ($rejections as $row) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['Title']) . "</td>";
            echo "<td>" . htmlspecialchars($row['appNum']) . "</td>";
            $reason = !empty($row['RejectionReason']) ? htmlspecialchars($row['RejectionReason']) : 'No reason specified';
            echo "<td>" . $reason . "</td>";
            echo "<td>" . htmlspecialchars($row['Deadline']) . "</td>";
            echo "</tr>";
        }
    }
}