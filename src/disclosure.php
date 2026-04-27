<?php

if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $file = $_FILES['file'];

        // echo "POST Data: "; var_dump($_POST);
        // echo "FILES Data: "; var_dump($_FILES);
        // die();

        try
        {
            include "config/config.php";
            include "model/disclosureModel.php";
            include "control/disclosureControl.php";
            include "config/config_session.php";

            $disclosure = new Disclosure($title, $description, $file);

            if($disclosure->submitDisclosure() === false)
                {
                    $_SESSION['errorsDisclosure'] = $disclosure->errors;
                    header("Location: ../public/invention_disclosure/disclosure.php?submit=failed");
                    exit();
                }

            header("Location: ../public/invention_disclosure/disclosure.php?submit=success");
            die();
        }
        catch (Exception $e) {
            echo "Error:" . $e->getMessage() ;
        }
    }
    else
        {
            header("Location: ../public/invention_disclosure/disclosure.php");
            die();
        }