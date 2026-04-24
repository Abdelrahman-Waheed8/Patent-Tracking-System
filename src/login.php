<?php
if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // Grabbing userdata from the form
        $email = $_POST["email"];
        $pwd = $_POST["password"];


        try {
            //Instantiate signupController class
            include "config/config.php";
            include "model/loginModel.php";
            include "control/loginControl.php";
            include "config/config_session.php";
            
            $login = new loginControl($email,$pwd);

            // Running error handlers
            $userData = $login->login();

            if ($userData === false) {
            // Grab the errors from the class and put them in the session
            $_SESSION["errorslogin"] = $login->errors;
            header("Location: ../public/index.php?login=failed");
            exit();
            }

            $_SESSION["user_id"] = $userData["usr_ID"];

            // Directs you to the page based on role
            if($userData["Role"] == "Inventor")
            {
                header("Location: ../public/disclosure/disclosure.php");
                die();
            } else {
                header("Location: ../public/index.php");
            }
        } catch (PDOException $e) {
            echo "Query Failed" . $e->getMessage() ;
        }
        
    }
    else
    {
        header("Location: ../public/index.php");
        exit();
    }