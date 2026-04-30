<?php 

class adminView
{
    public function displayErrorcreateUser()
    {

        if(isset($_SESSION["errorCreateUser"]))
            {
                $errors = $_SESSION["errorCreateUser"];
                foreach ($errors as $error) {
                echo '<p class="error-message">' . $error . '</p>';
            }
            
            unset($_SESSION["errorCreateUser"]);
            }
    }
}