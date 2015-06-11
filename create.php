<?php $pagetitle = "Create";?>

<?php include 'shared/head.php';?>
<?php include 'shared/navigation.php';?>
<?php include 'shared/header.php';?>

<?php
$invoice = null;
if(isset($_GET['invoiceid'])){
    $invoice = getInvoicesByIDandSenderUsername($_GET['invoiceid'],$_SESSION['username']);
};

function companyOptions($invoice){
    $companyArray = getCompanyNames();
    
    foreach($companyArray as $id=>$name){
        if($id === prefilledInvoiceCompany($invoice)){
            echo "<option value='$id' selected>'$name'</option>";
        } else {
            echo "<option value='$id'>'$name'</option>";
        }
    }
}


var_dump($invoice); 

function prefilledInvoiceCompany($invoice){
   if(isset($invoice)){
      return $invoice['billto']['id'];
   } 
}

function prefilledInvoiceValue($invoice, $field){
    if(isset($invoice)){
      return $invoice[$field];
   }   
}





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


$total = null;
if(isset($_GET['invoiceid'])){
    $total = getInvoiceTotalByInvoiceIDandSenderUsername($_GET['invoiceid'],$_SESSION['username']);
}
//var_dump($total);




/*
function invoiceidparameter(){
    if(isset($_GET['invoiceid'])){
        return "?invoiceid=$_GET[invoiceid]";
    }
}
<?php echo invoiceidparameter(); ?>
*/
?>

<form method="post" class="form-horizontal" action="invoice.php" onsubmit="ajaxCall(this, 'createinvoice'); return false;">
    <div id="errortext"></div>
    <h2>Recipient information</h2>
    <div class="form-group">
        <label class="col-sm-2 control-label">Name or Company</label>
        <div class="col-sm-4">
            <select class="form-control m-b" name="billtoid">
                <option value="">Select a Name or Company</option>
                <?php companyOptions($invoice); ?>
            </select>
        </div>
    </div>
    <div class="hr-line-dashed"></div>                                
    <div class="form-group">
        <label class="col-sm-2 control-label">Invoice date</label>
        <div class="col-sm-3">
            <div class="input-group date">
                <span class="input-group-addon" ><i class="fa fa-calendar"></i></span>
                <input type="text" class="form-control" id="invoice-date" name="invoicedate" value="<?php echo prefilledInvoiceValue($invoice,'invoicedate') ?>">
            </div>                                        
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Due date</label>
        <div class="col-sm-3">
            <div class="input-group date">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input type="text" class="form-control" id="due-date" name="duedate" value="<?php echo prefilledInvoiceValue($invoice,'duedate') ?>">
            </div>                                        
        </div>
    </div>
    
    <div class="hr-line-dashed"></div>
    <h2>Invoice lines</h2>
    <div id="invoice-lines">
        <div class="form-group">
            <div class="col-sm-push-2 col-sm-10">
                <div class="row">
                    <div class="col-md-8">
                        <input type="text" class="form-control" placeholder="Description" name="description">
                    </div>
                    <div class="col-md-3">
                        <div class="input-group m-b">
                        <span class="input-group-addon">$</span>
                            <input type="text" placeholder="Amount" class="form-control js-amount" name="amount">
                        </div>
                    </div>
                    <div class="col-md-1 text-right">
                        <button class="btn btn-danger js-remove-line" type="button"><i class="fa fa-trash"></i></button>    
                    </div>
                </div>  
            </div>
        </div>                                    
    </div>
    <div class="form-group">
        <div class="col-sm-1 col-sm-offset-11 text-right">
            <button class="btn btn-success " type="button" id="extra-line"><i class="fa fa-plus"></i> Line</button>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-push-2 col-sm-10">
            <div class="row">
                <div class="col-md-push-8 col-md-3">
                    <div class="input-group m-b">
                    <span class="input-group-addon">$</span>
                        <input type="text" placeholder="Total" id="total-amount" class="form-control" value="<?php echo $total?>" disabled>
                    </div>
                </div>
            </div>
        </div>
    </div>                                
    <div class="hr-line-dashed"></div>
    <div class="form-group">
        <label class="col-sm-2 control-label" name="comment">Comment</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" value="<?php echo prefilledInvoiceValue($invoice,'comment') ?>">
        </div>
    </div>
    <div class="hr-line-dashed"></div>
    <div class="form-group">
        <div class="col-sm-4 col-sm-offset-2">
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
    </div>
</form>

<?php include 'shared/footer.php';?>