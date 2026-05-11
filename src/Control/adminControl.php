<?php

class Admin extends signupModel
{
    private $fname;
    private $lname;
    private $email;
    private $password;
    private $role;
    public $errors = [];

    public function __construct($fname,$lname,$email,$password,$role)
    {
        $this->fname = $fname;
        $this->lname = $lname;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    public function createUser()
    {
        $this->emptyinput();
        $this->invalidEmail();

        if (!empty($this->errors)) {
        return false;
        }

        $this->setUser($this->email,$this->password, $this->fname, $this->lname, $this->role);
        return true;
    }

    private function emptyinput()
    {
        if (empty($this->fname) || empty($this->lname) || empty($this->email) || 
            empty($this->password) || empty($this->role)) {

            $this->errors["empty_input"] = "Fill in all fields!";
        }
    }

    private function invalidEmail() {
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->errors["invalid_email"] = "Invalid email format!";
        }
    }

}