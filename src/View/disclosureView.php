<?php

class disclosureView
{
    public function displaySessionInfo()
    {
        if(isset($_SESSION["errorsDisclosure"]))
            {
                $errors = $_SESSION["errorsDisclosure"];
                foreach($errors as $error)
                echo "<p class='error-info'>  " . $error . "</p>";            
        }

        unset($_SESSION["errorsDisclosure"]);
    }
}