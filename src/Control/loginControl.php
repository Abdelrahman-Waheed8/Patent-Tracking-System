<?php 

class loginControl extends loginModel{
    private $email;
    private $pwd;
    public $errors = [];

    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->pwd= $password;
    }

    public function login()
    {
        $this->emptyinput();
        $this->invalidEmail();
        
        $user = $this->getuser($this->email);
        
        if(!$user)
            {
                $this->errors["login_failed"] = "Invalid Credentials!";
                return false;
            }
        
        if(!password_verify($this->pwd, $user['pwd_hash']))
            {
                $this->errors["login_failed"] = "Invalid Credentials!";
                return false;
            }

        return true;
    }

    private function emptyinput()
    {
        if (empty($this->email) || empty($this->pwd)) {
            $this->errors["empty_input"] = "Fill in all fields!";
        }
    }

    private function invalidEmail() {
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->errors["invalid_email"] = "Invalid email format!";
        }
    }

}