<?php 

namespace Cragbook\Request;
use mysqli;

class Request {
    protected $connection;

    public function __construct()
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

    public function __destruct()
    {
        // close database
        $this->connection->close();
    }

    protected function escapeHTML($array)
    {
        foreach ($array as $value) {
            $value = htmlspecialchars_decode($value);
        }

        return $array;
    }
}

?>