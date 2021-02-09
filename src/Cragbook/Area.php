<?php

namespace Cragbook;
use Cragbook\Request\RequestInterface;
use \mysqli;

include(__DIR__ ."/Request/RequestInterface.php");


class AreaRequest implements RequestInterface {
    private $data;
    private $connection;

    function __construct()
    {
        //open database
        $this->connection = new mysqli(DATABASE["hostname"], DATABASE["user"], DATABASE["password"], DATABASE["name"], DATABASE["port"]);
    
        if ($this->connection->connect_error) {
            exit("Connection failed: " . $this->connection->connect_error);
        }
    }

    function __destruct()
    {
        $this->connection->close();
    }

    public function getData($method, $url)
    {
        // get area
        if ($method == "GET") {
            if (isset($url["areaid"])) {
                if (!is_numeric($url["areaid"])) exit;
            
                if (isset($_SESSION["userid"])) {
                    $sql = "SELECT * FROM areas WHERE areaid=" .$url["areaid"] .";";
                } 
                else {
                    $sql = "SELECT * FROM areas WHERE areaid=" .$url["areaid"] ." AND public=1;";
                }

                if (!$result = $connection->query($sql)) {
                    exit("Error in area_json.php: " .$connection->error);
                }
                
                $this->data = $result->fetch_assoc();
            }
        }
        
        // get all areas in database   
        else {
            if (isset($_SESSION["userid"])) {
                $sql = "SELECT * FROM areas ORDER BY name ASC;";
            }
            else {
                $sql = "SELECT * FROM areas WHERE public=1 ORDER BY name ASC;";
            }

            if (!$result = $connection->query($sql)) {
                exit("Error in area_json.php: " .$connection->error);
            }
            
            $this->data = [];
            while ($area = $result->fetch_assoc()) {
                $area["description"] = htmlspecialchars_decode($area["description"]);
                array_push($this->data, $area);
            }
        }
    }

    public function getJSON()
    {
        return json_encode($this->data);
    }
}

?>