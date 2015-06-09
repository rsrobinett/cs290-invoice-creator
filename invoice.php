<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
session_start();

require_once 'functions.php';
require_once 'src/dbOperations.php';

if(!isset($_POST['action'])){
    exit("Invalid request to the server");
}

if($_POST['action'] === "createinvoice"){

    $errormsg = "";

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
        $errormsg = $errormsg."A Sender Company Id is needed to add an invoice";
    }
    
    if(isset($_POST['billtoid'])){
        $billtoid = $_POST['billtoid'];
    } else {
        $errormsg = $errormsg."you must select a company to send your invoice to. ";
    }
    if(isset($_POST['invoicedate'])){
        $invoicedate = $_POST['invoicedate'];
    }else {
        $errormsg = $errormsg."An invoice date is required to create an invoice.  ";
    }
    if(isset($_POST['duedate'])){
        $duedate = $_POST['duedate'];
    } else {
        $errormsg = $errormsg."An due date is required to create an invoice.  ";
    }
    if(isset($_POST['comment'])){
        $comment = $_POST['comment'];
    }
    
    if(empty($errormsg)){
    createInvoice($senderid, $billtoid, $invoicedate, $duedate, $comment);
    exit("success"); 
    }
    
    exit($errormsg);
}

if($_POST['action'] === "createitem"){

    exit("success");    
}

exit("Invalid request to the server");    
?>