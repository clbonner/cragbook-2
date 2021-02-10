<?php

namespace Cragbook;

use Cragbook\RequestRequest;
use Cragbook\Request\RequestInterface;

class RoutesRequest extends Request implements RequestInterface
{
    public function getData($method, $url) 
    {
        if ($method == "GET" && isset($url["cragid"])) {
            $this->getRoutes($url["cragid"]);
        }

        elseif ($method == "POST" && isLoggedIn()) $this->updateRouteOrder($_POST["routes"]);
    }

    private function getRoutes($id)
    {
        $sql = "SELECT * FROM routes WHERE cragid = " .$id ." ORDER BY orderid ASC;";
                
        if (!$result = $connection->query($sql)) {
            exit("Error in route_json.php: " .$connection->error);
        }
        
        $this->data = [];
        while ($route = $result->fetch_assoc()) {
            array_push($this->data, $route);
        }
    }

    private function updateRouteOrder($data)
    {
        $routes = urldecode($data);
        $routes = json_decode($routes, true);
        
        foreach ($routes as $route) {
            $sql = "UPDATE routes SET orderid=" .$route["orderid"] ." WHERE routeid=" .$route["routeid"] .";";
            
            if(!$connection->query($sql)){
                exit("Error in route_json.php: " .$connection->error);
            }
        }
    }
}

?>