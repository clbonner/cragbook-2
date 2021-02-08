<?php

namespace Cragbook;

use Cragbook\Request;

include(__DIR__ ."/Request/RequestInterface.php");

class AreaRequest implements RequestInterface {
    private $data;
    private $db;

    function __construct($database) {
        //open database
        $this->db = new mysqli($host, $dbuser, $dbpass, $dbname, $dbport);
    
        if ($db->connect_error) {
            exit("Connection failed: " . $db->connect_error);
        }

    }

    public function getData($method, $id) {
        if ($method == "GET") {
        
            // get area
            if (isset($id["areaid"])) {
                if (!is_numeric($id["areaid"])) exit;
            
                if (isset($_SESSION["userid"])) {
                    $sql = "SELECT * FROM areas WHERE areaid=" .$id["areaid"] .";";
                } 
                else {
                    $sql = "SELECT * FROM areas WHERE areaid=" .$id["areaid"] ." AND public=1;";
                }

                if (!$result = $db->query($sql)) {
                    exit("Error in area_json.php: " .$db->error);
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

            if (!$result = $db->query($sql)) {
                exit("Error in area_json.php: " .$db->error);
            }
            
            $this->data = [];
            while ($area = $result->fetch_assoc()) {
                $area["description"] = htmlspecialchars_decode($area["description"]);
                array_push($this->data, $area);
            }
        }
    }

    public function getJSON() {
        return json_encode($this->data);
    }
}

?>