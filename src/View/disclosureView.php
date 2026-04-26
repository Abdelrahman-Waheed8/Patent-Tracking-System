<?php

class disclosureView
{
    public function displaySessionInfo()
    {
        if(isset($_SESSION["firstname"]))
            {
echo "<p class='session-info'>Welcome Back   <span>  " . $_SESSION['firstname'] . "</span></p>";            }
    }
}