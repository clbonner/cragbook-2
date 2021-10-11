<?php 

namespace Cragbook\Request;
use PDO;

class Request {
    protected $connection;

    public function __construct()
    {
        require __DIR__ ."/../../../config.php";
        
        // open database
        $this->connection = new PDO(
            "mysql:host={$DATABASE["hostname"]}:{$DATABASE["port"]};dbname={$DATABASE["name"]}",
            $DATABASE["user"], 
            $DATABASE["password"]
        );
    }

    public function __destruct()
    {
        // close database
        $this->connection = null;
    }
}

?>