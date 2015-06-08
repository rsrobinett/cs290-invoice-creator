<?php
// require('db.php');
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once 'functions.php';
require_once 'src/dbCredentials.php';
require_once 'src/dbConnection.php';

$username = $_POST['username'];
$password = $_POST['password'];
$action = $_POST['action'];

//validate login
function validateLogin($username, $password, $mysqli) {
    $user = getUserName($username, $mysqli);
    return $password == $user['password'];
}

// include_once "$_SERVER[DOCUMENT_ROOT]/src/dbCredentials.php";

// include_once "$_SERVER[DOCUMENT_ROOT]/src/dbConnection.php";

// include_once "$_SERVER[DOCUMENT_ROOT]/src/validation.php";

global $mysqli;

if (!$mysqli) {
    $mysqli = createDBConnection($dbhost, $dbuser, $dbpass, $dbname);
}

    
function setSessionUsername() {
    if(isset($_POST['username']) || ($_POST['username'] !== $_SESSION['username'])){
       $_SESSION['username'] = $_POST['username'];
    }       
}

function login($mysqli, $db) {
    setSessionUsername();
}

function usernameAndPasswordInDB($username, $password, $mysqli, $db) {
    if (!$mysqli || $mysqli->connect_error) {
        echo "<div class='error'>Connection error " .$mysqli->connect_error. " " .$mysqli->connect_error. "</div>";
    }
    
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

//return codes:
// 0 = fail
// 1 = valid
// 2 = username exists
// 3 = blank username
// 4 = blank password
if (empty($username)) exit('3');
if (empty($password)) exit('4');

if ($action === 'register') {
    // $user = getUserName($username, $mysqli);
    
    // //see if userid exists
    // if ($user['name']) exit('2');
    // addUser($username, $password, $mysqli);
    
    // $user = getUserName($username, $mysqli);
    // session_start();
    // $_SESSION['username'] = $username;
    // $_SESSION['userid'] = $user['userid'];
    // print '1';
    // exit();
} else if ($action === 'logout') {
    session_start();
    $_SESSION = array();
    session_destroy();
    redirect('index.php');
}

if (usernameAndPasswordInDB($username, $password, $mysqli, $db))
{
    // $user = getUserName($username, $mysqli);
    session_start();
    $_SESSION['username'] = $username;
    // $_SESSION['userid'] = $user['userid'];
    exit('1');
}

exit('0');

?>