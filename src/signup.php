<?php
if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // Grabbing userdata from the form
        $repeatedpwd = $_POST["repeatedpassword"];
        $email = $_POST["email"];
        $pwd = $_POST["password"];

        session_start();

        try {
            //Instantiate signupController class
            include "config/config.php";
            include "model/signupModel.php";
            include "control/signupControl.php";

            $signup = new SignupControl($email,$pwd,$repeatedpwd);

            // Running error handlers
            if ($signup->signup() === false) {
            // Grab the errors from the class and put them in the session
            $_SESSION["errorsignup"] = $signup->errors;
            header("Location: ../public/index.php?signup=failed");
            exit();
            }

            // Returning back to front page
            header("Location: ../public/index.php?signup=success");
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
