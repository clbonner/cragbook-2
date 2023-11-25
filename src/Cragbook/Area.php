<?php

namespace Cragbook;

class AreaRequest extends Request implements RequestInterface 
{
    // returns all areas from the database
    public function getAll()
    {
        try {
            if (AuthRequest::isLoggedIn()) {
                $sql = "SELECT * FROM areas ORDER BY name ASC;";
            }
            else {
                $sql = "SELECT * FROM areas WHERE draft=0 ORDER BY name ASC;";
            }
    
            $result = $this->connection->query($sql);
        }
        catch (PDOException $e) {
            print("Error getting areas from database.");
            return false;
        }

        return json_encode($result->fetchAll());
    }

    // returns a single area from the database
    public function getID($id) 
    {
        if (!is_numeric($id)) exit;
        
        $areaInfo = $this->getArea($id);
        $area = $areaInfo[0];
        $area["crags"] = $this->getCrags($id);

        if ($areaInfo == false || $area["crags"] == false) {
            print("Error getting area from database.");
            return false;
        }

        return json_encode($area);
    }

    // return area data
    private function getArea($id)
    {
        try {
            if (AuthRequest::isLoggedIn()) {
                $sql = $this->connection->prepare("SELECT * FROM areas WHERE areaid=:id;");
            } 
            else {
                $sql = $this->connection->prepare("SELECT * FROM areas WHERE areaid=:id AND draft=0;");
            }
    
            $sql->bindParam(':id', $id);
    
            if (!$sql->execute()) {
                return false;
            }
            
            return $sql->fetchAll();
        }
        catch (PDOException $e) {
            return false;
        }
    }

    // return a list of crags for the given area
    private function getCrags($id)
    {
        try {
            if (AuthRequest::isLoggedIn()) {
                $sql = $this->connection->prepare("SELECT * FROM crags WHERE areaid=:id;");
            } 
            else {
                $sql = $this->connection->prepare("SELECT * FROM crags WHERE areaid=:id AND draft=0;");
            }
    
            $sql->bindParam(':id', $id);
    
            if (!$sql->execute()) {
                return false;
            }
            
            return $sql->fetchAll();
        }
        catch (PDOException $e) {
            return false;
        }
    }
    
    /*
     * Add new area to the database.
     * Accepts one variable containing key value pairs for the area.
     * name (text), description (text), location (latitude & longitude),
     * draft (int 1 or 0).
     */
    public function addArea($area)
    {
        if (AuthRequest::isLoggedIn()) {
            try {
                $sql = $this->connection->prepare(
                    "INSERT INTO areas (name, description, location, draft) VALUES (:name, :description, :location, :draft)"
                );
                
                $sql->bindParam(':name', $area["name"]);
                $sql->bindParam(':description', $area["description"]);
                $sql->bindParam(':location', $area["location"]);
                $sql->bindParam(':draft', $area["draft"]);
                
                if (!$sql->execute()) {
                    return false;
                }
                else return true;
            }
            catch (PDOException $e) {
                return false;
            }
        }
        else {
            print("You must be logged in to perform this operation.");
        }

    }

    /*
     * Update area in the database.
     * Accepts one variable containing key value pairs for the area.
     * areaid (int) name (text), description (text), location (latitude & longitude),
     * draft (int 1 or 0).
     */
    public function updateArea($area)
    {
        if (AuthRequest::isLoggedIn()) {
            try {
                $sql = $this->connection->prepare(
                    "UPDATE areas SET name=:name, description=:description, location=:location, draft=:draft WHERE areaid=:areaid"
                );
                
                $sql->bindParam(':areaid', $area["areaid"]);
                $sql->bindParam(':name', $area["name"]);
                $sql->bindParam(':description', $area["description"]);
                $sql->bindParam(':location', $area["location"]);
                $sql->bindParam(':draft', $area["draft"]);
                
                if (!$sql->execute()) {
                    return false;
                }
                else return true;
            }
            catch (PDOException $e) {
                return false;
            }
            
        }
        else {
            print("You must be logged in to perform this operation.");
        }
    }

    // todo
    public function deleteArea($id)
    {
        // get area and crag details
        $sql = "SELECT * FROM areas WHERE areaid=" .$_SESSION["id"] .";";
        if (!$result = $db->query($sql))
            error("Error in admin/area.php: ");
        else
            $area = $result->fetch_assoc();


        $sql = "SELECT * FROM crags WHERE areaid=" .$area["areaid"];
        if (!$result = $db->query($sql)) 
            error("Error in admin/area.php: ");
        else {
            $crags = [];
            while($row = $result->fetch_assoc())
                array_push($crags, $row);
        }

        // remove crags and routes
        foreach ($crags as $crag) {
            $sql = "DELETE FROM crags WHERE cragid=" .$crag["cragid"] .";";
            if (!$result = $db->query($sql))
                error("Error in admin/area.php: ");
            
            $sql = "DELETE FROM routes WHERE cragid=" .$crag["cragid"] .";";
            if (!$result = $db->query($sql))
                error("Error in admin/area.php: ");
        }

        // remove area
        $sql = "DELETE FROM areas WHERE areaid=" .$area["areaid"] .";";
        if (!$result = $db->query($sql))
            error("Error in admin/area.php: ");
    }
}

?>