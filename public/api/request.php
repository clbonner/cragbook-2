<?php

namespace Cragbook;

include(__DIR__ ."/../../src/Cragbook/Cragbook.php");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    switch ($_GET["request"]) {

        case "areas":
            $area = new AreaRequest();
            echo json_encode($area->getAll());
            break;

        case "area":
            $area = new AreaRequest();
            echo json_encode($area->getID($_GET["id"]));
            break;

        case "crags":
            $crag = new CragRequest();
            echo json_encode($crag->getAll());
            break;

        case "crag":
            $crag = new CragRequest();
            echo json_encode($crag->getID($_GET["id"]));
            break;

        case "guides":
            $guide = new GuideRequest();
            echo json_encode($guide->getAll());
            break;
        
        case "guide":
            $guide = new GuideRequest();
            echo json_encode($guide->getID($_GET["id"]));
            break;
    }
        
}

elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {

}

return false;

?>