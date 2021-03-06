<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
session_start();

require_once 'functions.php';
require_once 'src/dbOperations.php';

if(!isset($_POST['action'])){
    exit("Invalid request to the server");
}


function AddOrUpdateInvoice(){

    $errormsg = "";
    $invoiceid = null;
    $senderid = null;
    $billtoid  = null;
    $invoicedate = null;
    $duedate = null;
    $comment = null;
    
    if(isset($_SESSION['companyid'])){
        $senderid = $_SESSION['companyid'];
    } else if(isset($_POST['senderid'])){
        $senderid = $_POST['senderid'];
    } else if (isset($_SESSION['username'])){
        $senderid = getCompanyIDbyUsername($_SESSION['username']);
    } else {
        $errormsg = $errormsg."You must be logged in with a ompany to add an invoice.  Check your settings.";
    }
    
    if(isset($_POST['invoiceid'])){
        $invoiceid = $_POST['invoiceid'];
    }
    
    if(isset($_POST['billtoid']) && !empty($_POST['billtoid'])){
        $billtoid = $_POST['billtoid'];
    } else {
        $errormsg = $errormsg."You must select a company to create an invoice. ";
    }
    if(isset($_POST['invoicedate']) && !empty($_POST['invoicedate'])){
        $strinvdate = $_POST['invoicedate'];
        
        $date = date_parse($strinvdate);
        if (!($date["error_count"] == 0 && checkdate($date["month"], $date["day"], $date["year"]))){
            $errormsg = $errormsg."Invalid date";
        }
        else{
            $invoicedate=date("Y-m-d H:i:s", strtotime($strinvdate));
        }
    }else {
        $errormsg = $errormsg."An invoice date is required to create an invoice.  ";
    }
    if(isset($_POST['duedate'])  && !empty($_POST['duedate'])){
        
        $strduedate = $_POST['duedate'];
        
        $date = date_parse($strduedate);
        if (!($date["error_count"] == 0 && checkdate($date["month"], $date["day"], $date["year"]))){
            $errormsg = $errormsg."Invalid date";
        }
        else {
            $duedate=date("Y-m-d H:i:s", strtotime($strduedate));
        }

    } else {
        $errormsg = $errormsg."An due date is required to create an invoice.  ";
    }
    if(isset($_POST['comment'])){
        $comment = $_POST['comment'];
    }
    
    if(empty($errormsg)){
        if(empty($invoiceid)) {
            $invoiceid = createInvoice($senderid, $billtoid, $invoicedate, $duedate, $comment);
            $returnvalue = ['status'=>'newinvoicesaved', 'id'=>"$invoiceid"];
            exit(json_encode($returnvalue)); 
        } else {
            updateInvoice($invoiceid, $senderid, $billtoid, $invoicedate, $duedate, $comment);
        }
        exit("saved");
    }
    
    exit($errormsg);
};


function AddItem(){
    $errormsg = "";
    $invoiceid = null;
    $description = null;
    $amount = null;
    $itemid = null;

    if(isset($_POST['invoiceid'])){
        $invoiceid = $_POST['invoiceid'];
    } else {
        $errormsg = $errormsg."you must associate an invoice number to create an item. ";
    }
    if(isset($_POST['description']) && !empty($_POST['description'])){
        $description = $_POST['description'];
    }else {
        $errormsg = $errormsg."An item must have a description.  ";
    }
    if(isset($_POST['amount']) && !empty($_POST['amount'])){
        $amount = $_POST['amount'];
    } else {
        $errormsg = $errormsg."An item requires an amount.  ";
    }

    if(empty($errormsg)){
        if(isset($_POST['itemid']) && !empty($_POST['itemid'])){
            $itemid = updateItem($_POST['itemid'], $invoiceid, $description, $amount);
            exit("saved");
        } else {
            $itemid = createItem($invoiceid, $description, $amount);
            $returnvalue = ['status'=>'newitemsaved', 'id'=>"$itemid"];
            exit(json_encode($returnvalue)); 
        }
    }
    
    exit($errormsg);    
}

if($_POST['action'] === "createinvoice" && !isset($_GET['invoiceid']) ){
    AddOrUpdateInvoice();
}

if($_POST['action'] === "createitem"){
    AddItem();
}

if($_POST['action'] === "createinvoice" && isset($_GET['invoiceid']) ){
    echo "Update is not implemnted yet";
}

if($_POST['action'] === "deleteitem"){
    $errormsg = null;
    $itemid = null;
    $invoiceid = null;
    if(isset($_POST['invoiceid'])){
        $invoiceid = $_POST['invoiceid'];
    } else {
        $errormsg = $errormsg."There must be an invoice number associated with an item to delete it.  ";
    }
    if(isset($_POST['itemid'])){
        $itemid = $_POST['itemid'];
    } else {
        $errormsg = $errormsg."There must be an item number associated with an item to delete it.  ";
    }
    
    if(empty($errormsg)){
        deleteItem($itemid, $invoiceid);
        $returnvalue = ['status'=>'itemdeleted', 'id'=>"$itemid"];
        exit(json_encode($returnvalue)); 
    }
    
    exit($errormsg);  
}

if($_POST['action'] === 'senditem'){
    $errormsg = null;
    if(isset($_POST['invoiceid'])){
        $invoiceid = $_POST['invoiceid'];
    } else {
        $errormsg = $errormsg."You must send invoiceid to change the status of an item.  ";
    }
        
    if(empty($errormsg)){
        $requestedstatus = 1;
        $newstatus = setInvoiceStatus($invoiceid, $requestedstatus);   

        if($newstatus != $requestedstatus){
            $errormsg = $errormsg = $errormsg."Invalid Status Change Request.  You can not change a status from Paid to Pending.  " ;
        }else{
        
        $returnvalue = ['status'=>'statussaved', 'id'=>$invoiceid ,'newstatus'=>'Pending'];
        exit(json_encode($returnvalue)); 
        }
        
    }
    
    exit($errormsg); 
}

if($_POST['action'] === 'payitem'){
    $errormsg = null;
    if(isset($_POST['invoiceid'])){
        $invoiceid = $_POST['invoiceid'];
    } else {
        $errormsg = $errormsg."You must send invoiceid to change the status of an item.  ";
    }
    
    if(empty($errormsg)){
        setInvoiceStatus($invoiceid, 2);   
        $returnvalue = ['status'=>'statussaved', 'id'=>$invoiceid ,'newstatus'=>"Paid"];
        exit(json_encode($returnvalue)); 
        
    }
    
    exit($errormsg); 
}



exit("Invalid request to the server");    
?>