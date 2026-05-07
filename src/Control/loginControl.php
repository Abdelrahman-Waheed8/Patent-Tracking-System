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
        
        if (!empty($this->errors)) {
            return false;
            }
        
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

        return $user;
    }

    private function emptyinput()
    {
        if ($this->email === "" || $this->pwd === "") {
            $this->errors["empty_input"] = "Fill in all fields!";
        }
    }

    private function invalidEmail() {
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->errors["invalid_email"] = "Invalid email format!";
        }
    }

    public function checkRole($role)
    {
        $redirect = "";
        switch ($role) {
            case "Inventor":
                $redirect = "../public/dashboard/dashboard.php";
                break;
            case "IP Counsel":
                $redirect = "../public/ip_counsel/ipcounsel.php";
                break;
            case "Examiner":
                $redirect = "../public/examiner/examiner.php";
                break;
            case "Admin":
                $redirect = "../public/systemAdmin/systemAdmin.php";
                break;
        }
        return $redirect;
    }
}