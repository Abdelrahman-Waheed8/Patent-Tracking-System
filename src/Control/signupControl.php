<?php

class SignupControl extends signupModel{
    private $email;
    private $password;
    private $repeatedPass;


    public function __construct($email, $password, $repeatedPass)
    {
        $this->email = $email;
        $this->password = $password;
        $this->repeatedPass = $repeatedPass;
    }

    public function signup()
    {
        if($this->emptyinput() == false)
            {
                echo "All fields needs to be filled! <br>";
                exit();
            }
        if($this->invalidEmail() == false)
            {
                echo "Invalid email! <br>";
                exit();
            }
        if($this->isUserSignedin() == true)
            {
                echo "User already exists!<br>";
                exit();
            }
        if($this->pwdMatch() == false)
            {
                echo "Password do not match!<br>";
                exit();
            }
        
        $this->setUser($this->email, $this->password);
    }

    public function emptyinput()
    {
        $result = true;
        if(empty($this->email) || empty($this->password) || empty($this->repeatedPass))
            {
                $result = false;
            }
        return $result;
    }

    public function invalidEmail()
    {
        $result = true;
        if(!filter_var($this->email,FILTER_VALIDATE_EMAIL))
            {
                $result = false;
            }
        return $result;
    }

    public function isUserSignedin()
    {
        $result = true;
        if(!$this->checkuser($this->email))
            {
                $result = false;
            }
        return $result;
    }

    public function pwdMatch()
    {
        if($this->password !== $this->repeatedPass)
            {
                return false;
            }
        return true;
    }
}