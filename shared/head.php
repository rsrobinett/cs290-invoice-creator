<?php 

error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();
require_once 'functions.php';
require_once 'src/dbOperations.php';

if(!isset($_SESSION['username'])){
    redirect('index.php');
}

;?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FACTURA+ <?php echo $pagetitle;?> </title>
    <link rel="stylesheet" href="styles/vendor.css">
    <link rel="stylesheet" href="styles/main.css">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700' rel='stylesheet' type='text/css'>
</head>

<body class="bg-blue">
    <div id="wrapper">