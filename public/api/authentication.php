<?php

// controller for user authentication

namespace Cragbook\Authentication;

include(__DIR__ ."/../../src/Cragbook/Cragbook.php");

if ($_GET["request"] == "isloggedin") {
    var_dump(AuthRequest::isLoggedIn());
}

?>