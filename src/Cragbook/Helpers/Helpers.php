<?php

namespace Cragbook\Helpers;

// escape any data for input/output
function sanitiseData($data) 
{
    return htmlentities($data, ENT_QUOTES, 'UTF-8');
}

// check user is logged in before performing any database inputs
function isLoggedIn() 
{
    return isset($_SESSION["userid"]) ? true : false;
}

?>
