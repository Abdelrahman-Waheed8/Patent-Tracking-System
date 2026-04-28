<?php 

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $title = $_POST['title'];
    $description = $_POST['description'];
    $file = $_FILES['file'];

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

        $disclosure = new Disclosure($title, $description, $file, $contributors);

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