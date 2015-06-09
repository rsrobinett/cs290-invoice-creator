<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once 'functions.php';
require_once 'src/dbOperations.php';

session_start();

if(!isset($_SESSION['username'])){
    echo "you aren't logged in";
    redirect('index.php');
}

function companyOptions(){
    
    $companyArray = getCompanyNames();
    
    foreach($companyArray as $id=>$name){
        echo "<option value='$id'>'$name'</option>";
    }
}

function createInvoiceTable(){
    $invoicearray = readInvoicesBySenderID(getCompanyIDbyUsername($username)); 
    echo "<table>";
    foreach($invoicearray as $index => $invoice){
        echo "<tr>";
        echo "<td> $invoice[lastupdated] </td>";
        echo "<td> $invoice[invoiceid] </td>";
        echo "<td> $invoice[billtoname] </td>";
        echo "<td> <span class='label'> $invoice[status] </span> </td>";
        echo "<td> $invoice[total] </td>";
        echo '<td>
            <div class="invoice-actions">
                <a href="#"><i class="fa fa-pencil fa-2x"></i></a><a href="#"><i class="fa fa-trash fa-2x"></i></a>    
            </div></td></tr>';
    }
    echo "</table>";
}

createInvoiceTable();

?>



<!doctype html>
<html class="no-js" lang="">

<head>
</head>

<body>
    <div id="errortext"></div>
        <form class="m-t" role="form" id="form" action="invoice.php" method="post" autocomplete="off" onsubmit="ajaxCall(this, 'createinvoice'); return false;">
            <div class="form-group">
            <select name="billtoid"  required>
                  <option value="other" selected>select a company</option>
                  <?php companyOptions(); ?>
                </select>
            </div>
            <div class="form-group">
                <!--should this just be auto generated date?--> 
                <input type="date" class="form-control" name="invoicedate" placeholder="Invoice Date" id="invoicedate" required>
            </div>
            <div class="form-group">
                <input type="date" class="form-control" name="duedate" placeholder="Due Date" id="duedate" required>
            </div>
            <div class="form-group">
                <textarea class="form-control" name="comment" placeholder="Comment" id="comment"></textarea>
            </div>
            <input type="submit" class="btn btn-primary block btn-block m-b" value="Save and Exit">
        </form>
    <script src="app.js"></script> 
</body>
</html>