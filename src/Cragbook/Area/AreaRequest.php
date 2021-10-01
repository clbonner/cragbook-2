<?php

namespace Cragbook;

use Cragbook\Request\Request;
use Cragbook\Request\RequestInterface;
use function Cragbook\Helpers\isLoggedIn;

class AreaRequest extends Request implements RequestInterface 
{
    public function getRequest($url)
    {
        if (isset($url["areaid"])) {
            $this->getID($url["areaid"]);
        }

        else $this->getAll();
    }
    
    public function postRequest()
    {

    }

    public function getID($id) 
    {
        if (!is_numeric($id)) exit;
        
        $area = $this->getArea($id);
        $area["crags"] = $this->getCrags($id);

        return $area;
    }

    public function getAll()
    {
        if (isLoggedIn()) {
            $sql = "SELECT * FROM areas ORDER BY name ASC;";
        }
        else {
            $sql = "SELECT * FROM areas WHERE draft=0 ORDER BY name ASC;";
        }

        if (!$result = $this->connection->query($sql)) {
            exit("Error retrieving all areas data: " .$this->connection->error);
        }
        
        $areas = [];
        while ($area = $result->fetch_assoc()) {
            $area["description"] = htmlspecialchars_decode($area["description"]);
            array_push($areas, $area);
        }

        return $areas;
    }

    private function getArea($id)
    {
        if (isLoggedIn()) {
            $sql = "SELECT * FROM areas WHERE areaid=" .$id .";";
        } 
        else {
            $sql = "SELECT * FROM areas WHERE areaid=" .$id ." AND draft=0;";
        }

        if (!$result = $this->connection->query($sql)) {
            exit("Error retrieving area data: " .$this->connection->error);
        }
        
        return $result->fetch_assoc();
    }

    private function getCrags($id)
    {
        if (isLoggedIn()) {
            $sql = "SELECT * FROM crags WHERE areaid=" .$id .";";
        } 
        else {
            $sql = "SELECT * FROM crags WHERE areaid=" .$id ." AND draft=0;";
        }

        if (!$result = $this->connection->query($sql)) {
            exit("Error retrieving area data: " .$this->connection->error);
        }
        
        $crags = [];

        while ($crag = $result->fetch_assoc()) {
            array_push($crags, $crag);
        }

        return $crags;
    }
    
    private function addArea()
    {

    }

    private function ammendArea()
    {

    }

    private function deleteArea($id)
    {
        // get area and crag details
        $sql = "SELECT * FROM areas WHERE areaid=" .$_SESSION["id"] .";";
        if (!$result = $db->query($sql))
            error("Error in admin/area.php: " .$db->error);
        else
            $area = $result->fetch_assoc();


        $sql = "SELECT * FROM crags WHERE areaid=" .$area["areaid"];
        if (!$result = $db->query($sql)) 
            error("Error in admin/area.php: " .$db->error);
        else {
            $crags = [];
            while($row = $result->fetch_assoc())
                array_push($crags, $row);
        }

        // remove crags and routes
        foreach ($crags as $crag) {
            $sql = "DELETE FROM crags WHERE cragid=" .$crag["cragid"] .";";
            if (!$result = $db->query($sql))
                error("Error in admin/area.php: " .$db->error);
            
            $sql = "DELETE FROM routes WHERE cragid=" .$crag["cragid"] .";";
            if (!$result = $db->query($sql))
                error("Error in admin/area.php: " .$db->error);
        }

        // remove area
        $sql = "DELETE FROM areas WHERE areaid=" .$area["areaid"] .";";
        if (!$result = $db->query($sql))
            error("Error in admin/area.php: " .$db->error);
    }
}

?>