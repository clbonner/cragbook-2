<?php 

namespace Cragbook\Request;

interface RequestInterface {
    // Gets data from the database
    public function getData();

    // Returns data as JSON encoded string
    public function getJSON();
}

?>