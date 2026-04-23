<?php
if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // Grabbing userdata from the form
        $repeatedpwd = $_POST["repeatedpassword"];
        $email = $_POST["email"];
        $pwd = $_POST["password"];

        try {
            //Instantiate signupController class
            include "config/config.php";
            include "model/signupModel.php";
            include "control/signupControl.php";

            $signup = new SignupControl($email,$pwd,$repeatedpwd);

            // Running error handlers
            $signup->signup();

            // Returning back to front page
            header("Location: ../public/index.html");
            die();
        } catch (PDOException $e) {
            echo "Query Failed" . $e->getMessage() ;
        }
        
    }
    else
    {
        header("Location: public/index.html");
        exit();
    }
