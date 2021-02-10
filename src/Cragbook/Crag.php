<?php

namespace Cragbook;

use Cragbook\Request\Request;
use Cragbook\Request\RequestInterface;

class CragRequest extends Request implements RequestInterface 
{
    public function getData($method, $url)
    {
        if ($method == "GET") {
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
    }

    private function getQuery($sql)
    {
        if (!$result = $db->query($sql)) {
            exit("Error in crag_json.php: " .$db->error);
        }

        return $result;
    }

    private function getAreaCrags($id)
    {
        if (isLoggedIn()) {
            $sql = "SELECT * FROM crags WHERE areaid=" .$id ." ORDER BY name ASC;";
        }
        else {
            $sql = "SELECT * FROM crags WHERE areaid=" .$id ." AND public=1 ORDER BY name ASC;";
        }

        return $result = $this->getQuery($sql);
    }

    private function getCrag($id)
    {
        if (isLoggedIn()) {
            $sql = "SELECT * FROM crags WHERE cragid=" .$id .";";
        }
        else {
            $sql = "SELECT * FROM crags WHERE cragid=" .$id ." AND public=1;";
        }

        return $result = $this->getQuery($sql);
    }

    private function getAllCrags()
    {
        if (isLoggedIn()) {
            $sql = "SELECT * FROM crags ORDER BY name ASC;";
        }
        else {
            $sql = "SELECT * FROM crags WHERE public=1 ORDER BY name ASC;";
        }

        return $result = $this->getQuery($sql);
    }
}