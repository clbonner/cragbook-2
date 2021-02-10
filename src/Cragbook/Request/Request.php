<?php 

namespace Cragbook\Request;

use \mysqli;

class Request {
    private $connection;
    private $data;

    function __construct()
    {
        // open database
        $this->connection = new mysqli(DATABASE["hostname"], DATABASE["user"], DATABASE["password"], DATABASE["name"], DATABASE["port"]);
    
        if ($this->connection->connect_error) {
            exit("Connection failed: " . $this->connection->connect_error);
        }
    }

    function __destruct()
    {
        // close database
        $this->connection->close();
    }

    public function getJSON()
    {
        return json_encode($this->data);
    }
}

?>