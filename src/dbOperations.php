<?php
require_once 'functions.php';
require_once 'dbCredentials.php';
require_once 'dbConnection.php';    
/*
global $mysqli;
if (!$mysqli) {
    $mysqli = createDBConnection($dbhost, $dbuser, $dbpass, $dbname);
    if (!$mysqli || $mysqli->connect_error) {
        echo "Connection error " .$mysqli->connect_error. " " .$mysqli->connect_error. "";
    }
}
*/

    //Create
function createUser($username, $password){
    global $mysqli;
    global $db;
    if (!$mysqli || $mysqli->connect_error) {
            echo "Connection error " .$mysqli->connect_error. " " .$mysqli->connect_error. "";
    } 
    
    if(!($stmt = $mysqli->prepare("INSERT INTO $db.user (username, hashpass) VALUES (?, ?)"))){
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "";
    }
    
    $hashpass = trim(base64_encode(hash('sha256',$password)));

    if (!$stmt->bind_param("ss", $username, $hashpass )) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }   
    
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }
    
    $userid = $stmt->insert_id;
    
    unset($stmt);
    
    return $userid;
}

function createInvoice($senderid, $billtoid, $invoicedate, $duedate, $comment){
    global $mysqli;
    global $db;
    if (!$mysqli || $mysqli->connect_error) {
            echo "Connection error " .$mysqli->connect_error. " " .$mysqli->connect_error. "";
    } 
    
    if(!($stmt = $mysqli->prepare("INSERT INTO $db.invoice (senderid, billtoid, invoicedate, duedate, comment, status, lastupdated) VALUES (?, ?, ?, ?, ?, 0, NOW())"))){
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "";
    }
    
    if (!$stmt->bind_param("iisss", $senderid, $billtoid, $invoicedate, $duedate, $comment)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }   
    
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }
    
    $userid = $stmt->insert_id;
    
    unset($stmt);
    
    return $userid;
}

function createItem($invoiceid, $description, $amount){
    global $mysqli;
    global $db;
    if (!$mysqli || $mysqli->connect_error) {
            echo "Connection error " .$mysqli->connect_error. " " .$mysqli->connect_error. "";
    } 
    
    if(!($stmt = $mysqli->prepare("INSERT INTO $db.item (invoiceid, description, amount) VALUES (?, ?, ?)"))){
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "";
    }
    
    if (!$stmt->bind_param("isd", $invoiceid, $description, $amount)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }   
    
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }
    
    $userid = $stmt->insert_id;
    
    unset($stmt);
    
    return $userid;
}


function createCompany($companyname, $userid, $streetaddress, $city, $state, $zip){
    global $mysqli;
    global $db;
    
    if (!$mysqli || $mysqli->connect_error) {
            echo "Connection error " .$mysqli->connect_error. " " .$mysqli->connect_error. "";
    } 
    
    if(!($stmt = $mysqli->prepare("INSERT INTO $db.company (userid, name, streetaddress, city, state, zip) VALUES (? , ?, ? , ? , ? , ?)"))){
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "";
    }

    if (!$stmt->bind_param("issssi", $userid, $companyname, $streetaddress, $city, $state, $zip)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }   
    
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }
    
    $companyid = $stmt->insert_id;
    
    unset($stmt);
    
    return $companyid;
}

    //Read
    
function getInvoiceStatus($invoiceid){
    global $mysqli;
    global $db;
    if (!$mysqli || $mysqli->connect_error) {
            echo "Connection error " .$mysqli->connect_error. " " .$mysqli->connect_error. "";
    } 
    
    if(!($stmt = $mysqli->prepare("Select status FROM $db.invoice WHERE invoiceid = ?"))){
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "";
    }
    
    if (!$stmt->bind_param("i", $invoiceid)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }   
    
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }
    $status = null;
    if(!$stmt->bind_result($status)){
        echo "Binding results failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }
    
    $returnstatus = null;
    while($stmt->fetch()){
        $returnstatus = $status;
    }

    
    unset($stmt);
    
    return $returnstatus;    
}

