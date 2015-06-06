<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
session_start();
function getPath(){
    $filepath = explode('/', $_SERVER['PHP_SELF'], -1);
    $filepath = implode('/', $filepath);
    $redirect = "http://" .$_SERVER['HTTP_HOST'].$filepath;
    return $redirect;
}

$dbCredentials = "src/dbCredentials.php";
include($dbCredentials);
global $mysqli;

if(($_SERVER['REQUEST_METHOD'] != 'POST') && !isset($_SESSION['username'])){
     header("Location: ".getPath()."/login.php?action=logout", true);
}

function createDBConnection($dbhost, $dbuser, $dbpass, $dbname){
    $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
    return $mysqli;
    // Check connection
    if (!$mysqli || $mysqli->connect_error) {
        echo "Connection error " .$mysqli->connect_error. " " .$mysqli->connect_error;
    } 
}



function registerUser($mysqli, $db){
    if(verifyUserInDB($mysqli, $db)){
        header("Location: ".getPath()."/login.php?action=logout", true);    
    }
    $userid = addToUser($mysqli, $db);
    addToCompany($mysqli, $db, $userid);
    setSessionUsername();
}

function setSessionUsername(){
    if(isset($_POST['username']) || ($_POST['username'] !== $_SESSION['username'])){
       $_SESSION['username'] = $_POST['username'];
    }       
}


function login($mysqli, $db){

    
    if(!verifyUserInDB($mysqli, $db)){
        header("Location: ".getPath()."/login.php?action=logout", true);    
    }

    setSessionUsername();
}

