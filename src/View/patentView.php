<?php 

class patentView extends patentControl
{
    public function displayContent()
    {
        $result = $this->filterData($_SESSION["user_id"]);

        foreach ($result as $row)
            {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["number"] . "</td>";
                echo "<td>" . $row["grantDate"] . "</td>";
                echo "<td>" . $row["status"] . "</td>";
                echo "<td>" . $row["deadline"] . "</td>";
                echo "<td>" . $row["daysLeft"] . "</td>";
                echo "</tr>";
            }
    }
}