<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * include/functions.php 
 * System functions and variables for accessing and displaying data.
*/

namespace Cragbook\Functions;

// connect the user to the database
function connectToDatabase() {
    require(__DIR__ ."/../config.php");
    
    $db = new mysqli($host, $dbuser, $dbpass, $dbname, $dbport);
    
    if ($db->connect_error)
        error("Connection failed: " . $db->connect_error);
    else
        return $db;
}

// Performs security checks on data that will be outputted as html
function sec_check($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// sets specific session data for adding and editing areas/crags/routes
function set_data($action, $id) {
    $_SESSION["action"] = $action;
    $_SESSION["id"] = $id;
}

// clears any set session data
function clear_data() {
    unset($_SESSION["action"]);
    unset($_SESSION["id"]);
}

// check user is logged in before performing any database updates
function login_check() {
    if (!isset($_SESSION["userid"]))
        exit;
}

?>
