<?php 

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $title = $_POST['title'];
    $description = $_POST['description'];
    $reformattedfiles = [];

    if (!empty($_FILES['files']['name'][0]))
        {
            foreach($_FILES['files']['name'] as $i => $name)
                {
                    $reformattedfiles[] = [
                        'name' => $_FILES['files']['name'][$i],
                        'type' => $_FILES['files']['type'][$i],
                        'tmp_name' => $_FILES['files']['tmp_name'][$i],
                        'error' => $_FILES['files']['error'][$i],
                        'size' => $_FILES['files']['size'][$i],
                    ];
                }
        }

    $contributors = [
        'ContributorIDs' => $_POST['ContributorIDs'] ?? [],
        'contributionPercentages' => $_POST['contributionPercentages'] ?? []
    ];

    try
    {
        include "config/config.php";
        include "model/disclosureModel.php";
        include "control/disclosureControl.php";
        include "config/config_session.php";

        $disclosure = new Disclosure($title, $description, $reformattedfiles, $contributors);

        if($disclosure->submitDisclosure() === false)
        {
            $_SESSION['errorsDisclosure'] = $disclosure->errors;
            header("Location: ../public/invention_disclosure/disclosure.php?submit=failed");
            exit();
        }

        header("Location: ../public/invention_disclosure/disclosure.php?submit=success");
        exit();
    }
    catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
else
{
    header("Location: ../public/invention_disclosure/disclosure.php");
    exit();
}