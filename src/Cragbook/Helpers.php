<?php

namespace Cragbook;

// escape any data for input/output
function sanitiseData($data) 
{
    return htmlentities($data, ENT_QUOTES, 'UTF-8');
}

// strip periods and slashes from a string
function stripSlashesPeriods($string) {
    $newstring = "";

    for ($i = 0; $i < strlen($string); $i++) {
        if ($string[$i] !== '.' && $string[$i] !== '/') {
            $newstring = $newstring .$string[$i];
        }
    }

    return $newstring;
}

// check user is logged in before performing any database inputs
function isLoggedIn() 
{
    return isset($_SESSION["userid"]) ? true : false;
}

?>
