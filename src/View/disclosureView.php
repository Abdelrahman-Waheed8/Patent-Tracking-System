<?php

class disclosureView
{
    public function displaySessionInfo()
    {
        if(isset($_SESSION["firstname"]))
            {
                echo "<p class= 'session-info'> Welcome Back " . $_SESSION["firstname"] . '</p>';
            }
    }
}