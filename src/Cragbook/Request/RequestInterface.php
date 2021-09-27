<?php 

namespace Cragbook\Request;

interface RequestInterface 
{
    // receives a GET request
    public function getRequest($url);

    // receives a POST request
    public function postRequest();
}

?>