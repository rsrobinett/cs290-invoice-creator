/* jshint devel:true */
'use strict';

var addInvoiceLineHandler = function (e) {
    var invoiceLines = document.getElementById('invoice-lines');
    var extraLine = '<div class="col-sm-push-2 col-sm-10"><div class="row"><div class="col-sm-3 col-md-3 col-lg-2"><input type="text" class="form-control" placeholder="Description"></div><div class="col-sm-3 col-md-3 col-lg-2"><div class="input-group m-b"><span class="input-group-addon">$</span><input type="text" placeholder="Amount" class="form-control js-amount"></div></div><div class="col-sm-3 col-md-3 col-lg-2 text-right"><button class="btn btn-danger js-remove-line" type="button"><i class="fa fa-trash"></i></button><button class="btn btn-success" type="button"><i class="fa fa-floppy-o"></i></button></div></div></div>';

    var invoice = document.createElement('div');
    invoice.classList.add('form-group');
    invoice.innerHTML = extraLine;
    invoiceLines.appendChild(invoice);
    refreshRemoveInvoiceLineHandlerListener();
    refreshAmountHandlerListener();
    e.preventDefault();
};

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
        var extraLine = '<div>        <form method="post" id="newitem" class="form-horizontal" action="invoice.php" onsubmit="ajaxCall(this, \'createitem\'); return false;" >            <input type="hidden" class="form-control" name="invoiceid" value="'+invoiceid+'">            <input type="hidden" class="form-control" name="itemid">            <div class="form-group">                <div class="col-sm-push-2 col-sm-10">                    <div class="row">                       <div class="col-sm-6 col-md-7 col-lg-8">                            <input type="text" class="form-control" placeholder="Description" name="description">                        </div>                        <div class="col-sm-3 col-md-3 col-lg-2">                           <div class="input-group m-b">                            <span class="input-group-addon">$</span>                                <input type="text" placeholder="Amount" class="form-control js-amount" name="amount">                            </div>                       </div>                        <div class="col-sm-3 col-md-3 col-lg-2 text-right">                            <button class="btn btn-danger js-remove-line" type="button"><i class="fa fa-trash"></i></button>                                <button class="btn btn-success "type="submit" ><i class="fa fa-floppy-o"></i></button>                        </div>                    </div>                  </div>            </div>        </form>        </div>';
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


// var validator = function() {
//     var regexCurrency  = /^[1-9]\d*(((,\d{3}){1})?(\.\d{0,2})?)$/;
//     var numStr = "123.20";
//     if (regexCurrency.test(numStr))
//         alert("Number is valid");
// }


// var createJson = function(e) {
//     e.preventDefault();
//     var form = document.getElementById('invoice');
//     var json = {lines:[]};

//     for (var i = 0; i < form.length; i++){
//         if (!!form[i].name) {

//             if(form[i].name === 'description') {
//                 var line = {};

//                 line[encodeURIComponent(form[i].name)] = encodeURIComponent(form[i].value);
//                 line[encodeURIComponent(form[i+1].name)] = encodeURIComponent(form[i+1].value);
//                 line['line'] = i;
//                 json.lines.push(line);
//             } else {
//                 json[encodeURIComponent(form[i].name)] = encodeURIComponent(form[i].value);
//             }
//         }
//     }

//     console.log(JSON.stringify(json));
// }

document.addEventListener('DOMContentLoaded', function (event) {
    
    var invoice = document.getElementById('invoice');
    if (invoice) {

        var pickerInvoiceDate = new Pikaday({ field: document.getElementById('invoice-date') });
        var pickerDueDate = new Pikaday({ field: document.getElementById('due-date') });

        // var sendButton = document.getElementById('send');
        // sendButton.addEventListener('click', createJson);
    }
    
    var invoicelines = document.getElementById('invoice-lines');
    if (invoicelines) {
        //var extraLine = document.getElementById('extra-line');
        //extraLine.addEventListener('click', addInvoiceLineHandler);    
        refreshRemoveInvoiceLineHandlerListener();
        refreshAmountHandlerListener();
    }
    
    
    var print = document.getElementById('print');
    if (print) {
        print.addEventListener('click', function() { window.print(); });    
    }
});
