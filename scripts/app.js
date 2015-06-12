var ajaxCall = function (form, action) {
    var url = form.action,
        httpRequest = new XMLHttpRequest();
        
    if(!httpRequest){
      alert('Cannot create an XMLHttpRequest instance');
    }
    
    httpRequest.onreadystatechange = function() {
        var errordiv=document.getElementById("errortext");
        var errortxt = '';
        if (httpRequest.readyState === 4) {
            if(httpRequest.status === 200){
                var data = httpRequest.responseText.trim();
                if(data === "success"){
                    //redirect to main page here
                    document.location.href = "main.php";
                } else if(data === "saved"){
                    //stay on page
                    //document.location.href = "invoice.php";
                    return;
                } else {
                    try{
                        data=JSON.parse(data);
                    }catch(e){};
                    if(typeof data === "object"){
                        if(data.status == "newinvoicesaved"){
                            document.location.href = "create.php?invoiceid="+data.id;
                            return;
                        } else if(data.status === "itemdeleted"){
                            document.getElementById("item"+data.id).remove();
                            return;
                        } else if(data.status === "newitemsaved") {
                            var newitem = document.getElementById("newitem");
                            addInvoiceIDToItem(newitem);
                            newitem.itemid = data.id;
                            newitem.id = "item"+data.id;
                            addNewItemLine();
                            return;
                        } else if(data.status === "statussaved"){
                            try{
                            var e = document.getElementById(data.id).parentElement.parentElement.parentElement.parentElement.getElementsByClassName('label')[0];
                            if(e != undefined && e != null){
                                e.textContent=data.newstatus;
                            }
                            }catch(e){};
                            try{
                                document.getElementById('paymentstatus').textContent="Paid";
                            }catch(e){};
                            
                            return;
                        }
                    }
                }
                errortxt += data;
            }
            else if(httpRequest.status == 400) {
                errortxt += ' There was an error 400';
            }
            else {
                errortxt += ' something else other than 200 was returned';
            }
        }
        errordiv.textContent = errortxt;
    };
            
    //get form fields
    var params = [].filter.call(form.elements, function(element) {
        return !!element.name || element.disabled;
    })
    //convert from fields to params
    .map(function(element) {
            return encodeURIComponent(element.name) + '=' + encodeURIComponent(element.value);
    }).join('&'); 
    
    params += '&action=' + action;

    httpRequest.open("POST", url);
    httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    httpRequest.send(params);
};
