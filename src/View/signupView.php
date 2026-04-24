<?php

class signupView
{
    public function displayErrorsignup()
    {

        if(isset($_SESSION["errorsignup"]))
            {
                $errors = $_SESSION["errorsignup"];
                foreach ($errors as $error) {
                echo '<p class="error-message">' . $error . '</p>';
            }

            // Clear the errors so they don't show up again on refresh
            unset($_SESSION["errorsignup"]);
            }
    }
}