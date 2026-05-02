<?php
if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // Grabbing userdata from the form
        $repeatedpassword = $_POST["repeatedpassword"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $firstname = $_POST["fname"];
        $lastname = $_POST["lname"];


        try {
            //Instantiate signupController class
            include "config/config.php";
            include "model/signupModel.php";
            include "control/signupControl.php";
            include "config/config_session.php";
            include "view/signupView.php";
            
            $signup = new SignupControl($email,$password,$repeatedpassword,$firstname,$lastname);
            $view = new signupView();

            // Running error handlers
            if ($signup->signup() === false) {
                $view->displayErrorsignup($signup->errors);
                exit();
            }

            if($_POST['isLive'] === 'true')
                {
                    exit();
                }

            $signup->setUser($email, $password , $firstname, $lastname);
            $view->displaySuccess();
            die();
        } catch (PDOException $e) {
            echo "Query Failed" . $e->getMessage() ;
        }
        
    }
