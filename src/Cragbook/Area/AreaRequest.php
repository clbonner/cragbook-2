<?php

namespace Cragbook\Area;

use Cragbook\Request\Request;
use Cragbook\Request\RequestInterface;
use function Cragbook\Helpers\isLoggedIn;

class AreaRequest extends Request implements RequestInterface 
{
    // returns all areas from the database
    public function getAll()
    {
        if (isLoggedIn()) {
            $sql = "SELECT * FROM areas ORDER BY name ASC;";
        }
        else {
            $sql = "SELECT * FROM areas WHERE draft=0 ORDER BY name ASC;";
        }

        if (!$result = $this->connection->query($sql)) {
            exit("Error retrieving all areas data: " .$this->connection->errorCode());
        }

        return $result->fetchAll();
    }

    // returns a single area from the database
    public function getID($id) 
    {
        if (!is_numeric($id)) exit;
        
        $areaInfo = $this->getArea($id);
        $area = $areaInfo[0];
        $area["crags"] = $this->getCrags($id);

        return $area;
    }

    // return area data
    private function getArea($id)
    {
        if (isLoggedIn()) {
            $sql = $this->connection->prepare("SELECT * FROM areas WHERE areaid=:id;");
        } 
        else {
            $sql = $this->connection->prepare("SELECT * FROM areas WHERE areaid=:id AND draft=0;");
        }

        $sql->bindParam(':id', $id);

        if (!$sql->execute()) {
            exit("Error retrieving area data: " .$this->connection->errorCode());
        }
        
        return $sql->fetchAll();
    }

    // return a list of crags for the given area
    private function getCrags($id)
    {
        if (isLoggedIn()) {
            $sql = $this->connection->prepare("SELECT * FROM crags WHERE areaid=:id;");
        } 
        else {
            $sql = $this->connection->prepare("SELECT * FROM crags WHERE areaid=:id AND draft=0;");
        }

        $sql->bindParam(':id', $id);

        if (!$sql->execute()) {
            exit("Error retrieving area data: " .$this->connection->errorCode());
        }
        
        return $sql->fetchAll();
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