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

    protected function setUser($email, $password, $firstname, $lastname)
    {
        $query= "INSERT INTO user (Email, pwd_hash, `Role`, first_name, last_name) VALUES (:email,:hashedpwd,'Inventor', :first_name, :last_name);";

        $pwdhash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":hashedpwd", $pwdhash);
        $stmt->bindParam(":first_name",$firstname);
        $stmt->bindParam(":last_name",$lastname);
        $stmt->execute();

        $stmt = null;
    }
}