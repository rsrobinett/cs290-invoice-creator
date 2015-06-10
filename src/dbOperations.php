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
    
    if(!($stmt = $mysqli->prepare("INSERT INTO $db.invoice (senderid, billtoid, invoicedate, duedate, comment, status, lastupdated) VALUES (?, ?, ?, ?, ?, 0, NOW())"))){
        echo "<div class='error'>Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
    }
    
    if (!$stmt->bind_param("iisss", $senderid, $billtoid, $invoicedate, $duedate, $comment)) {
        echo "<div class='error'>Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }   
    
    if (!$stmt->execute()) {
        echo "<div class='error'>Execute failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }
    
    $userid = $stmt->insert_id;
    
    unset($stmt);
    
    return $userid;
}

function createItem($invoiceid, $description, $amount){
    global $mysqli;
    global $db;
    if (!$mysqli || $mysqli->connect_error) {
            echo "<div class='error'>Connection error " .$mysqli->connect_error. " " .$mysqli->connect_error. "</div>";
    } 
    
    if(!($stmt = $mysqli->prepare("INSERT INTO $db.item (invoiceid, description, amount) VALUES (?, ?, ?)"))){
        echo "<div class='error'>Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
    }
    
    if (!$stmt->bind_param("isd", $invoiceid, $description, $amount)) {
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

function getItemsByInvoiceIDandBillToUsername(){
    //not yet implemented
}
    
function getInvoicesByIDandBillToUsername($id, $username){
    //not tested yet
    global $mysqli;
    global $db; 
    
    $userid = getUserIDbyUsername($username);
    
    if(!($stmt = $mysqli->prepare("SELECT inv.invoiceid
                                    , c.name as billtoname
                                    , c.streetaddress as billingaddress
                                    , '' as billingcity
                                    , c.state as billingstate
                                    , c.zip as billingzip
                                    , s.name as sendername
                                    , s.streetaddress as senderaddress
                                    , '' as sendercity
                                    , s.state as senderstate
                                    , s.zip as senderzip
                                    , DATE_FORMAT(duedate, '%M %D, %Y') as duedate
                                    , DATE_FORMAT(invoicedate, '%M %D, %Y') as invoicedate
                                    , CASE status
                                    WHEN 0 THEN 'Draft'
                                    WHEN 1 THEN 'Paid'
                                    WHEN 2 THEN 'Pending'
                                    END as status
                                    , DATE_FORMAT(lastupdated, '%M %D, %Y') as lastupdated
                                    FROM $db.invoice inv
                                    LEFT JOIN $db.company c on inv.billtoid = c.companyid
                                    LEFT JOIN $db.company s on inv.senderid = s.companyid
                                    WHERE inv.invoiceid = ? and c.userid = ?;
                                   "))){
                                        
        echo "<div class='error'>Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
    }
    
    if (!$stmt->bind_param("ii", $id, $userid)) {
        echo "<div class='error'>Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }

    if (!$stmt->execute()) {
        echo "<div class='error'>Execute failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }   

    $invoiceid = null;
    $billtoname = null;
    
    $billingaddress=null;
    $billingcity=null;
    $billingstate=null;
    $billingzip=null;
    $sendername=null;
    $senderaddress=null;
    $sendercity=null;
    $senderstate=null;
    $ssenderzip=null;
    
    
    $duedate = null;
    $invoicedate = null;
    $status = null;
    $lastupdated = null;
    $total = null;
    
    
    if (!$stmt->bind_result($invoiceid,$billtoname,$billingaddress,$billingcity,$billingstate,$billingzip,$sendername,$senderaddress,$sendercity,$senderstate,$ssenderzip,$duedate,$invoicedate,$status,$lastupdated)) {
        echo "<div class='error'>Binding results failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }    
    
    $invoicearray = array();
    
    while($stmt->fetch()){
        $invoice = array();
        $invoice = ['lastupdated'=>$lastupdated
                    ,'invoiceid'=>$invoiceid
                    ,'billtoname'=>$billtoname
                    ,'billingaddress'=>$billingaddress
                    ,'billingcity'=>$billingcity
                    ,'billingstate'=>$billingstate
                    ,'billingzip'=>$billingzip
                    ,'sendername'=>$sendername
                    ,'senderaddress'=>$senderaddress
                    ,'sendercity'=>$sendercity
                    ,'senderstate'=>$senderstate
                    ,'ssenderzip'=>$ssenderzip
                    ,'duedate'=>$duedate, 
                    'invoicedate'=>$invoicedate
                    ,'status'=>$status];
        $invoicearray[]=$invoice;
        }

    unset($stmt);
    
    return $invoicearray;  
}
    
function getInvoicesByBilltoID($billtoid){
    //not tested yet
    global $mysqli;
    global $db; 
    
    if(!($stmt = $mysqli->prepare("SELECT inv.invoiceid, c.name as sendername
                                    , DATE_FORMAT(duedate, '%M %D, %Y') as duedate
                                    , DATE_FORMAT(invoicedate, '%M %D, %Y') as invoicedate
                                    , CASE status
                                    WHEN 0 THEN 'Draft'
                                    WHEN 1 THEN 'Paid'
                                    WHEN 2 THEN 'Pending'
                                    END as status
                                    , DATE_FORMAT(lastupdated, '%M %D, %Y') as lastupdated
                                    , sum(item.amount) as total
                                    FROM $db.invoice inv
                                    LEFT JOIN $db.company c on inv.senderid = c.companyid
                                    LEFT JOIN $db.item on item.invoiceid = inv.invoiceid 
                                    WHERE billtoid = ?
                                    GROUP BY inv.invoiceid, c.name, duedate, invoicedate, status, lastupdated
                                    ORDER BY lastupdated;"))){
        echo "<div class='error'>Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
    }
    
    if (!$stmt->bind_param("i", $billtoid)) {
        echo "<div class='error'>Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }

    if (!$stmt->execute()) {
        echo "<div class='error'>Execute failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }   

    $invoiceid = null;
    $sendername = null;
    $duedate = null;
    $invoicedate = null;
    $status = null;
    $lastupdated = null;
    $total = null;
    
    
    
    if (!$stmt->bind_result($invoiceid,$sendername,$duedate,$invoicedate,$status,$lastupdated,$total)) {
        echo "<div class='error'>Binding results failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }    
    
    $invoicearray = array();
    
    while($stmt->fetch()){
        $invoice = array();
        $invoice = ['lastupdated'=>$lastupdated,'invoiceid'=>$invoiceid,'sendername'=>$sendername,'duedate'=>$duedate, 'invoicedate'=>$invoicedate,'status'=>$status,'total'=>$total];
        $invoicearray[]=$invoice;
        }

    unset($stmt);
    
    return $invoicearray;  
}

function getInvoicesBySenderID($senderid){
    global $mysqli;
    global $db; 
    
    if(!($stmt = $mysqli->prepare("SELECT inv.invoiceid, c.name as billtoname
                                    , DATE_FORMAT(duedate, '%M %D, %Y') as duedate
                                    , DATE_FORMAT(invoicedate, '%M %D, %Y') as invoicedate
                                    , CASE status
                                    WHEN 0 THEN 'Draft'
                                    WHEN 1 THEN 'Paid'
                                    WHEN 2 THEN 'Pending'
                                    END as status
                                    , DATE_FORMAT(lastupdated, '%M %D, %Y') as lastupdated
                                    , sum(item.amount) as total
                                    FROM $db.invoice inv
                                    LEFT JOIN $db.company c on inv.billtoid = c.companyid
                                    LEFT JOIN $db.item on item.invoiceid = inv.invoiceid 
                                    WHERE senderid = ?
                                    GROUP BY inv.invoiceid, c.name, duedate, invoicedate, status, lastupdated
                                    ORDER BY lastupdated;"))){
        echo "<div class='error'>Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "</div>";
    }
    
    if (!$stmt->bind_param("i", $senderid)) {
        echo "<div class='error'>Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }

    if (!$stmt->execute()) {
        echo "<div class='error'>Execute failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }   

    $invoiceid = null;
    $billtoname = null;
    $duedate = null;
    $invoicedate = null;
    $status = null;
    $lastupdated = null;
    $total = null;
    
    
    
    if (!$stmt->bind_result($invoiceid,$billtoname,$duedate,$invoicedate,$status,$lastupdated,$total)) {
        echo "<div class='error'>Binding results failed: (" . $stmt->errno . ") " . $stmt->error. "</div>";
    }    
    
    $invoicearray = array();
    
    while($stmt->fetch()){
        $invoice = array();
        $invoice = ['lastupdated'=>$lastupdated,'invoiceid'=>$invoiceid,'billtoname'=>$billtoname,'duedate'=>$duedate, 'invoicedate'=>$invoicedate,'status'=>$status,'total'=>$total];
        $invoicearray[]=$invoice;
        }

    unset($stmt);
    
    return $invoicearray;  
}


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



