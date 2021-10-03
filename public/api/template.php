<?php

// Return html template from templates folder.
if($_SERVER["REQUEST_METHOD"] == "GET")
{
    $template = __DIR__ ."/../../templates/" .$_GET["id"];

    if (file_exists($template .".html")) $template = $template .".html";
    else $template = $template .".php";

    echo file_get_contents($template);
}

?>
