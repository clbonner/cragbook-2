<?php

namespace Cragbook;

class CragRequest extends Request implements RequestInterface 
{
    // returns a list of all crags in the database
    public function getAll()
    {
        if (AuthRequest::isLoggedIn()) {
            $sql = "SELECT * FROM crags ORDER BY name ASC;";
        }
        else {
            $sql = "SELECT * FROM crags WHERE draft=0 ORDER BY name ASC;";
        }

        if (!$result = $this->connection->query($sql)) {
            exit("Error retrieving crags.");
        }

        return json_encode($result->fetchAll());
    }

    // returns a single crag from the database
    public function getID($id)
    {
        if (!is_numeric($id)) exit;
        
        $cragInfo = $this->getCrag($id);
        $crag = $cragInfo[0];
        $crag["routes"] = $this->getRoutes($id);

        return json_encode($crag);
    }

    // returns crag data from the database
    private function getCrag($id)
    {
        if (AuthRequest::isLoggedIn()) {
            $sql = $this->connection->prepare("SELECT * FROM crags WHERE cragid=:id;");
        }
        else {
            $sql = $this->connection->prepare("SELECT * FROM crags WHERE cragid=:id AND draft=0;");
        }

        $sql->bindParam(':id', $id);

        if (!$sql->execute()) {
            exit("Error retrieving crag.");
        }

        return $sql->fetchAll();
    }

    // returns a list of routes for the given crag
    private function getRoutes($id)
    {
        $sql = $this->connection->prepare("SELECT * FROM routes WHERE cragid=:id ORDER BY orderid ASC;");
        $sql->bindParam(':id', $id);

        if (!$sql->execute()) {
            exit("Error retrieving crag routes.");
        }

        return $sql->fetchAll();
    }
}