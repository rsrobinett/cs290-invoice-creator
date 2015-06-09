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

?>



<!doctype html>
<html class="no-js" lang="">

<head>
</head>

<body>
    <div id="errortext"></div>
        <form class="m-t" role="form" id="form2" action="invoice.php" method="post" autocomplete="off" onsubmit="ajaxCall(this, 'createinvoice'); return false;">
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
        
        
        <form class="m-t" role="form" id="form" action="invoice.php" method="post" autocomplete="off" onsubmit="ajaxCall(this, 'createitem'); return false;">
            <div class="form-group">
                <input type="number" class="form-control" name="invoiceid" placeholder="Invoice Number" id="invoiceid" required>
            </div>
            <div class="form-group">
                <textarea class="form-control" name="description" placeholder="Description" id="description" required></textarea>
            </div>
            <div class="form-group">
                <input type="number" class="form-control" name="amount" placeholder="amount" id="amount" required>
            </div>
            <input type="submit" class="btn btn-primary block btn-block m-b" value="Save and Exit">
        </form>
    <script src="app.js"></script> 
</body>
</html>