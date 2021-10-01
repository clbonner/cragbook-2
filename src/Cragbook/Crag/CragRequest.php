<?php

namespace Cragbook;

use Cragbook\Request\Request;
use Cragbook\Request\RequestInterface;
use function Cragbook\Helpers\isLoggedIn;

class CragRequest extends Request implements RequestInterface 
{
    public function getAll()
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

        $crags = [];

        while ($crag = $result->fetch_assoc()) {
            $crag["description"] = htmlspecialchars_decode($crag["description"]);
            $crag["approach"] = htmlspecialchars_decode($crag["approach"]);
            array_push($crags, $crag);
        }

        return $crags;
    }

    public function getID($id)
    {
        $crag = $this->getCrag($id);
        $crag["routes"] = $this->getRoutes($id);

        return $crag;
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

        return $result->fetch_assoc();
    }

    private function getRoutes($id)
    {
        $sql = "SELECT * FROM routes ORDER BY orderid ASC;";

        if (!$result = $this->connection->query($sql)) {
            exit("Error in CragRequest.php: " .$this->connection->error);
        }

        $routes = [];

        while ($route = $result->fetch_assoc()) {
            array_push($routes, $route);
        }

        return $routes;
    }
}