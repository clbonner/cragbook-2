<?php

namespace Cragbook;

use Cragbook\Request\Request;
use Cragbook\Request\RequestInterface;
use function Cragbook\Helpers\isLoggedIn;

class CragRequest extends Request implements RequestInterface 
{
    public function getRequest($url)
    {
        if (isset($url["areaid"])) {
            $result = $this->getAreaCrags($url["areaid"]);
        }
        elseif (isset($url["cragid"])) {
            $result = $this->getCrag($url["cragid"]);                
        }
        else {
            $result = $this->getAllCrags();
        }

        $this->data = [];
        
        while ($crag = $result->fetch_assoc()) {
            $crag["description"] = htmlspecialchars_decode($crag["description"]);
            $crag["approach"] = htmlspecialchars_decode($crag["approach"]);
            array_push($this->data, $crag);
        }
    }

    public function postRequest()
    {

    }

    private function getAreaCrags($id)
    {
        if (isLoggedIn()) {
            $sql = "SELECT * FROM crags WHERE areaid=" .$id ." ORDER BY name ASC;";
        }
        else {
            $sql = "SELECT * FROM crags WHERE areaid=" .$id ." AND draft=0 ORDER BY name ASC;";
        }

        if (!$result = $this->connection->query($sql)) {
            exit("Error in CragRequest.php: " .$this->connection->error);
        }

        return $result;
    }

    private function getCrag($id)
    {
        if (isLoggedIn()) {
            $sql = "SELECT * FROM crags WHERE cragid=" .$id .";";
        }
        else {
            $sql = "SELECT * FROM crags WHERE cragid=" .$id ." AND draft=0;";
        }

        if (!$result = $this->connection->query($sql)) {
            exit("Error in CragRequest.php: " .$this->connection->error);
        }

        return $result;
    }

    private function getAllCrags()
    {
        if (isLoggedIn()) {
            $sql = "SELECT * FROM crags ORDER BY name ASC;";
        }
        else {
            $sql = "SELECT * FROM crags WHERE draft=0 ORDER BY name ASC;";
        }

        if (!$result = $this->connection->query($sql)) {
            exit("Error in CragRequest.php: " .$this->connection->error);
        }

        return $result;
    }
}