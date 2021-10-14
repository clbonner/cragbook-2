<?php

namespace Cragbook\Crag;

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
            exit("Error in CragRequest.php: " .$this->connection->errorCode());
        }

        return $result->fetchAll();
    }

    // returns a single crag from the database
    public function getID($id)
    {
        if (!is_numeric($id)) exit;
        
        $cragInfo = $this->getCrag($id);
        $crag = $cragInfo[0];
        $crag["routes"] = $this->getRoutes($id);

        return $crag;
    }

    // returns crag data from the database
    private function getCrag($id)
    {
        if (isLoggedIn()) {
            $sql = $this->connection->prepare("SELECT * FROM crags WHERE cragid=:id;");
        }
        else {
            $sql = $this->connection->prepare("SELECT * FROM crags WHERE cragid=:id AND draft=0;");
        }

        $sql->bindParam(':id', $id);

        if (!$sql->execute()) {
            exit("Error in CragRequest.php: " .$this->connection->errorCode());
        }

        return $sql->fetchAll();
    }

    // returns a list of routes for the given crag
    private function getRoutes($id)
    {
        $sql = $this->connection->prepare("SELECT * FROM routes WHERE cragid=:id ORDER BY orderid ASC;");
        $sql->bindParam(':id', $id);

        if (!$sql->execute()) {
            exit("Error in CragRequest.php: " .$this->connection->errorCode());
        }

        return $sql->fetchAll();
    }
}