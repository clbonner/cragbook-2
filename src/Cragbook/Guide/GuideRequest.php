<?php

namespace Cragbook\Guide;

use Cragbook\Request\Request;
use Cragbook\Request\RequestInterface;
use Cragbook\Authentication\AuthRequest;

class GuideRequest extends Request implements RequestInterface 
{
    // returns a list of all guidebooks in the database
    public function getAll()
    {
        if (AuthRequest::isLoggedIn()) {
            $sql = "SELECT * FROM guides ORDER BY name ASC;";
        }
        else {
            $sql = "SELECT * FROM guides WHERE draft=0 ORDER BY name ASC;";
        }

        if (!$result = $this->connection->query($sql)) {
            exit("Error retrieving guides data: " .$this->connection->errorCode());
        }

        return $result->fetchAll();
    }

    // combine and return guidebook and crag data
    public function getID($id)
    {
        if (!is_numeric($id)) exit;

        $guideInfo = $this->getGuide($id);
        $guide = $guideInfo[0];
        $guide["crags"] = $this->getCrags($id);

        return $guide;
    }

    // returns the guidebook data given its id
    private function getGuide($id)
    {    
        if (AuthRequest::isLoggedIn()) {
            $sql = $this->connection->prepare("SELECT * FROM guides WHERE guideid=:id");
        }
        else {
            $sql = $this->connection->prepare("SELECT * FROM guides WHERE guideid=:id AND draft=0;");
        }

        $sql->bindParam(":id", $id);

        if (!$sql->execute()) {
            exit("Error retrieving guide data: " .$this->connection->errorCode());
        }
        
        return $sql->fetchAll();
    }

    // returns a list of crags associated with the guidebook id
    private function getCrags($id)
    {    
        if (AuthRequest::isLoggedIn()) {
            $sql = $this->connection->prepare("SELECT * FROM crags WHERE guideid=:id");
        } 
        else {
            $sql = $this->connection->prepare("SELECT * FROM crags WHERE guideid=:id AND draft=0;");
        }

        $sql->bindParam(':id', $id);

        if (!$sql->execute()) {
            exit("Error retrieving guide data: " .$this->connection->errorCode());
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