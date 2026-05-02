<?php
if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // Grabbing userdata from the form
        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);


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
            $view->sendJsonResponseError($errors);
            exit();
            }

            $_SESSION["user_id"] = $userData["usr_ID"];
            $_SESSION["firstname"] = $userData["first_name"];
            $_SESSION["role"] = $userData["Role"];

            $redirect = $login->checkRole($userData["Role"]);
            $view->sendJsonResponse("success", $redirect);
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