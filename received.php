<?php $pagetitle = Recieved;?>

<?php include 'shared/head.php';?>
<?php include 'shared/navigation.php';?>
<?php include 'shared/header.php';?>

<?php

function createReceivedInvoiceTable($username){
    $invoicearray = getInvoicesByBilltoID(getCompanyIDbyUsername($username)); 
    foreach($invoicearray as $index => $invoice){
        echo "<tr>";
        echo "<td> $invoice[duedate] </td>";
        echo "<td> $invoice[invoiceid] </td>";
        echo "<td> $invoice[sendername] </td>";
        echo "<td> <span class='label'> $invoice[status] </span> </td>";
        echo "<td> $invoice[total] </td>";
        echo '<td>
            <div class="invoice-actions">
               <a href="'.getPath().'/viewinvoice.php?invoiceid='.$invoice['invoiceid'].'"><i class="fa fa-folder-open-o fa-2x"></i></a></a>
            </div></td></tr>';
    }
}
?>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Due Date</th>
                <th>Invoice #</th>
                <th>Sender</th>
                <th>Status</th>
                <th colspan="2">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php createReceivedInvoiceTable($_SESSION['username']); ?>
        </tbody>
    </table>
</div>

<?php include 'shared/footer.php';?>