<?php

namespace Cragbook;

include(__DIR__ ."/../../src/Cragbook/Cragbook.php");

use Helpers\stripSlashesPeriods;

// Return html template from templates folder.
if($_SERVER["REQUEST_METHOD"] == "GET")
{
    $file = stripSlashesPeriods($_GET["id"]);    

    $template = __DIR__ ."/../../templates/" .$file;

    if (file_exists($template .".html")) $template = $template .".html";
    else $template = $template .".php";

    echo file_get_contents($template);
}

?>
