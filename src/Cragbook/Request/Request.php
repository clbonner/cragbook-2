<?php 

namespace Cragbook\Request;
use PDO;

class Request {
    protected $connection;

    public function __construct()
    {
        require __DIR__ ."/../../../config.php";
        
        // open database
        try {
            $this->connection = new PDO(
                "mysql:host={$DATABASE["hostname"]}:{$DATABASE["port"]};dbname={$DATABASE["name"]}",
                $DATABASE["user"], 
                $DATABASE["password"]
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            print("Error connecting to database.");
        }
    }

    public function __destruct()
    {
        // close database
        $this->connection = null;
    }
}

?>