<?php

namespace Cragbook;
use Cragbook\Request\RequestInterface;

include(__DIR__ ."/Request/RequestInterface.php");

class CragRequest implements RequestInterface {
    private $data;
    private $connection;

    function __construct()
    {
        //open database
        $this->connection = new \mysqli(DATABASE["hostname"], DATABASE["user"], DATABASE["password"], DATABASE["name"], DATABASE["port"]);
    
        if ($this->connection->connect_error) {
            exit("Connection failed: " . $this->connection->connect_error);
        }

    }

    function __destruct()
    {
        $this->connection->close();
    }

    public function getData($method, $query)
    {
        if ($method == "GET") {
            if (isset($query["areaid"])) {
                // get crags for area
                if (isset($_SESSION["userid"]))
                    $sql = "SELECT * FROM crags WHERE areaid=" .$query["areaid"] ." ORDER BY name ASC;";
                else
                    $sql = "SELECT * FROM crags WHERE areaid=" .$query["areaid"] ." AND public=1 ORDER BY name ASC;";
            }
            elseif (isset($query["cragid"])) {
                // get a single crag
                if (isset($_SESSION["userid"]))
                    $sql = "SELECT * FROM crags WHERE cragid=" .$query["cragid"] .";";
                else 
                    $sql = "SELECT * FROM crags WHERE cragid=" .$query["cragid"] ." AND public=1;";
            }
            else {
                // return all crags
                if (isset($_SESSION["userid"]))
                    $sql = "SELECT * FROM crags ORDER BY name ASC;";
                else 
                    $sql = "SELECT * FROM crags WHERE public=1 ORDER BY name ASC;";
            }
            
            if (!$result = $db->query($sql)) {
                exit("Error in crag_json.php: " .$db->error);
            }
            
            // put crag(s) in to array
            $this->data = [];
            
            while ($crag = $result->fetch_assoc()) {
                $crag["description"] = htmlspecialchars_decode($crag["description"]);
                $crag["approach"] = htmlspecialchars_decode($crag["approach"]);
                array_push($this->data, $crag);
            }
        }
    }

    public function getJSON()
    {
        return json_encode($this->data);
    }
}