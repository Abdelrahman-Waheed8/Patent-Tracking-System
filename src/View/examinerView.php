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
}