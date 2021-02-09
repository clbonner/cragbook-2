<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * include/functions.php 
 * System functions and variables for accessing and displaying data.
*/

namespace Cragbook\Helpers;

// Performs security checks on data that will be outputted as html
function sanitiseInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// check user is logged in before performing any database updates
function isLoggedIn() {
    if (!isset($_SESSION["userid"]))
        exit;
}

?>
