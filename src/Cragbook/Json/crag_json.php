<?php

/* This file is part of Cragbook https://github.com/clbonner/cragbook
 * and is licensesed under the GNU General Public License version 3.
 * Copyright 2017 Christopher L Bonner
 *
 * include/crag_json.php 
 * Returns JSON data for crags in database.
 */

require_once(__DIR__ ."/../../config.php");
$db = db_connect();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["areaid"])) {
        // get crags for area
        if (isset($_SESSION["userid"]))
            $sql = "SELECT * FROM crags WHERE areaid=" .$_GET["areaid"] ." ORDER BY name ASC;";
        else
            $sql = "SELECT * FROM crags WHERE areaid=" .$_GET["areaid"] ." AND public=1 ORDER BY name ASC;";
    }
    elseif (isset($_GET["cragid"])) {
        // get a single crag
        if (isset($_SESSION["userid"]))
            $sql = "SELECT * FROM crags WHERE cragid=" .$_GET["cragid"] .";";
        else 
            $sql = "SELECT * FROM crags WHERE cragid=" .$_GET["cragid"] ." AND public=1;";
    }
    else {
        // return all crags
        if (isset($_SESSION["userid"]))
            $sql = "SELECT * FROM crags ORDER BY name ASC;";
        else 
            $sql = "SELECT * FROM crags WHERE public=1 ORDER BY name ASC;";
    }
    
    if (!$result = $db->query($sql)) {
        exit("Error in crag_json.php: " .$db->error);
    }
    
    // put crag(s) in to array
    $crags = [];
    
    while ($crag = $result->fetch_assoc()) {
        $crag["description"] = htmlspecialchars_decode($crag["description"]);
        $crag["approach"] = htmlspecialchars_decode($crag["approach"]);
        array_push($crags, $crag);
    }
    
    // send crags as JSON
    echo json_encode($crags);
}

$db->close();
    
?>