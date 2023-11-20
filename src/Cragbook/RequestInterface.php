<?php 

namespace Cragbook;

interface RequestInterface 
{
    // request all items from the database
    public function getAll();

    // request a single item from the database
    public function getID($id);
}

?>