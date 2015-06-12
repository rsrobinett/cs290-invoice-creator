/* jshint devel:true */
'use strict';

// var addInvoiceLineHandler = function (e) {
//     var invoiceLines = document.getElementById('invoice-lines');
//     var extraLine = '<div class="col-sm-push-2 col-sm-10"><div class="row"><div class="col-sm-3 col-md-3 col-lg-2"><input type="text" class="form-control" placeholder="Description"></div><div class="col-sm-3 col-md-3 col-lg-2"><div class="input-group m-b"><span class="input-group-addon">$</span><input type="text" placeholder="Amount" class="form-control js-amount"></div></div><div class="col-sm-3 col-md-3 col-lg-2 text-right"><button class="btn btn-danger js-remove-line" type="button"><i class="fa fa-trash"></i></button><button class="btn btn-success" type="button"><i class="fa fa-floppy-o"></i></button></div></div></div>';

//     var invoice = document.createElement('div');
//     invoice.classList.add('form-group');
//     invoice.innerHTML = extraLine;
//     invoiceLines.appendChild(invoice);
//     refreshRemoveInvoiceLineHandlerListener();
//     refreshAmountHandlerListener();
//     e.preventDefault();
// };

var changeStatusHandler = function (e){
    var invoiceid = e.target.id;
    currentRow.id;
    var form = document.createElement("form");
    form.action='invoice.php';
    form.invoiceid=invoiceid;
    
    ajaxCall(form, 'senditem');
}

var removeInvoceLineHandler = function (e) {
    var form = e.currentTarget.form;
    if(form.id !== "newitem"){
        ajaxCall(form, 'deleteitem');
    } else {
        form.remove();
    }
    addNewItemLine();
        /*if(document.getElementById("newitem") == undefined){
            location.reload();
        }*/
    refreshRemoveInvoiceLineHandlerListener();
    updateTotal();
}

var addNewItemLine = function(){
    if(document.getElementById("newitem") == undefined){
        var invoiceLines = document.getElementById('invoice-lines');
        var insertbefore = document.getElementById('addnewitemsabove');
        var invoiceid = document.getElementById('invoice').elements.invoiceid.value;
        var extraLine = '<div>        <form method="post" id="newitem" class="form-horizontal" action="invoice.php" onsubmit="ajaxCall(this, \'createitem\'); return false;" >            <input type="hidden" class="form-control" name="invoiceid" value="'+invoiceid+'">            <input type="hidden" class="form-control" name="itemid">            <div class="form-group">                <div class="col-sm-push-2 col-sm-10">                    <div class="row">                       <div class="col-sm-6 col-md-7 col-lg-8">                            <input type="text" class="form-control" placeholder="Description" name="description" required title="Please Enter an Item Description">                        </div>                        <div class="col-sm-3 col-md-3 col-lg-2">                           <div class="input-group m-b">                            <span class="input-group-addon">$</span>                                <input type="text" placeholder="Amount" class="form-control js-amount" name="amount" required pattern="\\d+(\\.\\d{2})?" title="Needs to be a dollar amount. Ex: 1000.33">                            </div>                       </div>                        <div class="col-sm-3 col-md-3 col-lg-2 text-right">                            <button class="btn btn-danger js-remove-line" type="button"><i class="fa fa-trash"></i></button>                                <button class="btn btn-success "type="submit" ><i class="fa fa-floppy-o"></i></button>                        </div>                    </div>                  </div>            </div>        </form>        </div>';
        var line = document.createElement('div');
        line.innerHTML=extraLine;
        invoiceLines.insertBefore(line, insertbefore);
        refreshRemoveInvoiceLineHandlerListener();
    }
}

var addInvoiceIDToItem = function (item){
    var invoiceid = document.getElementById('invoice').elements.invoiceid.value;
    item.elements.invoiceid=invoiceid;
}


var refreshRemoveInvoiceLineHandlerListener = function () {
    var removeLine = document.getElementsByClassName('js-remove-line');
    for (var i = 0; i < removeLine.length; i++) {
        removeLine[i].addEventListener('click', removeInvoceLineHandler);
    };            
}

var refreshAmountHandlerListener = function () {
    var amounts = document.getElementsByClassName('js-amount');
    for (var i = 0; i < amounts.length; i++) {
        amounts[i].addEventListener('blur', updateTotal);
    };    
}

var updateTotal = function () {
    var amounts = document.getElementsByClassName('js-amount');
    var total = document.getElementById('total-amount');
    var subtotal = 0;
    for (var i = 0; i < amounts.length; i++) {
        subtotal += parseFloat(amounts[i].value) || 0;
    };
    total.value = subtotal;        
}


document.addEventListener('DOMContentLoaded', function (event) {
    
    var invoice = document.getElementById('invoice');
    if (invoice) {
        var pickerInvoiceDate = new Pikaday({ 
            field: document.getElementById('invoice-date'), 
            format: 'MM/DD/YYYY'
        });
        
        var pickerDueDate = new Pikaday({ 
            field: document.getElementById('due-date'),
            format: 'MM/DD/YYYY'
        });
        pickerInvoiceDate.setMoment(moment());
        pickerDueDate.setMoment(moment().add(15, 'days'));
    }
    
    var invoicelines = document.getElementById('invoice-lines');
    if (invoicelines) {
        //var extraLine = document.getElementById('extra-line');
        //extraLine.addEventListener('click', addInvoiceLineHandler);    
        refreshRemoveInvoiceLineHandlerListener();
        refreshAmountHandlerListener();
    }
    
    var overview = document.getElementsByTagName("a").elements.getElementByClass('sendinvoice');
    if(overview){
        changeStatusHandler();
    }
    
    var print = document.getElementById('print');
    if (print) {
        print.addEventListener('click', function() { window.print(); });    
    }
});