function getInvoicesByIDandSenderUsername($id, $username){
    global $mysqli;
    global $db; 
    
    $userid = getUserIDbyUsername($username);
    
    if(!($stmt = $mysqli->prepare("SELECT inv.invoiceid
                                    , c.name as billtoname
                                    , c.companyid
                                    , c.streetaddress as billingaddress
                                    , c.city as billingcity
                                    , c.state as billingstate
                                    , c.zip as billingzip
                                    , s.name as sendername
                                    , s.companyid
                                    , s.streetaddress as senderaddress
                                    , s.city as sendercity
                                    , s.state as senderstate
                                    , s.zip as senderzip
                                    , DATE_FORMAT(duedate, '%M %D, %Y') as duedate
                                    , DATE_FORMAT(invoicedate, '%M %D, %Y') as invoicedate
                                    , CASE status
                                    WHEN 0 THEN 'Draft'
                                    WHEN 1 THEN 'Pending'
                                    WHEN 2 THEN 'Paid'
                                    END as status
                                    , DATE_FORMAT(lastupdated, '%M %D, %Y') as lastupdated
                                    , comment
                                    FROM $db.invoice inv
                                    LEFT JOIN $db.company c on inv.billtoid = c.companyid
                                    LEFT JOIN $db.company s on inv.senderid = s.companyid
                                    WHERE inv.invoiceid = ? and s.userid = ?;
                                   "))){
                                        
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "";
    }
    
    if (!$stmt->bind_param("ii", $id, $userid)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }

    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }   

    $invoiceid = null;
    
    $billtoname = null;
    $billtoid = null;
    $billingaddress=null;
    $billingcity=null;
    $billingstate=null;
    $billingzip=null;
    
    $sendername=null;
    $senderid=null;
    $senderaddress=null;
    $sendercity=null;
    $senderstate=null;
    $ssenderzip=null;

    $duedate = null;
    $invoicedate = null;
    $status = null;
    $lastupdated = null;
    $comment = null;
    
    
    if (!$stmt->bind_result($invoiceid,$billtoname,$billtoid,$billingaddress,$billingcity,$billingstate,$billingzip,$sendername,$senderid,$senderaddress,$sendercity,$senderstate,$ssenderzip,$duedate,$invoicedate,$status,$lastupdated,$comment)) {
        echo "Binding results failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }    
    
    $invoicearray = array();
    
    while($stmt->fetch()){
       // $invoice = array();
        $invoice = ['lastupdated'=>$lastupdated
                    ,'invoiceid'=>$invoiceid
                    , 'billto'=>[
                                'id'=>$billtoid
                                ,'name'=>$billtoname
                                ,'address'=>$billingaddress
                                ,'city'=>$billingcity
                                ,'state'=>$billingstate
                                ,'zip'=>$billingzip
                                ]
                    , 'sender'=>[
                                 'id'=>$senderid
                                , 'name'=>$sendername
                                ,'address'=>$senderaddress
                                ,'city'=>$sendercity
                                ,'state'=>$senderstate
                                ,'zip'=>$ssenderzip
                                ]
                    , 'duedate'=>$duedate
                    , 'invoicedate'=>$invoicedate
                    , 'status'=>$status
                    , 'lastupdated'=>$lastupdated
                    , 'comment'=>$comment
                    ];
        $invoicearray[]=$invoice;
        }

    unset($stmt);
    
    if(count($invoicearray)>0)
    {
        return $invoice;  
    }
    return array();
}

function getInvoiceTotalByInvoiceIDandSenderUsername($id, $username){
    global $mysqli;
    global $db; 
    
    $userid = getUserIDbyUsername($username);
    
    if(!($stmt = $mysqli->prepare("SELECT sum(amount) as total
                                    FROM $db.item AS it
                                    LEFT JOIN $db.invoice AS inv ON it.invoiceid = inv.invoiceid
                                    LEFT JOIN $db.company c ON inv.senderid = c.companyid
                                    WHERE it.invoiceid = ? and c.userid = ?
                                "))){
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "";
    }
    
    if (!$stmt->bind_param("ii", $id, $userid)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }

    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }
    $total = null;
    if (!$stmt->bind_result( $total)) {
        echo "Binding results failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }
    
     while($stmt->fetch()){}
     
     unset($stmt);
     
     return $total;
    
}


function getInvoiceTotalByInvoiceIDandBilltoUsername($id, $username){
    global $mysqli;
    global $db; 
    
    $userid = getUserIDbyUsername($username);
    
    if(!($stmt = $mysqli->prepare("SELECT sum(amount) as total
                                    FROM $db.item AS it
                                    LEFT JOIN $db.invoice AS inv ON it.invoiceid = inv.invoiceid
                                    LEFT JOIN $db.company c ON inv.billtoid = c.companyid
                                    WHERE it.invoiceid = ? and c.userid = ?
                                "))){
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "";
    }
    
    if (!$stmt->bind_param("ii", $id, $userid)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }

    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }
    $total = null;
    if (!$stmt->bind_result( $total)) {
        echo "Binding results failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }
    
     while($stmt->fetch()){}
     
     unset($stmt);
     
     return $total;
    
}

function getItemsByInvoiceIDandSenderUsername($id, $username){
    global $mysqli;
    global $db; 
    
    $userid = getUserIDbyUsername($username);

    if(!($stmt = $mysqli->prepare("SELECT itemid
                                        , it.invoiceid
                                        , description
                                        , amount
                                    FROM $db.item AS it
                                    LEFT JOIN $db.invoice AS inv ON it.invoiceid = inv.invoiceid
                                    LEFT JOIN $db.company c ON inv.senderid = c.companyid
                                    WHERE it.invoiceid = ? and c.userid = ?
                                "))){
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "";
    }
    
    if (!$stmt->bind_param("ii", $id, $userid)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }

    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }   
    
    $itemid = null;
    $invoiceid = null;
    $description = null;
    $amount = null;
    
    if (!$stmt->bind_result($itemid, $invoiceid, $description, $amount)) {
        echo "Binding results failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }
    $itemarr = array();
    while($stmt->fetch()){
        $item = [
                'itemid'=>$itemid
                ,'invoiceid'=>$invoiceid
                ,'description'=>$description
                ,'amount'=>$amount
            ];
        $itemarr[] = $item;
    }
    
    unset($stmt);
    
    if(count($itemarr)>0)
    {
        return $itemarr;  
    }
    return array();
}

function getItemsByInvoiceIDandBillToUsername($id, $username){
    global $mysqli;
    global $db; 
    
    $userid = getUserIDbyUsername($username);

    if(!($stmt = $mysqli->prepare("SELECT itemid
                                        , it.invoiceid
                                        , description
                                        , amount
                                    FROM $db.item AS it
                                    LEFT JOIN $db.invoice AS inv ON it.invoiceid = inv.invoiceid
                                    LEFT JOIN $db.company c ON inv.billtoid = c.companyid
                                    WHERE it.invoiceid = ? and c.userid = ?
                                "))){
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "";
    }
    
    if (!$stmt->bind_param("ii", $id, $userid)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }

    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }   
    
    $itemid = null;
    $invoiceid = null;
    $description = null;
    $amount = null;
    
    if (!$stmt->bind_result($itemid, $invoiceid, $description, $amount)) {
        echo "Binding results failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }
    $itemarr = array();
    while($stmt->fetch()){
        $item = [
                'itemid'=>$itemid
                ,'invoiceid'=>$invoiceid
                ,'description'=>$description
                ,'amount'=>$amount
            ];
        $itemarr[] = $item;
    }
    
    unset($stmt);
    
    if(count($itemarr)>0)
    {
        return $itemarr;  
    }
    return array();
}
    
function getInvoicesByIDandBillToUsername($id, $username){
    global $mysqli;
    global $db; 
    
    $userid = getUserIDbyUsername($username);
    
    if(!($stmt = $mysqli->prepare("SELECT inv.invoiceid
                                    , c.name as billtoname
                                    , c.streetaddress as billingaddress
                                    , c.city as billingcity
                                    , c.state as billingstate
                                    , c.zip as billingzip
                                    , s.name as sendername
                                    , s.streetaddress as senderaddress
                                    , s.city as sendercity
                                    , s.state as senderstate
                                    , s.zip as senderzip
                                    , DATE_FORMAT(duedate, '%M %D, %Y') as duedate
                                    , DATE_FORMAT(invoicedate, '%M %D, %Y') as invoicedate
                                    , CASE status
                                    WHEN 0 THEN 'Draft'
                                    WHEN 1 THEN 'Pending'
                                    WHEN 2 THEN 'Paid'
                                    END as status
                                    , DATE_FORMAT(lastupdated, '%M %D, %Y') as lastupdated
                                    , comment
                                    FROM $db.invoice inv
                                    LEFT JOIN $db.company c on inv.billtoid = c.companyid
                                    LEFT JOIN $db.company s on inv.senderid = s.companyid
                                    WHERE status > 0 AND inv.invoiceid = ? AND c.userid = ?;
                                   "))){
                                        
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "";
    }
    
    if (!$stmt->bind_param("ii", $id, $userid)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }

    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error. "";
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
    $comment = null;
    
    
    if (!$stmt->bind_result($invoiceid,$billtoname,$billingaddress,$billingcity,$billingstate,$billingzip,$sendername,$senderaddress,$sendercity,$senderstate,$ssenderzip,$duedate,$invoicedate,$status,$lastupdated,$comment)) {
        echo "Binding results failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }    
    
    $invoicearray = array();
    
    while($stmt->fetch()){
       // $invoice = array();
        $invoice = ['lastupdated'=>$lastupdated
                    ,'invoiceid'=>$invoiceid
                    , 'billto'=>[
                                'name'=>$billtoname
                                ,'address'=>$billingaddress
                                ,'city'=>$billingcity
                                ,'state'=>$billingstate
                                ,'zip'=>$billingzip
                                ]
                    , 'sender'=>[
                                 'name'=>$sendername
                                ,'address'=>$senderaddress
                                ,'city'=>$sendercity
                                ,'state'=>$senderstate
                                ,'zip'=>$ssenderzip
                                ]
                    , 'duedate'=>$duedate
                    , 'invoicedate'=>$invoicedate
                    , 'status'=>$status
                    , 'lastupdated'=>$lastupdated
                    , 'comment'=>$comment
                    ];
        $invoicearray[]=$invoice;
        }

    unset($stmt);
    
    if(count($invoicearray)>0)
    {
        return $invoicearray;  
    }
    return array();
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
                                    WHEN 1 THEN 'Pending'
                                    WHEN 2 THEN 'Paid'
                                    END as status
                                    , DATE_FORMAT(lastupdated, '%M %D, %Y') as lastupdated
                                    , sum(item.amount) as total
                                    FROM $db.invoice inv
                                    LEFT JOIN $db.company c on inv.senderid = c.companyid
                                    LEFT JOIN $db.item on item.invoiceid = inv.invoiceid 
                                    WHERE status > 0 AND billtoid = ?
                                    GROUP BY inv.invoiceid, c.name, duedate, invoicedate, status, lastupdated
                                    ORDER BY lastupdated;"))){
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "";
    }
    
    if (!$stmt->bind_param("i", $billtoid)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }

    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }   

    $invoiceid = null;
    $sendername = null;
    $duedate = null;
    $invoicedate = null;
    $status = null;
    $lastupdated = null;
    $total = null;
    
    
    
    if (!$stmt->bind_result($invoiceid,$sendername,$duedate,$invoicedate,$status,$lastupdated,$total)) {
        echo "Binding results failed: (" . $stmt->errno . ") " . $stmt->error. "";
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
                                    WHEN 1 THEN 'Pending'
                                    WHEN 2 THEN 'Paid'
                                    END as status
                                    , DATE_FORMAT(lastupdated, '%M %D, %Y') as lastupdated
                                    , sum(item.amount) as total
                                    FROM $db.invoice inv
                                    LEFT JOIN $db.company c on inv.billtoid = c.companyid
                                    LEFT JOIN $db.item on item.invoiceid = inv.invoiceid 
                                    WHERE senderid = ?
                                    GROUP BY inv.invoiceid, c.name, duedate, invoicedate, status, lastupdated
                                    ORDER BY lastupdated;"))){
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "";
    }
    
    if (!$stmt->bind_param("i", $senderid)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }

    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }   

    $invoiceid = null;
    $billtoname = null;
    $duedate = null;
    $invoicedate = null;
    $status = null;
    $lastupdated = null;
    $total = null;
    
    
    
    if (!$stmt->bind_result($invoiceid,$billtoname,$duedate,$invoicedate,$status,$lastupdated,$total)) {
        echo "Binding results failed: (" . $stmt->errno . ") " . $stmt->error. "";
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
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "";
    }

    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }   

    $companyname = null;
    $companyid = null;
    
    if (!$stmt->bind_result($companyid, $companyname )) {
        echo "Binding results failed: (" . $stmt->errno . ") " . $stmt->error. "";
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
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "";
    }

    if (!$stmt->bind_param("s", $username)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }

    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }   

    $companyname= null;
    
    if (!$stmt->bind_result($companyname )) {
        echo "Binding results failed: (" . $stmt->errno . ") " . $stmt->error. "";
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
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "";
    }

    if (!$stmt->bind_param("s", $username)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }

    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }   

    $companyid= null;
    
    if (!$stmt->bind_result($companyid )) {
        echo "Binding results failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }    
    
    while($stmt->fetch()){
        //there should only be one so just loop through it.
        }

    unset($stmt);
    
    return $companyid;      
    
}

function getCompanySettingsbyUsername($username){
    global $mysqli;
    global $db;
    
    if(!($stmt = $mysqli->prepare("SELECT companyid, name, streetaddress, city, state, zip  
                                   FROM $db.company 
                                   LEFT JOIN $db.user 
                                   ON $db.company.userid = $db.user.userid
                                   WHERE username = ?"))){
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "";
    }

    if (!$stmt->bind_param("s", $username)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }

    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }   

    $companyid = null;
    $name = null;
    $streetaddress = null;
    $city = null;
    $state = null;
    $zip = null;
    
    if (!$stmt->bind_result($companyid, $name, $streetaddress, $city, $state, $zip )) {
        echo "Binding results failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }    

    while($stmt->fetch()){
        $company = [
            'companyid' => $companyid,
            'name' => $name,
            'streetaddress' => $streetaddress,
            'city' => $city,
            'state' => $state,
            'zip' => $zip
            ];
        }

    unset($stmt);
    
    return $company;      
    
}
  

function getUserIDbyUsername($username){
    //not tested yet
    global $mysqli;
    global $db;

    if(!($stmt  = $mysqli->prepare("SELECT userid FROM $db.user where username = ?"))){
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "";
    }

    if (!$stmt->bind_param("s", $username )) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "";
    } 
    
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error. "";
    } 
    
    $userid = NULL;
    
    if (!$stmt->bind_result($userid )) {
        echo "Binding results failed: (" . $stmt->errno . ") " . $stmt->error. "";
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
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "";
    }

    if (!$stmt->bind_param("s", $username)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }

    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error. "";
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
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "";
    }

      if (!$stmt->bind_param("s", $companyname)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }

    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error. "";
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
    
function updateCompany($companyid, $companyname,  $streetaddress, $city, $state, $zip){
    global $mysqli;
    global $db;
    
    if (!$mysqli || $mysqli->connect_error) {
            exit("Connection error " .$mysqli->connect_error. " " .$mysqli->connect_error. "");
    } 
    if(!($stmt = $mysqli->prepare("UPDATE $db.company SET 
                                    name = ?
                                    , streetaddress = ?
                                    , city = ?
                                    , state = ?
                                    , zip = ?
                                    WHERE companyid = ?
                                    "))){
        exit("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "");
    }
    
    if (!$stmt->bind_param("sssssi", $companyname, $streetaddress, $city, $state, $zip, $companyid)) {
        exit("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "");
    } 
    
    if (!$stmt->execute()) {
        exit("Execute failed: (" . $stmt->errno . ") " . $stmt->error. "");
    }
    
    $affectedrows = $stmt->affected_rows;
    
    unset($stmt);
    
    return $affectedrows;
}
    
function setInvoiceStatus($invoiceid, $requestedstatus){
    global $mysqli;
    global $db;
    
    $currentstatus = getInvoiceStatus($invoiceid);

    if(+$requestedstatus <= +$currentstatus){
        return $currentstatus;
    }
    
    if (!$mysqli || $mysqli->connect_error) {
            echo "Connection error " .$mysqli->connect_error. " " .$mysqli->connect_error. "";
    } 
    
    if(!($stmt = $mysqli->prepare("UPDATE $db.invoice SET status = ? WHERE invoiceid = ?"))){
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "";
    }
    
    if (!$stmt->bind_param("ii", $requestedstatus, $invoiceid)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }   
    
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }

    if($stmt->affected_rows > 0 ){
        
        
    unset($stmt);
    
    return $requestedstatus;    
    }
    return $currentstatus;
}

function updateItem($itemid, $invoiceid, $description, $amount){
    global $mysqli;
    global $db;
    if (!$mysqli || $mysqli->connect_error) {
            echo "Connection error " .$mysqli->connect_error. " " .$mysqli->connect_error. "";
    } 
    
    if(!($stmt = $mysqli->prepare("UPDATE $db.item SET invoiceid = ?, description = ?, amount = ? WHERE itemid = ?"))){
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "";
    }
    
    if (!$stmt->bind_param("isdi", $invoiceid, $description, $amount, $itemid)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }   
    
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }
    
    $userid = $stmt->insert_id;
    
    unset($stmt);
    
    return $userid;
}

   
function updateInvoice($invoiceid, $senderid, $billtoid, $invoicedate, $duedate, $comment){
    global $mysqli;
    global $db;
    
    //check if invoice exists.  
    
    if (!$mysqli || $mysqli->connect_error) {
            echo "Connection error " .$mysqli->connect_error. " " .$mysqli->connect_error. "";
    } 
    
    if(!($stmt = $mysqli->prepare("UPDATE $db.invoice 
                                    SET senderid = ?
                                    , billtoid = ?
                                    , invoicedate = ?
                                    , duedate = ?
                                    , comment = ?
                                    , status = 0 
                                    , lastupdated = NOW()
                                    WHERE invoiceid = ?
                                    "))){
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "";
    }
    
    if (!$stmt->bind_param("iisssi", $senderid, $billtoid, $invoicedate, $duedate, $comment, $invoiceid)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }   
    
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }
    
    $invoiceid = $stmt->insert_id;
    
    unset($stmt);
    
    return $invoiceid;
}
    

    //Delete
function deleteItem($itemid, $invoiceid){
    global $mysqli;
    global $db;
    if (!$mysqli || $mysqli->connect_error) {
            echo "Connection error " .$mysqli->connect_error. " " .$mysqli->connect_error. "";
    } 
    
    if(!($stmt = $mysqli->prepare("DELETE FROM $db.item WHERE itemid = ? AND invoiceid = ?"))){
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    
    if (!$stmt->bind_param("ii", $itemid,$invoiceid)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }   
    
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    
    $userid = $stmt->insert_id;
    
    unset($stmt);
    
    return $userid;
}

//other db operations
function usernameAndPasswordInDB($username, $password, $mysqli, $db) {
    
    $username = trim($_POST['username']);
    $hashpass = trim(base64_encode(hash('sha256',$_POST['password'])));

    if(!($stmt = $mysqli->prepare("SELECT username FROM $db.user WHERE username = ? AND hashpass = ?"))){
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "";
    }

    if (!$stmt->bind_param("ss", $username, $hashpass )) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error. "";
    }

    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error. "";
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



