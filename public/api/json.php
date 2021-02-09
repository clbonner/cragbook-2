<?php

namespace Cragbook;

include(__DIR__ ."/../../src/Cragbook/Cragbook.php");

$area = new AreaRequest();
$area->getData($_SERVER["REQUEST_METHOD"], $_GET["areaid"]);
echo $area->getJSON();


/* Return json data from csv flat file (for development purposes only)
if($_SERVER["REQUEST_METHOD"] == "GET")
{
    $data = [];

    if (!$csvfile = fopen(__DIR__ ."/../../csv/" .$_GET["file"] .".csv", "r")) {
        exit("Error: could not open file path.");
    }

    $headers = fgetcsv($csvfile);
    
    while (!feof($csvfile)) {
        $line = fgetcsv($csvfile);
        $arr = [];

        for ($i = 0; $i < count($line); $i++) {
            $arr[$headers[$i]] = $line[$i];
        }
        array_push($data, $arr);
    }

    echo json_encode($data);
}*/

?>
