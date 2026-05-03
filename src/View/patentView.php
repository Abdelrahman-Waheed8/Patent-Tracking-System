<?php 

class patentView extends patentControl
{
    public function displayContent()
    {
        $result = $this->filterData();

        foreach ($result as $row)
            {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["number"] . "</td>";
                echo "<td>" . $row["grantDate"] . "</td>";
                echo "<td>" . $row["status"] . "</td>";
                echo "</tr>";
            }
    }
}