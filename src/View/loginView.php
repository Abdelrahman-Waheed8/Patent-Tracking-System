<?php
class loginView
{
    public function displayErrorlogin($errors)
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