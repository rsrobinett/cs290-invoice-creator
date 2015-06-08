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

include_once "$_SERVER[DOCUMENT_ROOT]/src/dbCredentials.php";

include_once "$_SERVER[DOCUMENT_ROOT]/src/dbConnection.php";

include_once "$_SERVER[DOCUMENT_ROOT]/src/validation.php";



global $mysqli;

if(($_SERVER['REQUEST_METHOD'] != 'POST') && !isset($_SESSION['username'])){
    echo "you aren't logged in";
     //header("Location: ".getPath()."/login.php?action=logout", true);
}

function registerUser($mysqli, $db){
    $userid = addToUser($mysqli, $db);
    addToCompany($mysqli, $db, $userid);
    setSessionUsername();
}

/*
function setSessionUsername(){
    if(isset($_POST['username']) || ($_POST['username'] !== $_SESSION['username'])){
       $_SESSION['username'] = $_POST['username'];
    }       
}


function login($mysqli, $db){

    if(!usernameAndPasswordInDB($mysqli, $db)){
       header("Location: ".getPath()."/login.php?action=logout", true);    
    }

    setSessionUsername();
}
*/
/*
function usernameAndPasswordInDB($mysqli, $db){
    if (!$mysqli || $mysqli->connect_error) {
            echo "<div class='error'>Connection error " .$mysqli->connect_error. " " .$mysqli->connect_error. "</div>";
    }
    
    $username = trim($_POST['username']);
    $hashpass = trim(base64_encode(hash('sha256',$_POST['password'])));
    
    echo "usernameAndPasswordInDB";
    echo "username = $username";
    echo "hashpass = $hashpass";
    
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
*/  

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
    
    if(!($stmt = $mysqli->prepare("INSERT INTO $db.company (userid, companyname) VALUES (?, ?)"))){
        echo "<div class='error'>Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
    }

    if(isset($_POST['companyname']))
    $companyname = $_POST['companyname'];
    //$firstname = $_POST['firstname'];
    //$lastname = $_POST['lastname'];
    //$address = $_POST['address'];

    echo "companyname = $companyname <p>";
    

    if (!$stmt->bind_param("issss", $userid, $nam)) {
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

    if(!($stmt  = $mysqli->prepare("SELECT name FROM $db.company where userid = ?"))){
        echo "<div class='error'>Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
    }
    
    $userid = getUserIDFromUserName($mysqli, $db);
    
    if (!$stmt->bind_param("s", $userid )) {
        echo "<div class='error'>Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    } 
    
     if (!$stmt->execute()) {
        echo "<div class='error'>Execute failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
    } 
    
    
    $name = NULL;

    
    if (!$stmt->bind_result($name)) {
        echo "<div class='error'>Binding results failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }
    
    
    while ($stmt->fetch()) {
        echo "<p> name = $name";
    }

    unset($stmt);

}
if(!$mysqli){
    $mysqli = createDBConnection($dbhost, $dbuser, $dbpass, $dbname);
}
if(isset($_POST['register']))
    {   
    echo "registering user";
    registerUser($mysqli, $db);
    echo $_SESSION['username']."is logged in";
    }
else
if(isset($_POST['login']))
    {   
    echo "loging in user";
    login($mysqli, $db);
    echo $_SESSION['username']."is logged in";
    }
    
/*    
else{
    echo "How did you get here?";
}

if(!isset($_SESSION['username'])){
  echo "session username is not set";
  //setSessionUsername();
} 
*/

?>

<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FACTURA+ Overview</title>
    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    
    <link rel="stylesheet" href="styles/main.css">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700' rel='stylesheet' type='text/css'>
    <script src="scripts/vendor/modernizr.js"></script>
</head>

<body class="bg-blue">
    <div id="wrapper">
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="side-menu" style="display: block;">
                    <li class="nav-header">
                        <p>
                            <?php
    echo $_SESSION['username'];
    echo "userID:".getUserIDFromUserName($mysqli, $db);
//   printCompanyInfo($mysqli, $db);
?>

                            <strong class="font-bold">Andres Antista</strong>
                        </p>
                    </li>
                    <li class="active">
                        <a href="overview.html"><i class="fa fa-inbox"></i> <span class="nav-label">Overview</span></a>
                    </li>
                    <li>
                        <a href="create.html"><i class="fa fa-pencil"></i> <span class="nav-label">Create</span></a>
                    </li>
                    <li>
                        <a href="received.html"><i class="fa fa-dollar"></i> <span class="nav-label">Received</span></a>
                    </li>
                    <li>
                        <a href="settings.html"><i class="fa fa-cogs"></i> <span class="nav-label">Settings</span></a>
                    </li>
                </ul>
            </div>
        </nav>
        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top" role="navigation">
                    <ul class="nav navbar-top-links navbar-right">
                        <li class="">
                            <span class="m-r-sm text-muted welcome-message">Welcome to FACTURA+</span>
                        </li>
                        <li>
                            <a href="<?php echo getPath().'/?action=logout'?>">
                                <i class="fa fa-sign-out"></i> Log out
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-12">
                    <h2>Overview</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="wrapper wrapper-content animated fadeInRight">
                        <div class="ibox-content p-xl">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Last edit</th>
                                            <th>Invoice #</th>
                                            <th>Client</th>
                                            <th>Status</th>
                                            <th>Total</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                            Jun 7th, 2015
                                            </td>
                                            <td>#1001</td>
                                            <td>Dreyfus properties
                                            </td>
                                            <td><span class="label">Draft</span></td>
                                            <td>$1000</td>
                                            <td>
                                                <div class="invoice-actions">
                                                    <a href="#"><i class="fa fa-pencil fa-2x"></i></a><a href="#"><i class="fa fa-trash fa-2x"></i></a>    
                                                </div>
                                                
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                            Jun 7th, 2015
                                            </td>
                                            <td>#1001</td>
                                            <td>Dreyfus properties
                                            </td>
                                            <td><span class="label label-primary">Paid</span></td>
                                            <td>$1000</td>
                                            <td>
                                                <div class="invoice-actions">
                                                    <a href="#"><i class="fa fa-pencil fa-2x"></i></a><a href="#"><i class="fa fa-trash fa-2x"></i></a>    
                                                </div>
                                                
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                            Jun 7th, 2015
                                            </td>
                                            <td>#1001</td>
                                            <td>Dreyfus properties
                                            </td>
                                            <td><span class="label label-danger">Pending</span></td>
                                            <td>$1000</td>
                                            <td>
                                                <div class="invoice-actions">
                                                    <a href="#"><i class="fa fa-pencil fa-2x"></i></a><a href="#"><i class="fa fa-trash fa-2x"></i></a>    
                                                </div>
                                                
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer">
                <div>
                    <strong>OSU CS290</strong> - Rachelle Robinett &copy;2015
                </div>
            </div>
        </div>
    </div>
    <script src="scripts/vendor.js"></script>
    <script src="scripts/plugins.js"></script>
    <script src="scripts/main.js"></script>
</body>

</html>
