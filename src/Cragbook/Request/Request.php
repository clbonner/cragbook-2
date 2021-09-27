<?php 

namespace Cragbook\Request;
use mysqli;

class Request {
    public $connection;
    public $data;

    function __construct()
    {
        require __DIR__ ."/../../../config.php";
        // open database
        $this->connection = new mysqli(
            $DATABASE["hostname"], 
            $DATABASE["user"], 
            $DATABASE["password"], 
            $DATABASE["name"], 
            $DATABASE["port"]
        );
    
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