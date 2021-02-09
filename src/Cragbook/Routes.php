<?php

namespace Cragbook;
use Cragbook\Request\RequestInterface;

include(__DIR__ ."/Request/RequestInterface.php");

class RoutesInterface implements RequestInterface
{
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
            // send JSON data for routes at crag
            if (isset($query["areaid"])) {
                
                if (isset($_SESSION["userid"])) {
                    $sql = "SELECT cragid,name FROM crags WHERE areaid = ". $query["areaid"] ." ORDER BY name ASC;";
                }
                else {
                    $sql = "SELECT cragid,name FROM crags WHERE areaid = ". $query["areaid"] ." AND public=1 ORDER BY name ASC;";
                }

                if (!$result = $connection->query($sql)) {
                    exit("Error in route_json.php: " .$connection->error);
                }
                elseif ($result->num_rows > 0) {
                    // store crags in array
                    $crags = [];
                    while($row = $result->fetch_assoc()) 
                        array_push($crags, $row);

                    // get cragid's for area
                    foreach($crags as $crag) {
                        $values = $values . $crag["cragid"] . ",";
                    }
                    $values[strlen($values) - 1] = " ";
                    
                    $sql = "SELECT * FROM routes WHERE cragid IN (". $values .") ORDER BY orderid;";
                    
                    if (!$result = $connection->query($sql)) {
                        ajax_err("Error in route_json.php: " .$connection->error);
                    }
                    
                    $routes = [];
                    while ($route = $result->fetch_assoc()) {
                        array_push($routes, $route);
                    }
                    
                    if (!isset($_SESSION["userid"])) {
                        for ($i = 0; $i < sizeof($routes); $i++) {
                            if ($routes[$i]["private"] == 1) {
                                $routes[$i]["description"] = "";
                            }
                        }
                    }
                }
                else {
                    $routes = "";
                }
            }
            
            // send JSON data for routes at crag
            if (isset($query["cragid"])) {
                
                $sql = "SELECT * FROM routes WHERE cragid = ". $query["cragid"] ." ORDER BY orderid ASC;";
                
                if (!$result = $connection->query($sql)) {
                    exit("Error in route_json.php: " .$connection->error);
                }
                
                $routes = [];
                while ($route = $result->fetch_assoc()) {
                    array_push($routes, $route);
                }
                
                if (!isset($_SESSION["userid"])) {
                    foreach ($routes as $key => $value) {
                        if ($routes[$key]["private"] == 1) {
                            $routes[$key]["description"] = "";
                        }
                    }
                }
                
                // send routes as JSON
                echo json_encode($routes);
            }
        }

        // update route order for crag
        if ($method == "POST") {
            
            login_check();
            
            $routes = urldecode($_POST["routes"]);
            $routes = json_decode($routes, true);
            
            // update routes in database
            foreach ($routes as $route) {
                $sql = "UPDATE routes SET orderid=" .$route["orderid"] ." WHERE routeid=" .$route["routeid"] .";";
                
                if(!$connection->query($sql)){
                    exit("Error in route_json.php: " .$connection->error);
                }
            }
        }
    }
}


?>