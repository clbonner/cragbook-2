<?php

namespace Cragbook;

use Cragbook\Request\Request;

class Authentication extends Request
{
    // check given username/password match database user
    public function loginUser($username, $password)
    {
        $sql = $this->connection->prepare("SELECT * FROM users WHERE username=?");
        $sql->bind_param("i", $username);
        $result = $this->connection->query($sql);

        if ($result->num_rows() == 1) {
            $credential = $result->fetch_assoc();
            if (password_verify($password, $credential["password"])) {
                $_SESSION["userid"] = $credential["userid"];
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
        if ($this->loginUser()) {
            $password = password_hash($newpassword, PASSWORD_DEFAULT);

            $sql = $this->connection->prepare("UPDATE users SET password=? WHERE username=?");
            $sql->bind_param("ss", $password, $username);
            
            return $sql->execute();
        }
        
        return false;
    }    
}

?>;