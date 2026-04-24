<?php

class loginModel extends DBH{
    protected function getuser($email){
        $query= "SELECT Email, pwd_hash FROM user WHERE Email = :email;";

        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = null;
        return $result;
    }
}
