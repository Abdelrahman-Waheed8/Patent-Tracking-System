<?php

class ExaminerView
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

            // Clear errors after displaying
            unset($_SESSION['errorsExaminer']);
        }
    }
}