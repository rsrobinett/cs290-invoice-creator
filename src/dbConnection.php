<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once "dbCredentials.php";

global $mysqli;

function createDBConnection($dbhost, $dbuser, $dbpass, $dbname){
    global $mysqli;
    $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
    // Check connection
    if (!$mysqli || $mysqli->connect_error) {
        echo "Connection error " .$mysqli->connect_error. " " .$mysqli->connect_error;
    }
    return $mysqli;
    
}


$mysqli = createDBConnection($dbhost, $dbuser, $dbpass, $dbname);

?>