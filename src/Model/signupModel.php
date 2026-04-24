<?php

class signupModel extends DBH{
    protected function checkuser($email)
    {
        $query= "SELECT Email FROM user WHERE Email = :email;";

        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = null;
        return $result;
    }

    protected function setUser($email, $password)
    {
        $query= "INSERT INTO user (Email, pwd_hash, `Role`) VALUES (:email,:hashedpwd,'Inventor');";

        $pwdhash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":hashedpwd", $pwdhash);
        $stmt->execute();

        $stmt = null;
    }
}