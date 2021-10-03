<?php

namespace Cragbook;

use Cragbook\Request\Request;
use Cragbook\Request\RequestInterface;
use function Cragbook\Helpers\isLoggedIn;

class CragRequest extends Request implements RequestInterface 
{
    // returns a list of all crags in the database
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
            array_push($crags, $crag);
        }

        return $crags;
    }

    // returns a single crag from the database
    public function getID($id)
    {
        $crag = $this->getCrag($id);
        $crag["routes"] = $this->getRoutes($id);

        return $crag;
    }

    // returns crag data from the database
    private function getCrag($id)
    {
        if (isLoggedIn()) {
            $sql = "SELECT * FROM crags WHERE cragid=" 
                .$this->connection->real_escape_string($id) .";";
        }
        else {
            $sql = "SELECT * FROM crags WHERE cragid=" 
                .$this->connection->real_escape_string($id) ." AND draft=0;";
        }

        if (!$result = $this->connection->query($sql)) {
            exit("Error in CragRequest.php: " .$this->connection->error);
        }

        return $result->fetch_assoc();
    }

    // returns a list of routes for the given crag
    private function getRoutes($id)
    {
        $sql = "SELECT * FROM routes WHERE cragid=" 
            .$this->connection->real_escape_string($id) ." ORDER BY orderid ASC;";

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