<?php
if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // Grabbing userdata from the form
        $email = $_POST["email"];
        $password = $_POST["password"];


        try {
            //Instantiate signupController class
            include "config/config.php";
            include "model/loginModel.php";
            include "control/loginControl.php";
            include "view/loginView.php";
            include "config/config_session.php";
            
            $login = new loginControl($email,$password);
            $view = new loginView();

            $userData = $login->login();
            
            // Running error handlers
            if ($userData === false) {
            $errors = $login->errors;
            $view->displayErrorlogin($errors);
            exit();
            }

            $_SESSION["user_id"] = $userData["usr_ID"];
            $_SESSION["firstname"] = $userData["first_name"];

            $view->displaySuccess();
            exit();
        } catch (PDOException $e) {
            echo "Query Failed" . $e->getMessage() ;
        }
        
    }
    else
    {
        header("Location: ../public/index.php");
        exit();
    }