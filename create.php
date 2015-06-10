<?php $pagetitle = "Create";?>

<?php include 'shared/head.php';?>
<?php include 'shared/navigation.php';?>
<?php include 'shared/header.php';?>

<?php
function companyOptions(){
    
    $companyArray = getCompanyNames();
    
    foreach($companyArray as $id=>$name){
        echo "<option value='$id'>'$name'</option>";
    }
}

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
                <option value="" selected>Select a Name or Company</option>
                <?php companyOptions(); ?>
            </select>
        </div>
    </div>
    <div class="hr-line-dashed"></div>                                
    <div class="form-group">
        <label class="col-sm-2 control-label">Invoice date</label>
        <div class="col-sm-3">
            <div class="input-group date">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input type="text" class="form-control" id="invoice-date" name="invoicedate">
            </div>                                        
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Due date</label>
        <div class="col-sm-3">
            <div class="input-group date">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input type="text" class="form-control" id="due-date" name="duedate">
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
                        <input type="text" class="form-control" placeholder="Description">
                    </div>
                    <div class="col-md-3">
                        <div class="input-group m-b">
                        <span class="input-group-addon">$</span>
                            <input type="text" placeholder="Amount" class="form-control js-amount">
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
                        <input type="text" placeholder="Total" id="total-amount" class="form-control" disabled>
                    </div>
                </div>
            </div>
        </div>
    </div>                                
    <div class="hr-line-dashed"></div>
    <div class="form-group">
        <label class="col-sm-2 control-label" name="comment">Comment</label>
        <div class="col-sm-10">
            <input type="text" class="form-control">
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