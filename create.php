<?php $pagetitle = "Create Invoice";?>
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

function prefillItems($id, $username){
    $items = getItemsByInvoiceIDandSenderUsername($id, $username); 
    foreach($items as $index => $item){
      
        echo'
        <div>
        <form method="post" id="item'.$item['itemid'].'" class="form-horizontal" action="invoice.php" onsubmit="ajaxCall(this, \'createitem\'); return false;">
            <input type="hidden" class="form-control" name="invoiceid" value="'.$item['invoiceid'].'">
            <input type="hidden" class="form-control" name="itemid" value="'.$item['itemid'].'">        
            <div class="form-group">
                <div class="col-sm-push-2 col-sm-10">
                    <div class="row">
                        <div class="col-sm-6 col-md-7 col-lg-8">
                            <input type="text" class="form-control" placeholder="Description" name="description" value="'.$item['description'].'">
                        </div>
                        <div class="col-sm-3 col-md-3 col-lg-2">
                            <div class="input-group m-b">
                            <span class="input-group-addon">$</span>
                                <input type="text" placeholder="Amount" class="form-control js-amount" name="amount" value="'.$item['amount'].'">
                            </div>
                        </div>
                        <div class="col-sm-3 col-md-3 col-lg-2 text-right">
                            <button class="btn btn-danger js-remove-line" type="button"><i class="fa fa-trash"></i></button>
                            <button class="btn btn-success " type="submit" ><i class="fa fa-floppy-o"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        </div>';
            
    }
}

$total = null;
if(isset($_GET['invoiceid'])){
    $total = getInvoiceTotalByInvoiceIDandSenderUsername($_GET['invoiceid'],$_SESSION['username']);
}

?>

<form method="post" id="invoice" class="form-horizontal" action="invoice.php" onsubmit="ajaxCall(this, 'createinvoice'); return false;">
    <div id="errortext"></div>
    <h2>Recipient information</h2>
    <div class="form-group">
        <input type="hidden" class="form-control" id="invoiceid" name="invoiceid" value="<?php echo prefilledInvoiceValue($invoice,'invoiceid') ?>">
    </div>
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
    <div class="form-group">
        <label class="col-sm-2 control-label" >Comment</label>
        <div class="col-sm-10">
            <input type="text" name="comment" class="form-control" value="<?php echo prefilledInvoiceValue($invoice,'comment') ?>">
        </div>
    </div>
    <div class="hr-line-dashed"></div>
    <div class="form-group">
        <div class="col-sm-4 col-sm-offset-2">
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
    </div>
</form>


    <div class="hr-line-dashed"></div>
    <h2>Invoice lines</h2>
    <div <?php if(isset($_GET['invoiceid'])){echo 'hidden';}?>> Invoice Lines can be added after you have saved the Recipient Information </div>
    <div id="invoice-lines" <?php if(!isset($_GET['invoiceid'])){echo 'hidden';}?>>
        <?php if(isset($_GET['invoiceid'])){ prefillItems($_GET['invoiceid'],$_SESSION['username']);} ?>
        <div>
        <form method="post" id="newitem" class="form-horizontal" action="invoice.php" onsubmit="ajaxCall(this, 'createitem'); return false;" >
            <input type="hidden" class="form-control" name="invoiceid" value="<?php if(isset($_GET['invoiceid'])){echo $_GET['invoiceid'];}?>">
            <input type="hidden" class="form-control" name="itemid">
            <div class="form-group">
                <div class="col-sm-push-2 col-sm-10">
                    <div class="row">
                        <div class="col-sm-6 col-md-7 col-lg-8">
                            <input type="text" class="form-control" placeholder="Description" name="description">
                        </div>
                        <div class="col-sm-3 col-md-3 col-lg-2">
                            <div class="input-group m-b">
                            <span class="input-group-addon">$</span>
                                <input type="text" placeholder="Amount" class="form-control js-amount" name="amount">
                            </div>
                        </div>
                        <div class="col-sm-3 col-md-3 col-lg-2 text-right">
                            <button class="btn btn-danger js-remove-line" type="button"><i class="fa fa-trash"></i></button>    
                            <button class="btn btn-success " type="submit" ><i class="fa fa-floppy-o"></i></button>
                        </div>
                    </div>  
                </div>
            </div>
        </form>
        </div>
        <div id="addnewitemsabove"></div>
        <div>
        <div class="form-group">
            <div class="col-sm-push-2 col-sm-10">
                <div class="row">
                    <div class="col-md-push-8 col-sm-3 col-md-3 col-lg-2">
                        <div class="input-group m-b">
                        <span class="input-group-addon">$</span>
                            <input type="text" placeholder="Total" id="total-amount" class="form-control" value="<?php echo $total?>" disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
        
<?php include 'shared/footer.php';?>