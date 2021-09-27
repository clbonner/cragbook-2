<?php

namespace Cragbook;

include(__DIR__ ."/../../src/Cragbook/Cragbook.php");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    switch ($_GET["id"]) {

        case "areas":
            $area = new AreaRequest();
            $area->getRequest($_GET);
            echo $area->getJSON();
            break;

        case "area":
            $area = new AreaRequest();
            $area->getRequest($_GET);
            echo $area->getJSON();
            break;

        case "crags":
            $crag = new CragRequest();
            $crag->getRequest($_GET);
            echo $crag->getJSON();
            break;
    }
        
}

elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {

}

return false;

?>