function verifyUserInDB($mysqli, $db){
    if (!$mysqli || $mysqli->connect_error) {
            echo "<div class='error'>Connection error " .$mysqli->connect_error. " " .$mysqli->connect_error. "</div>";
    }
    
    $username = trim($_POST['username']);
    $hashpass = trim(base64_encode(hash('sha256',$_POST['password'])));
    
    if(!($stmt = $mysqli->prepare("SELECT username FROM $db.user where username = ? AND hashpass = ?"))){
        echo "<div class='error'>Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
    }

    if (!$stmt->bind_param("ss", $username, $hashpass )) {
        echo "<div class='error'>Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }

    if (!$stmt->execute()) {
        echo "<div class='error'>Execute failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }   
    
    if($stmt->num_rows !== 1){
        return true;
    }
    
    return false;
}
    

function addToUser($mysqli, $db){
    if (!$mysqli || $mysqli->connect_error) {
            echo "<div class='error'>Connection error " .$mysqli->connect_error. " " .$mysqli->connect_error. "</div>";
    } 
    
    if(!($stmt = $mysqli->prepare("INSERT INTO $db.user (username, hashpass) VALUES (?, ?)"))){
        echo "<div class='error'>Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
    }
    
    $username = trim($_POST['username']);
    $hashpass = trim(base64_encode(hash('sha256',$_POST['password'])));


    echo "username = $username <p>";
    echo "hashpass = $hashpass <p>";
    

    if (!$stmt->bind_param("ss", $username, $hashpass )) {
        echo "<div class='error'>Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }   
    
    if (!$stmt->execute()) {
        echo "<div class='error'>Execute failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }
    
    $newuserid = $stmt->insert_id;
    
    unset($stmt);
    
    return $newuserid;
}

function addToCompany($mysqli, $db, $userid){
    if (!$mysqli || $mysqli->connect_error) {
            echo "<div class='error'>Connection error " .$mysqli->connect_error. " " .$mysqli->connect_error. "</div>";
    } 
    
    if(!($stmt = $mysqli->prepare("INSERT INTO $db.company (userid, companyname, firstname, lastname, address) VALUES (?, ?, ?, ?, ?)"))){
        echo "<div class='error'>Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
    }


    $companyname = $_POST['companyname'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $address = $_POST['address'];

    echo "companyname = $companyname <p>";
    echo "firstname = $firstname <p>";
    

    if (!$stmt->bind_param("issss", $userid, $companyname, $firstname, $lastname,  $address)) {
        echo "<div class='error'>Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }   
    
    if (!$stmt->execute()) {
        echo "<div class='error'>Execute failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }
    unset($stmt);
}

function getUserIDFromUserName($mysqli, $db){

    if(!($stmt  = $mysqli->prepare("SELECT userid FROM $db.user where username = ?"))){
        echo "<div class='error'>Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
    }
        
    $username = $_SESSION['username'];

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

function printCompanyInfo($mysqli, $db){

    if(!($stmt  = $mysqli->prepare("SELECT companyname, firstname, lastname, address FROM $db.company where userid = ?"))){
        echo "<div class='error'>Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
    }
    
    $userid = getUserIDFromUserName($mysqli, $db);
    
    if (!$stmt->bind_param("s", $userid )) {
        echo "<div class='error'>Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    } 
    
     if (!$stmt->execute()) {
        echo "<div class='error'>Execute failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
    } 
    
    
    $companyname = NULL;
    $firstname = NULL;
    $lastname = NULL;
    $address = NULL;
    
    
    if (!$stmt->bind_result($companyname, $firstname, $lastname, $address)) {
        echo "<div class='error'>Binding results failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }
    
    
    while ($stmt->fetch()) {
        echo "<p> companyname = $companyname";
        echo "<p> firstname = $firstname";
        echo "<p> lastname = $lastname";
        echo "<p> address = $address";
        
        
    }
    
    unset($stmt);
    
}

/*

function getInventory($mysqli, $db){
    $inventory;
    if(isset($_POST['filtercategory']) && $_POST['filtercategory'] !== 'other'){
    //case when filter is set and it is not set to other
        $filterValue = $_POST['filtercategory'];
        if(!($inventory  = $mysqli->prepare("SELECT id, name, category, length, rented FROM $db.inventory where category = ? ORDER BY name"))){
            echo "<div class='error'>Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
        }
        
        if (!$inventory->bind_param("s", $filterValue )) {
            echo "<div class='error'>Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
        }  
        
    } else {
    //case when no filter is set or filter is set to other    
        if(!($inventory  = $mysqli->prepare("SELECT id, name, category, length, rented FROM $db.inventory ORDER BY name"))){
            echo "<div class='error'>Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
        }
    }
    
    if (!$inventory->execute()) {
        echo "<div class='error'>Execute failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
    } else {
        return $inventory;
    }
}
*/

/*
function printBody(){
    if(session_status() == PHP_SESSION_ACTIVE){
      if(($_SERVER['REQUEST_METHOD'] != 'POST') && !isset($_SESSION['username'])){
            header("Location: ".getPath()."/login.php?action=logout", true);
      } else if(isset($_POST['username']) && ( $_POST['username'] == null || $_POST['username'] == '')){
            echo "<div>A username must be entered. Click <a href=\"".getPath()."/login.php?action=logout\"> here</a> to return to the login screen.</div>"; 
      } else if(isset($_POST['register'])){
               
      } else {
            
            if(isset($_POST['username']) && ($_POST['username'] !== $_SESSION['username'])){
                  $_SESSION['username'] = $_POST['username'];
                  $_SESSION['visits'] = 0;
            } else {
                  $_SESSION['visits']++;
            }
 
            echo "<div>Hello $_SESSION[username] you have visited this page $_SESSION[visits] times before.</div>";  
            echo "<div>Click <a href=\"".getPath()."/login.php?action=logout\">here</a> to logout.</div>"; 
            echo "<div>Click <a href=\"".getPath()."/content2.php\">here</a> to get more content.</div>"; 
      }
} else {
      header("Location: ".getPath()."/login.php?action=logout", true);
      }  
}
*/

$mysqli = createDBConnection($dbhost, $dbuser, $dbpass, $dbname);
if(isset($_POST['register']))
    {   
    echo "registering user";
    registerUser($mysqli, $db);
    echo $_SESSION['username']."is logged in";
    }

if(isset($_POST['login']))
    {   
    echo "loging in user";
    login($mysqli, $db);
    echo $_SESSION['username']."is logged in";
    }

if(!isset($_SESSION['username'])){
  echo "session username is not set";
  //setSessionUsername();
} 


?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice Page</title>
</head>

<body>
<?php
    echo $_SESSION['username'];
    echo "userID:".getUserIDFromUserName($mysqli, $db);
   printCompanyInfo($mysqli, $db);
?>
 <div><a href="<?php echo getPath().'/login.php?action=logout'?>">logout</a></div>
</body>

</html>

