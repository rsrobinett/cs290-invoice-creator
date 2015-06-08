<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once "dbCredentials.php";

global $mysqli;

function createDBConnection($dbhost, $dbuser, $dbpass, $dbname){
    $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
    return $mysqli;
    // Check connection
    if (!$mysqli || $mysqli->connect_error) {
        echo "Connection error " .$mysqli->connect_error. " " .$mysqli->connect_error;
    } 
}


$mysqli = createDBConnection($dbhost, $dbuser, $dbpass, $dbname);

?>