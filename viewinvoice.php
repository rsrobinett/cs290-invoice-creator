<?php $pagetitle = Invoice;?>

<?php include 'shared/head.php';?>
<?php include 'shared/navigation.php';?>
<?php include 'shared/header.php';?>

<?php 
    //need to get invoice data from db
    $invoice = getInvoicesByIDandBillToUsername($_GET['invoiceid'],$_SESSION['username']);
    //need to get itemdata from db

function createItemTable($id, $username){
    $items = getItemsByInvoiceIDandBillToUsername($id, $username); 
    foreach($items as $index => $item){
        echo "<tr>";
        echo "<td>";
        //echo "<div><strong></strong></div>";
        //echo "<small> $item[description] </small>";
        echo "$item[description]";
        echo "</td>";
        echo "<td>\$$item[amount] </td>";
        echo '</tr>';
    }
}

$total = getInvoiceTotalByInvoiceIDandBilltoUsername($_GET['invoiceid'],$_SESSION['username']);

?>

<div class="row">
    <div class="col-xs-6">
        <h5>From:</h5>
        <address>
            <strong><?php echo $invoice[0]['sender']['name']; ?></strong>
            <br> <?php echo $invoice[0]['sender']['address']; ?> 
            <br><?php echo $invoice[0]['sender']['city'].", ".$invoice[0]['sender']['state']." ".$invoice[0]['sender']['zip']; ?>
            <br>
        </address>
    </div>
    <div class="col-xs-6 text-right">
        <h4>Invoice No.</h4>
        <h4 class="text-navy"><?php echo $invoice[0]['invoiceid']; ?></h4>
        <span>To:</span>
        <address>
            <strong><?php echo $invoice[0]['billto']['name']; ?></strong>
            <br> <?php echo $invoice[0]['billto']['address']; ?> 
            <br><?php echo $invoice[0]['billto']['city'].", ".$invoice[0]['sender']['state']." ".$invoice[0]['sender']['zip']; ?>
            <br>
        </address>
        <p>
            <span><strong>Invoice Date:</strong> <?php echo $invoice[0]['invoicedate']; ?></span>
            <br>
            <span><strong>Due Date:</strong> <?php echo $invoice[0]['duedate']; ?></span>
        </p>
    </div>
</div>
<div class="table-responsive m-t">
    <table class="table invoice-table">
        <thead>
            <tr>
                <th>Item List</th>
                <th>Item Price</th>
            </tr>
        </thead>
        <tbody>
            <?php createItemTable($_GET['invoiceid'], $_SESSION['username']); ?>
        </tbody>
    </table>
</div>
<table class="table invoice-total">
    <tbody>
        <tr>
            <td><strong>TOTAL :</strong></td>
            <td>$<?php echo $total; ?></td>
        </tr>
    </tbody>
</table>
<div class="text-right no-print">
    <button class="btn btn-info" id="print"><i class="fa fa-print"></i> Print</button>
    <button class="btn btn-primary"><i class="fa fa-dollar"></i> Send Payment</button>
</div>
<div class="well m-t"><strong>Comments </strong><?php echo $invoice[0]['comment']; ?>
</div>

<?php include 'shared/footer.php';?>