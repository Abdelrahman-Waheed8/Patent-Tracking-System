<?php

class signupView
{
    public function displayErrorsignup($errors)
    {
        if(!empty($errors))
            {
                foreach($errors as $error)
                {
                    echo $error . "<br>";
                }
            }
        
    }

    public function displaySuccess()
    {
        echo "success";
    }
}