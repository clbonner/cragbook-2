<?php

// controller for user authentication

namespace Cragbook;

use Cragbook\AuthRequest;

include(__DIR__ ."/../../src/Cragbook/Cragbook.php");

if ($_GET["request"] == "isloggedin") {
    var_dump(AuthRequest::isLoggedIn());
}

?>