<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);


$dbConnection = "../src/dbConnection.php";
include($dbConnection);

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
    , name VARCHAR( 255 ) NOT NULL 
    , streetaddress VARCHAR( 255 ) 
    , state VARCHAR( 255 ) 
    , zip INT
    , PRIMARY KEY(companyid)
    , FOREIGN KEY (userid) REFERENCES user(userid)
    , UNIQUE (name))";
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


function createInvoiceTable($mysqli, $db){
    $createtablesql = "CREATE TABLE $db.invoice (
    invoiceid INT NOT NULL AUTO_INCREMENT 
    , senderid INT NOT NULL
    , billtoid INT NOT NULL
    , invoicedate DATETIME NOT NULL
    , duedate DATETIME NOT NULL
    , comment VARCHAR( 255 ) 
    , status INT NOT NULL
    , lastupdated DATETIME NOT NULL
    , PRIMARY KEY (invoiceid)
    , FOREIGN KEY (senderid) REFERENCES company(companyid)
    , FOREIGN KEY (billtoid) REFERENCES company(companyid))";
    if($mysqli->query($createtablesql)){
        echo "Created Company Table";
    } else {
        echo "Create Table Query failed: (" . $mysqli->errno . ") " . $mysqli->error;
        echo "<div>$createtablesql</div>";
    }
}

function dropInvoiceTable($mysqli, $db){
    $droptablesql = "DROP TABLE $db.invoice";
    if($mysqli->query($droptablesql)){
        echo "Dropped Company Table";
    } else {
        echo "Drop Table Query failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
}

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

function displayCreateInvoiceTableMessage($mysqli, $db){
    if(isset($_POST["createInvoiceTable"])){
        echo "creating invoice table";
        createInvoiceTable($mysqli, $db);
    }
}
function displayDropInvoiceTableMessage($mysqli, $db){
    if(isset($_POST["dropInvoiceTable"])){
        echo "dropping invoice table";
        dropInvoiceTable($mysqli, $db);
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
    <input type="submit" value="Create Invoice Table" name="createInvoiceTable">
</form>
<div><?php displayCreateInvoiceTableMessage($mysqli, $db) ?></div>

<form action="createdb.php" method="post">
    <input type="submit" value="Drop Invoice Table" name="dropInvoiceTable">
</form>
<div><?php displayDropInvoiceTableMessage($mysqli, $db) ?></div>
</div>

<form action="createdb.php" method="post">
    <input type="submit" value="Add Test Data" name="inserttestdata">
</form>
<div><?php echo "not implementd"; ?></div>
</body>
</html>