<?php 

class patentView extends patentControl
{
    public function displayContent()
    {
        $result = $this->filterData($_SESSION["user_id"]);

        foreach ($result['list'] as $row)
            {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["number"] . "</td>";
                echo "<td>" . $row["grantDate"] . "</td>";
                echo "<td>" . $row["status"] . "</td>";
                echo "<td>" . $row["deadline"] . "</td>";
                echo "<td>" . $row["dueDate"] . "</td>";
                echo "<td>" . $row["daysLeft"] . "</td>";
                echo "<td>";
                if (!empty($row["fees"]))
                {
                    echo "$" . $row["fees"]["USD"] . " / " . $row["fees"]["EGP"] . " EGP";
                }
                else
                {
                    echo "N/A";
                }
                echo "</td>";
                echo "<td><button class='review-btn' onclick='showModal(viewModal)'>Review<button></td>";
                echo "</tr>";
            }
    }

    public function displayCardTotal()
    {
        $result = $this->filterData($_SESSION["user_id"]);

        echo "<h1 id='total-patents'>". $result['summary']['total'] ."</h1>";
    }

    public function displayCardOverDue()
    {
        $result = $this->filterData($_SESSION["user_id"]);

        echo "<h1 id='due-soon'>". $result['summary']['overdue'] ."</h1>";
    }

    public function displayCardDueSoon()
    {
        $result = $this->filterData($_SESSION["user_id"]);

        echo "<h1 id='overdue'>". $result['summary']['dueSoon'] ."</h1>";
    }
}