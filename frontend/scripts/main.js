/* jshint devel:true */
'use strict';

var addInvoiceLineHandler = function (e) {
    var invoiceLines = document.getElementById('invoice-lines');
    var s = '<div class="col-sm-push-2 col-sm-10"><div class="row"><div class="col-md-9"><input type="text" class="form-control" placeholder="Description"></div><div class="col-md-3"><div class="input-group m-b"><span class="input-group-addon">$</span><input type="text" placeholder="Amount" class="form-control"></div></div></div></div>';
    var invoice = document.createElement('div');
    invoice.classList.add('form-group');
    invoice.innerHTML = s;
    invoiceLines.appendChild(invoice);
    e.preventDefault();
};

document.addEventListener('DOMContentLoaded', function (event) {
    var extraLine = document.getElementById('extra-line');
    extraLine.addEventListener('click', addInvoiceLineHandler);

    var pickerInvoiceDate = new Pikaday({ field: document.getElementById('invoice-date') });
    var pickerDueDate = new Pikaday({ field: document.getElementById('due-date') });
});
