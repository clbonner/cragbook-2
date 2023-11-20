<?php

namespace Cragbook;

class AuthRequest extends Request
{
    // check given username/password match database user
    public function loginUser($username, $password)
    {
        $sql = $this->connection->prepare("SELECT * FROM users WHERE username=:username");
        $sql->bindParam(':username', $username);
        $sql->execute();

        if ($sql->rowCount() == 1) {
            $credential = $sql->fetchAll();
            if ($credential[0]["username"] == $username && password_verify($password, $credential[0]["password"])) {
                $_SESSION["userid"] = $credential[0]["userid"];
                return true;
            }
        }        

        return false;
    }

    public function logoutUser()
    {
        unset($_SESSION["userid"]);
    }

    static public function isLoggedIn()
    {
        return isset($_SESSION["userid"]);
    }
    
    // update password in database with new hash
    public function changePassword($username, $password, $newpassword)
    {
        if ($this->loginUser($username, $password)) {
            $password = password_hash($newpassword, PASSWORD_DEFAULT);

            $sql = $this->connection->prepare("UPDATE users SET password=:password WHERE username=:username");
            $sql->bindParam(':username', $username);
            $sql->bindParam(':password', $password);
            
            return $sql->execute();
        }
        
        return false;
    }    
}

?>