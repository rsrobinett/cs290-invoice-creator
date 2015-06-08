<?php

$dbCredentials = "dbCredentials.php";
include_once $dbCredentials;

$dbConnection = "dbConnection.php";
include_once $dbConnection;
/*
global $mysqli;
if(!$mysqli){
    $mysqli = createDBConnection($dbhost, $dbuser, $dbpass, $dbname);
}
*/

function registrationValidation($mysqli, $db){
    
    if( nameIsUnique($mysqli, $db) && passwordIsValid() && usernameIsUnique($mysqli, $db) )
    {
        setSessionUsername();
        return true;
    }
    return false;
}

function passwordIsValid(){
    return true;
}

function nameIsUnique($mysqli, $db){
    return true;
}

function usernameIsUnique($mysqli, $db){
    if (!$mysqli || $mysqli->connect_error) {
            echo "<div class='error'>Connection error " .$mysqli->connect_error. " " .$mysqli->connect_error. "</div>";
    }
    
    $username = trim($_POST['username']);

    if(!($stmt = $mysqli->prepare("SELECT username FROM $db.user WHERE username = ?"))){
        echo "<div class='error'>Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
    }

    if (!$stmt->bind_param("s", $username )) {
        echo "<div class='error'>Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }

    if (!$stmt->execute()) {
        echo "<div class='error'>Execute failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }   
    
    $stmt->store_result();
    
    if($stmt->num_rows === 0){
        unset($stmt);
        return true;
    }

    unset($stmt);
    return false;
}






/*
if(($_SERVER['REQUEST_METHOD'] == 'POST')&&isset($_POST['validateregister'])&&isset($_POST['username'])&&isset($_POST['password'])&&isset($_POST['companyname'])){

    if (registrationValidation($mysqli, $db)){
        
    }
    echo "can't regiser";
    return false;
}
*/
   // echo 'unknown error';
?>