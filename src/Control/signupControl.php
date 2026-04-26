<?php

class SignupControl extends signupModel{
    private $email;
    private $password;
    private $repeatedPass;
    private $firstname;
    private $lastname;
    public $errors = [];


    public function __construct($email, $password, $repeatedPass,$firstname,$lastname)
    {
        $this->email = $email;
        $this->password = $password;
        $this->repeatedPass = $repeatedPass;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
    }

    public function signup()
    {
        $this->isUserSignedin();
        $this->emptyinput();
        $this->invalidEmail();
        $this->pwdMatch();
        $this->invalidName();
        
        if (!empty($this->errors)) {
            return false; 
        }
        
        $this->setUser($this->email, $this->password , $this->firstname, $this->lastname);
        return true;
    }

    private function emptyinput() {
        if (empty($this->email) || empty($this->password) || empty($this->repeatedPass) || empty($this->firstname) || empty($this->lastname)) {
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

    private function invalidName() {
    // This allows ONLY letters. No spaces, no numbers.
    if (!preg_match("/^[a-zA-Z]*$/", $this->firstname) || !preg_match("/^[a-zA-Z]*$/", $this->lastname)) {
        $this->errors["invalid_name"] = "Names must only contain letters!";
    }
}
}