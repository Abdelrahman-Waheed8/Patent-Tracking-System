<?php

class ExaminerView extends Examiner
{
    public function renderTable($patents)
    {
        foreach ($patents as $item) {
            echo "
            <tr>
                <td>{$item['title']}</td>
                <td>{$item['inventor']}</td>
                <td>{$item['status']}</td>
                <td>
                    <form method='POST' action='../../src/examiner.php'>
                        <input type='hidden' name='id' value='{$item['id']}'>
                        
                        <button name='action' value='approve'>Approve</button>
                        <button name='action' value='reject'>Reject</button>
                        <button name='action' value='pending'>Pending</button>
                    </form>
                </td>
            </tr>
            ";
        }
    }

    public function displayErrors()
    {
        if (isset($_SESSION['errorsExaminer']) && !empty($_SESSION['errorsExaminer'])) {
            echo "<div class='error-messages'>";
            foreach ($_SESSION['errorsExaminer'] as $error) {
                echo "<p>$error</p>";
            }
            echo "</div>";

            unset($_SESSION['errorsExaminer']);
        }
    }

    public function showLegalReview()
    {
        $legalreview = $this->getLegalReviewApps();
        foreach ($legalreview as $row) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['Title']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Description']) . "</td>";
            echo "<td>" . htmlspecialchars($row['appNum']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Deadline']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Status']). "</td>";
            echo "<td>";
            echo "<form method='POST' action='../../src/examiner.php' style='display:inline;'>";
            echo "<input type='hidden' name='AppID' value='" . htmlspecialchars($row['AppID']) . "'>";
            echo "<input type='hidden' name='action' value='approve_patent'>";
            echo "<button type='submit' class='approve-btn' style='padding: 8px 12px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;'>Approve & Grant Patent</button>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
    }
}