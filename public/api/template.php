<?php

// Return html template from templates folder.
if($_SERVER["REQUEST_METHOD"] == "GET")
{
    $template = $_GET["id"];

    echo file_get_contents(__DIR__ ."/../../templates/" . $template . ".html");
}

?>
