/* jshint devel:true */
'use strict';

var addInvoiceLineHandler = function (e) {
    var invoiceLines = document.getElementById('invoice-lines');
    var extraLine = '<div class="col-sm-push-2 col-sm-10"><div class="row"><div class="col-md-8"><input type="text" class="form-control" placeholder="Description"></div><div class="col-md-3"><div class="input-group m-b"><span class="input-group-addon">$</span><input type="text" placeholder="Amount" class="form-control js-amount"></div></div><div class="col-md-1 text-right"><button class="btn btn-danger js-remove-line" type="button"><i class="fa fa-trash"></i></button></div></div></div>';

    var invoice = document.createElement('div');
    invoice.classList.add('form-group');
    invoice.innerHTML = extraLine;
    invoiceLines.appendChild(invoice);
    refreshRemoveInvoiceLineHandlerListener();
    refreshAmountHandlerListener();
    e.preventDefault();
};

var removeInvoceLineHandler = function (e) {
    e.target.parentNode.parentNode.parentNode.parentNode.remove();
    updateTotal();
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
    var extraLine = document.getElementById('extra-line');
    extraLine.addEventListener('click', addInvoiceLineHandler);

    refreshRemoveInvoiceLineHandlerListener();
    refreshAmountHandlerListener();

    var pickerInvoiceDate = new Pikaday({ field: document.getElementById('invoice-date') });
    var pickerDueDate = new Pikaday({ field: document.getElementById('due-date') });
});
