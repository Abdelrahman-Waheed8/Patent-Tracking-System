<?php

if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $fname = $_POST["fname"];
        $lname = $_POST["lname"];
        $email = $_POST["email"];
        $pwd = $_POST["password"];
        $role = $_POST["role"];

        include "config/config.php";
        include "config/config_session.php";
        include "model/signupModel.php";
        include "control/adminControl.php";
        include "view/adminView.php";

        try {
            $admin = new Admin($fname,$lname,$email,$pwd,$role);

            if($admin->createUser() == false)
                {
                    $_SESSION["errorCreateUser"] = $admin->errors;
                    header("Location: ../public/systemAdmin/systemAdmin.php?createUser=failed");
                    exit();
                }

            header("Location: ../public/systemAdmin/systemAdmin.php?createUser=success");
            die();
        } catch (PDOException $e) {
            echo "Query Failed" . $e->getMessage() ;
        }
    }
else
    {
        header("Location: ../public/index.php?access=denied");
        die();
    }