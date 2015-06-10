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



function createInvoiceTable($username){
    $invoicearray = getInvoicesBySenderID(getCompanyIDbyUsername($username)); 
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
}

/**************DO NOT DELETE***********************/
//move to recived when it's created
/*
function createReceivedInvoiceTable($username){
    $invoicearray = getInvoicesByBilltoID(getCompanyIDbyUsername($username)); 
    echo "<table>";
    foreach($invoicearray as $index => $invoice){
        echo "<tr>";
        echo "<td> $invoice[lastupdated] </td>";
        echo "<td> $invoice[invoiceid] </td>";
        echo "<td> $invoice[sendername] </td>";
        echo "<td> <span class='label'> $invoice[status] </span> </td>";
        echo "<td> $invoice[total] </td>";
        echo '<td>
            <div class="invoice-actions">
                <a href="#"><i class="fa fa-pencil fa-2x"></i></a><a href="#"><i class="fa fa-trash fa-2x"></i></a>    
            </div></td></tr>';
    }
    echo "</table>";
}

<?php createReceivedInvoiceTable($_SESSION['username'])?>
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
                            <?php echo $_SESSION['username'];?>

                            <strong class="font-bold"><?php echo getCompanyNamebyUsername($_SESSION['username']);?></strong>
                        </p>
                    </li>
                    <li class="active">
                        <a href="overview.html"><i class="fa fa-inbox"></i> <span class="nav-label">Overview</span></a>
                    </li>
                    <li>
                        <a href="create.php"><i class="fa fa-pencil"></i> <span class="nav-label">Create</span></a>
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
                                        <?php createInvoiceTable($_SESSION['username']);?>
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
