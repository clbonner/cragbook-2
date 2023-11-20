<?php

namespace Cragbook;

include(__DIR__ ."/../../src/Cragbook/Cragbook.php");

use Cragbook\AreaRequest;
use Cragbook\CragRequest;
use Cragbook\GuideRequest;


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    switch ($_GET["request"]) {
        case "area":
            $request = new AreaRequest();
            break;

        case "crag":
            $request = new CragRequest();
            break;
        
        case "guide":
            $request = new GuideRequest();
            break;
    }

    if (isset($_GET["id"])) $json = $request->getID($_GET["id"]);
    else $json = $request->getAll();
    echo $json;
}

elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {

}

?>