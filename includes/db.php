<?php
ob_start();
session_start();

error_reporting(E_ALL);
ini_set("display_errors", 1);

//database credentials
define('DBHOST','rachelle-cs290-invoice-creator-1571620');
define('DBUSER','rachelle');
define('DBPASS','');
define('DBNAME','c9');

try {
	//create PDO connection 
	$db = new PDO("mysql:host=".DBHOST.";port=3306;dbname=".DBNAME, DBUSER, DBPASS);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
	//show error
    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
    exit;
}
//include the user class, pass in the database connection
include('authentication.php');
$user = new User($db); 
?>