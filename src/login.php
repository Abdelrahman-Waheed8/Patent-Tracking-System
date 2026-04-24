<?php
if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // Grabbing userdata from the form
        $email = $_POST["email"];
        $pwd = $_POST["password"];

        session_start();

        try {
            //Instantiate signupController class
            include "config/config.php";
            include "model/loginModel.php";
            include "control/loginControl.php";

            $login = new loginControl($email,$pwd);

            // Running error handlers
            if ($login->login() === false) {
            // Grab the errors from the class and put them in the session
            $_SESSION["errorslogin"] = $login->errors;
            header("Location: ../public/index.php?login=failed");
            exit();
            }

            // Returning back to front page
            header("Location: ../public/index.php?login=success");
            die();
        } catch (PDOException $e) {
            echo "Query Failed" . $e->getMessage() ;
        }
        
    }
    else
    {
        header("Location: ../public/index.php");
        exit();
    }