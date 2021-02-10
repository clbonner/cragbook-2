<?php

namespace Cragbook;

use Cragbook\Request\Request;
use Cragbook\Request\RequestInterface;

class AreaRequest extends Request implements RequestInterface 
{
    public function getData($method, $url)
    {
        if ($method == "GET") {
            if (isset($url["areaid"])) {
                $this->getArea($url["areaid"]);
            }
        }
        else $this->getAllAreas();
    }    

    private function getArea($url) 
    {
        if (isset($url["areaid"])) {
            if (!is_numeric($url["areaid"])) exit;
        
            if (isset($_SESSION["userid"])) {
                $sql = "SELECT * FROM areas WHERE areaid=" .$url["areaid"] .";";
            } 
            else {
                $sql = "SELECT * FROM areas WHERE areaid=" .$url["areaid"] ." AND public=1;";
            }

            if (!$result = $connection->query($sql)) {
                exit("Error in area_json.php: " .$connection->error);
            }
            
            $this->data = $result->fetch_assoc();
        }
    }

    private function getAllAreas()
    {
        if (isset($_SESSION["userid"])) {
            $sql = "SELECT * FROM areas ORDER BY name ASC;";
        }
        else {
            $sql = "SELECT * FROM areas WHERE public=1 ORDER BY name ASC;";
        }

        if (!$result = $connection->query($sql)) {
            exit("Error in area_json.php: " .$connection->error);
        }
        
        $this->data = [];
        while ($area = $result->fetch_assoc()) {
            $area["description"] = htmlspecialchars_decode($area["description"]);
            array_push($this->data, $area);
        }
    } 
}

?>