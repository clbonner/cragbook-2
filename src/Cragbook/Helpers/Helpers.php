<?php

namespace Cragbook\Helpers;

// Performs security checks on data that will be outputted as html
function sanitiseInput($data) 
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// check user is logged in before performing any database updates
function isLoggedIn() 
{
    if (isset($_SESSION["userid"])) {
        return true;
    }
    else {
        return false;
    }
}

?>
