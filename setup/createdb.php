<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);


$dbConnection = "../src/dbConnection.php";
include($dbConnection);

/*
$dbCredentials = "../src/dbCredentials.php";
include($dbCredentials);
global $mysqli;

function createDBConnection($dbhost, $dbuser, $dbpass, $dbname){
    $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
    return $mysqli;
    // Check connection
    if (!$mysqli || $mysqli->connect_error) {
        echo "Connection error " .$mysqli->connect_error. " " .$mysqli->connect_error;
    } 
}
*/

function displayDBConnectionInfo($mysqli, $dbhost, $dbname){
     if (!$mysqli || $mysqli->connect_error) {
        echo "<div> Connected to host: $dbhost </div>";
        echo "<div> Connected to database: $dbname </div>";  
     }
}
function createUserTable($mysqli, $db){
    $createtablesql = "CREATE TABLE $db.user (
    userid INT NOT NULL AUTO_INCREMENT 
    , username VARCHAR( 255 ) NOT NULL 
    , hashpass VARCHAR( 255 ) NOT NULL
    , PRIMARY KEY(userid)
    , UNIQUE (username))";
    if($mysqli->query($createtablesql)){
        echo "Created User Table";
    } else {
        echo "Create Table Query failed: (" . $mysqli->errno . ") " . $mysqli->error;
        echo "<div>$createtablesql</div>";
    }
}

function dropUserTable($mysqli, $db){
    $droptablesql = "DROP TABLE $db.user";
    if($mysqli->query($droptablesql)){
        echo "Dropped Table";
    } else {
        echo "Drop Table Query failed: (" . $mysqli->errno . ") " . $mysqli->error;
        echo "<div>$droptablesql</div>";
    }
}

function createCompanyTable($mysqli, $db){
    $createtablesql = "CREATE TABLE $db.company (
    companyid INT NOT NULL AUTO_INCREMENT 
    , userid INT
    , companyname VARCHAR( 255 ) NOT NULL 
    , firstname VARCHAR( 255 ) NOT NULL
    , lastname VARCHAR( 255 ) NOT NULL
    , address VARCHAR( 255 ) NOT NULL
    , PRIMARY KEY(companyid)
    , FOREIGN KEY (userid) REFERENCES user(userid)
    , UNIQUE (companyname))";
    if($mysqli->query($createtablesql)){
        echo "Created Company Table";
    } else {
        echo "Create Table Query failed: (" . $mysqli->errno . ") " . $mysqli->error;
        echo "<div>$createtablesql</div>";
    }
}

function dropCompanyTable($mysqli, $db){
    $droptablesql = "DROP TABLE $db.company";
    if($mysqli->query($droptablesql)){
        echo "Dropped Company Table";
    } else {
        echo "Drop Table Query failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
}


/*
function createtable($mysqli, $db){
    $createtablesql = "CREATE TABLE $db.user (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY 
    , name VARCHAR( 255 ) NOT NULL 
    , category VARCHAR( 255 ) NULL 
    , length INT NULL
    , rented BOOL NOT NULL DEFAULT 0 
    , UNIQUE (name)
    , CHECK (length > 0 OR ISNULL(length)))";
    if($mysqli->query($createtablesql)){
        echo "Created Table";
    } else {
        echo "Create Table Query failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
}
*/

$mysqli = createDBConnection($dbhost, $dbuser, $dbpass, $dbname);

function displayCreateUserTableMessage($mysqli, $db){
    if(isset($_POST["createUserTable"])){
        echo "creating user table";
        createUserTable($mysqli, $db);
    }
}
function displayDropUserTableMessage($mysqli, $db){
    if(isset($_POST["dropUserTable"])){
        echo "dropping user table";
        dropUserTable($mysqli, $db);
    }
}

function displayCreateCompanyTableMessage($mysqli, $db){
    if(isset($_POST["createCompanyTable"])){
        echo "creating company table";
        createCompanyTable($mysqli, $db);
    }
}
function displayDropCompanyTableMessage($mysqli, $db){
    if(isset($_POST["dropCompanyTable"])){
        echo "dropping company table";
        dropCompanyTable($mysqli, $db);
    }
}



function instertTestData($mysqli, $db){
    $sql = "
    INSERT INTO $db.inventory (name, category, length) VALUES
    ('Avengers: Age of Ultron', 'Action', 141),
    ('Furious Seven', 'Action', 137),
    ('Mad Max: Fury Road', 'Action', 120),
    ('The Avengers', 'Action', 143),
    ('Jupiter Ascending', 'Action', 127),
    ('Kingsman: The Secret Service', 'Comedy', 129),
    ('Mortdecai', 'Comedy', 107),
    ('Paul Blart: Mall Cop 2', 'Comedy', 94),
    ('The Wedding Ringer', 'Comedy', 101),
    ('Home', 'Comedy', 94),
    ('The Age of Adaline', 'Drama', 112),
    ('Ex Machina', 'Drama', 108),
    ('The Water Diviner', 'Drama', 111),
    ('Fifty Shades of Gray', 'Drama', 125),
    ('The Longest Ride', 'Drama', 139),
    ('ztest', null , null);
    ";
    if (!$mysqli || $mysqli->connect_error) {
        echo "Connection error " .$mysqli->connect_error. " " .$mysqli->connect_error;
    } 
   
   if($mysqli->query($sql)){
        echo "Inserted Test Data";
    } else {
        echo "Query failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
}
function displayInstertTestDataMessage($mysqli, $dbname){
    if(isset($_POST["inserttestdata"])){
        echo "inserting test data ";
        instertTestData($mysqli, $dbname);
    }
}  
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Setup</title>
</head>
<body>
<div>
<?php displayDBConnectionInfo($mysqli, $dbhost, $dbname) ?>
</div>
<div>
<form action="createdb.php" method="post">
    <input type="submit" value="Create User Table" name="createUserTable">
</form>
<div><?php displayCreateUserTableMessage($mysqli, $db) ?></div>

<form action="createdb.php" method="post">
    <input type="submit" value="Drop User Table" name="dropUserTable">
</form>
<div><?php displayDropUserTableMessage($mysqli, $db) ?></div>
</div>



<form action="createdb.php" method="post">
    <input type="submit" value="Create Company Table" name="createCompanyTable">
</form>
<div><?php displayCreateCompanyTableMessage($mysqli, $db) ?></div>

<form action="createdb.php" method="post">
    <input type="submit" value="Drop Company Table" name="dropCompanyTable">
</form>
<div><?php displayDropCompanyTableMessage($mysqli, $db) ?></div>
</div>



<form action="createdb.php" method="post">
    <input type="submit" value="Add Test Data" name="inserttestdata">
</form>
<div><?php displayInstertTestDataMessage($mysqli, $db) ?></div>
</body>
</html>