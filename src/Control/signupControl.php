<?php

class SignupControl extends signupModel{
    private $email;
    private $password;
    private $repeatedPass;
    public $errors = [];


    public function __construct($email, $password, $repeatedPass)
    {
        $this->email = $email;
        $this->password = $password;
        $this->repeatedPass = $repeatedPass;
    }

    public function signup()
    {
        $this->isUserSignedin();
        $this->emptyinput();
        $this->invalidEmail();
        $this->pwdMatch();
        
        if (!empty($this->errors)) {
            return false; 
        }
        
        $this->setUser($this->email, $this->password);
        return true;
    }

    private function emptyinput() {
        if (empty($this->email) || empty($this->password) || empty($this->repeatedPass)) {
            $this->errors["empty_input"] = "Fill in all fields!";
        }
    }

    private function invalidEmail() {
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->errors["invalid_email"] = "Invalid email format!";
        }
    }

    private function isUserSignedin() {
        if ($this->checkuser($this->email)) {
            $this->errors["user_exists"] = "This email is already registered!";
        }
    }

    private function pwdMatch() {
        if ($this->password !== $this->repeatedPass) {
            $this->errors["password_mismatch"] = "Passwords do not match!";
        }
    }
}