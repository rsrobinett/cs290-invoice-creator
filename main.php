<?php $pagetitle = 'Accounts Receivable';?>

<?php include 'shared/head.php';?>
<?php include 'shared/navigation.php';?>
<?php include 'shared/header.php';?>

<?php 
function createInvoiceTable($username){
    $invoicearray = getInvoicesBySenderID(getCompanyIDbyUsername($username)); 
    foreach($invoicearray as $index => $invoice){
        echo "<tr>";
        echo "<td> $invoice[lastupdated] </td>";
        echo "<td> #$invoice[invoiceid] </td>";
        echo "<td> $$invoice[billtoname] </td>";
        echo "<td> <span class='label'> $invoice[status] </span> </td>";
        echo "<td> $invoice[total] </td>";
        echo '<td>
            <div class="invoice-actions">
                <a href="'.getPath().'/create.php?invoiceid='.$invoice['invoiceid'].'"><i class="fa fa-pencil fa-2x"></i></a>
                <a href="#"><i  id="'.$invoice['invoiceid'].'" class="sendinvoice fa fa-paper-plane fa-2x"></i></a>
            </div></td></tr>';
    }
}
?>

<div class="table-responsive">
    <div id="errortext"></div>
    <table id="overview" class="table table-striped">
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
        </tbody>
    </table>
</div>

<?php include 'shared/footer.php';?>