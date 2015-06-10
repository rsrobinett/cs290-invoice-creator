<?php $pagetitle = Invoice;?>

<?php include 'shared/head.php';?>
<?php include 'shared/navigation.php';?>
<?php include 'shared/header.php';?>

<?php 
    //need to get invoice data from db
    $info = getInvoicesByIDandBillToUsername($_GET['invoiceid'],$_SESSION['username']);
    //need to get itemdata from db
    var_dump($info);

?>

<div class="row">
    <div class="col-sm-6">
        <h5>From:</h5>
        <address>
            <strong>Inspinia, Inc.</strong>
            <br> 106 Jorg Avenu, 600/10
            <br> Chicago, VT 32456
            <br>
            <abbr title="Phone">P:</abbr> (123) 601-4590
        </address>
    </div>
    <div class="col-sm-6 text-right">
        <h4>Invoice No.</h4>
        <h4 class="text-navy">INV-000567F7-00</h4>
        <span>To:</span>
        <address>
            <strong>Corporate, Inc.</strong>
            <br> 112 Street Avenu, 1080
            <br> Miami, CT 445611
            <br>
            <abbr title="Phone">P:</abbr> (120) 9000-4321
        </address>
        <p>
            <span><strong>Invoice Date:</strong> Marh 18, 2014</span>
            <br>
            <span><strong>Due Date:</strong> March 24, 2014</span>
        </p>
    </div>
</div>
<div class="table-responsive m-t">
    <table class="table invoice-table">
        <thead>
            <tr>
                <th>Item List</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div><strong>Admin Theme with psd project layouts</strong></div>
                    <small>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</small></td>
                <td>$31,98</td>
            </tr>
            <tr>
                <td>
                    <div><strong>Wodpress Them customization</strong></div>
                    <small>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                    Eiusmod tempor incididunt ut labore et dolore magna aliqua.
                </small></td>
                <td>$196.80</td>
            </tr>
            <tr>
                <td>
                    <div><strong>Angular JS &amp; Node JS Application</strong></div>
                    <small>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</small></td>
                <td>$1033.20</td>
            </tr>
        </tbody>
    </table>
</div>
<!-- /table-responsive -->
<table class="table invoice-total">
    <tbody>
        <tr>
            <td><strong>TOTAL :</strong></td>
            <td>$1261.98</td>
        </tr>
    </tbody>
</table>
<div class="text-right">
    <button class="btn btn-primary"><i class="fa fa-dollar"></i> Send Payment</button>
</div>
<div class="title-action">
    <a href="invoice_print.html" target="_blank" class="btn btn-primary"><i class="fa fa-print"></i> Print Invoice </a>
</div>
<div class="well m-t"><strong>Comments</strong> It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less
</div>



<?php include 'shared/footer.php';?>