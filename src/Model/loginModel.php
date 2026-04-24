<?php

class loginModel extends DBH{
    protected function getuser($email){
        $query= "SELECT usr_ID, Email, pwd_hash, `Role`, first_name, last_name FROM user WHERE Email = :email;";

        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = null;
        return $result;
    }
}
