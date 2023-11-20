<?php

namespace Cragbook;

include(__DIR__ ."/../../src/Cragbook/Cragbook.php");
$_SESSION["userid"] = 1;

$area = [
    "areaid" => 19,
    "name" => "another updated area",
    "description" => "Test decription",
    "location" => "1243143, -43432432",
    "draft" => 0
];

$request = new AreaRequest();

$request->updateArea($area);

?>