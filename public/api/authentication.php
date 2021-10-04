<?php

// controller for user authentication

namespace Cragbook;

use Cragbook\Authentication\AuthRequest;

if ($_GET["request"] == "isloggedin") {
    echo AuthRequest::isLoggedIn();
}

?>