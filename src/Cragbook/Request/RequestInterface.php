<?php 

namespace Cragbook\Request;

interface RequestInterface 
{
    public function getAll();

    public function getID($id);
}

?>