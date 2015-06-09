<?php
require_once 'functions.php';
require_once 'dbCredentials.php';
require_once 'dbConnection.php';    
/*
global $mysqli;
if (!$mysqli) {
    $mysqli = createDBConnection($dbhost, $dbuser, $dbpass, $dbname);
    if (!$mysqli || $mysqli->connect_error) {
        echo "<div class='error'>Connection error " .$mysqli->connect_error. " " .$mysqli->connect_error. "</div>";
    }
}
*/

    //Create
function createUser($username, $password){
    global $mysqli;
    global $db;
    if (!$mysqli || $mysqli->connect_error) {
            echo "<div class='error'>Connection error " .$mysqli->connect_error. " " .$mysqli->connect_error. "</div>";
    } 
    
    if(!($stmt = $mysqli->prepare("INSERT INTO $db.user (username, hashpass) VALUES (?, ?)"))){
        echo "<div class='error'>Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
    }
    
    $hashpass = trim(base64_encode(hash('sha256',$password)));

    if (!$stmt->bind_param("ss", $username, $hashpass )) {
        echo "<div class='error'>Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }   
    
    if (!$stmt->execute()) {
        echo "<div class='error'>Execute failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }
    
    $userid = $stmt->insert_id;
    
    unset($stmt);
    
    return $userid;
}

function createInvoice($senderid, $billtoid, $invoicedate, $duedate, $comment){
    global $mysqli;
    global $db;
    if (!$mysqli || $mysqli->connect_error) {
            echo "<div class='error'>Connection error " .$mysqli->connect_error. " " .$mysqli->connect_error. "</div>";
    } 
    
    if(!($stmt = $mysqli->prepare("INSERT INTO $db.invoice (senderid, billtoid, invoicedate, duedate, comment) VALUES (?, ?, ?, ?, ?)"))){
        echo "<div class='error'>Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
    }
    
    if (!$stmt->bind_param("iisss", $senderid, $billtoid, $invoicedate, $duedate, $comment )) {
        echo "<div class='error'>Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }   
    
    if (!$stmt->execute()) {
        echo "<div class='error'>Execute failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }
    
    $userid = $stmt->insert_id;
    
    unset($stmt);
    
    return $userid;
}



function createCompany($companyname, $userid){
    global $mysqli;
    global $db;
    
    if (!$mysqli || $mysqli->connect_error) {
            echo "<div class='error'>Connection error " .$mysqli->connect_error. " " .$mysqli->connect_error. "</div>";
    } 
    
    if(!($stmt = $mysqli->prepare("INSERT INTO $db.company (userid, name) VALUES (?, ?)"))){
        echo "<div class='error'>Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
    }

    if (!$stmt->bind_param("is", $userid, $companyname)) {
        echo "<div class='error'>Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }   
    
    if (!$stmt->execute()) {
        echo "<div class='error'>Execute failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }
    
    $companyid = $stmt->insert_id;
    
    unset($stmt);
    
    return $companyid;
}

    //Read

function getCompanyNames(){
    global $mysqli;
    global $db; 
    
    if(!($stmt = $mysqli->prepare("SELECT companyid, name FROM $db.company
                                   ORDER BY name;"))){
        echo "<div class='error'>Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
    }

    if (!$stmt->execute()) {
        echo "<div class='error'>Execute failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }   

    $companyname = null;
    $companyid = null;
    
    if (!$stmt->bind_result($companyid, $companyname )) {
        echo "<div class='error'>Binding results failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }    
    
    $companyarray = array();
    
    while($stmt->fetch()){
        $companyarray[$companyid]=$companyname;
        }

    unset($stmt);
    
    return $companyarray;     
}


function getCompanyNamebyUsername($username){
    global $mysqli;
    global $db;
    
    if(!($stmt = $mysqli->prepare("SELECT name FROM $db.company 
                                   LEFT JOIN $db.user 
                                   ON $db.company.userid = $db.user.userid
                                   WHERE username = ?"))){
        echo "<div class='error'>Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
    }

    if (!$stmt->bind_param("s", $username)) {
        echo "<div class='error'>Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }

    if (!$stmt->execute()) {
        echo "<div class='error'>Execute failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }   

    $companyname= null;
    
    if (!$stmt->bind_result($companyname )) {
        echo "<div class='error'>Binding results failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }    
    
    while($stmt->fetch()){
        //there should only be one so just loop through it.
        }

    unset($stmt);
    
    return $companyname;      
}

function getCompanyIDbyUsername($username){
    global $mysqli;
    global $db;
    
    if(!($stmt = $mysqli->prepare("SELECT companyid FROM $db.company 
                                   LEFT JOIN $db.user 
                                   ON $db.company.userid = $db.user.userid
                                   WHERE username = ?"))){
        echo "<div class='error'>Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
    }

    if (!$stmt->bind_param("s", $username)) {
        echo "<div class='error'>Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }

    if (!$stmt->execute()) {
        echo "<div class='error'>Execute failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }   

    $companyid= null;
    
    if (!$stmt->bind_result($companyid )) {
        echo "<div class='error'>Binding results failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }    
    
    while($stmt->fetch()){
        //there should only be one so just loop through it.
        }

    unset($stmt);
    
    return $companyid;      
    
}
  

function getUserIDbyUsername($username){
    //not tested yet
    global $mysqli;
    global $db;

    if(!($stmt  = $mysqli->prepare("SELECT userid FROM $db.user where username = ?"))){
        echo "<div class='error'>Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
    }

    if (!$stmt->bind_param("s", $username )) {
        echo "<div class='error'>Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    } 
    
    if (!$stmt->execute()) {
        echo "<div class='error'>Execute failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
    } 
    
    $userid = NULL;
    
    if (!$stmt->bind_result($userid )) {
        echo "<div class='error'>Binding results failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }      
    
    $sessionuserid = NULL;
    
    while ($stmt->fetch()) {
        $sessionuserid = $userid;
    }
    
    unset($stmt);
    
    return $sessionuserid;
}
  
    
function usernameExists($username){
    global $mysqli;
    global $db;
    
    if(!($stmt = $mysqli->prepare("SELECT username FROM $db.user WHERE username = ?"))){
        echo "<div class='error'>Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
    }

    if (!$stmt->bind_param("s", $username)) {
        echo "<div class='error'>Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }

    if (!$stmt->execute()) {
        echo "<div class='error'>Execute failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }   
    
    $stmt->store_result();
    
    if($stmt->num_rows > 0){
        unset($stmt);
        return true;
    }

    unset($stmt);
    return false;    
}

function companyExists($companyname){
    global $mysqli;
    global $db;
    
    if(!($stmt = $mysqli->prepare("SELECT name FROM $db.company WHERE name = ?"))){
        echo "<div class='error'>Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
    }

      if (!$stmt->bind_param("s", $companyname)) {
        echo "<div class='error'>Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }

    if (!$stmt->execute()) {
        echo "<div class='error'>Execute failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }   
    
    $stmt->store_result();
    
    if($stmt->num_rows > 0){
        unset($stmt);
        return true;
    }

    unset($stmt);
    return false;    
}
    //Update
    //Delete


//other db operations
function usernameAndPasswordInDB($username, $password, $mysqli, $db) {
    
    $username = trim($_POST['username']);
    $hashpass = trim(base64_encode(hash('sha256',$_POST['password'])));

    if(!($stmt = $mysqli->prepare("SELECT username FROM $db.user WHERE username = ? AND hashpass = ?"))){
        echo "<div class='error'>Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
    }

    if (!$stmt->bind_param("ss", $username, $hashpass )) {
        echo "<div class='error'>Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }

    if (!$stmt->execute()) {
        echo "<div class='error'>Execute failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }   
    
    $stmt->store_result();
    
    if($stmt->num_rows === 1){
        unset($stmt);
        return true;
    }

    unset($stmt);
    return false;
}


?>



