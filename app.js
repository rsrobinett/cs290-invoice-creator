// var validateLogin = function() {
//     var username=document.getElementById("username").value;
//     var password=document.getElementById("password").value;
    
//     //do form checking here. 
//     var httpRequest = new XMLHttpRequest();
//     var url = "main.php"; //src/validation.php";
    
//     if (!httpRequest) {
//       alert('Cannot create an XMLHttpRequest instance');
//     }
    
//     httpRequest.onreadystatechange = function() {
//         var errordiv = document.getElementById("errortext");
//         var errortxt = '';
//         if (httpRequest.readyState === 4) {
//             if(httpRequest.status === 200){
//                 var data = httpRequest.responseText.trim();
//                 if(data == "ok"){
//                     //redirect to main page here (happens through submit form returning true)
//                     location.href="main.php";
//                     // document.getElementById("loginform").submit();
//                     return true;
//                 } else {
//                     //should this be just text content? 
//                     errortxt += " " + data;
//                 }
//                 errordiv.textContent = errortxt;
//                 return false;
//             }
//             else if(httpRequest.status == 400) {
//                 errortxt += ' There was an error 400';
//             }
//             else {
//                 errortxt += ' something else other than 200 was returned';
//             }
//         }
//     };
    
//     var formData = "username="+username+"&password="+password+"&validatelogin=true";
    
//     httpRequest.open("POST", url, true);
//     httpRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
//     httpRequest.send(formData);
// };






/**
 * Source: http://stackoverflow.com/questions/6990729/simple-ajax-form-using-javascript-no-jquery
 * Takes a form node and sends it over AJAX.
 * @param {HTMLFormElement} form - Form node to send
 * @param {function} callback - Function to handle onload. 
 *                              this variable will be bound correctly.
 */

var ajaxAuth = function (form, action) {
    var url = form.action,
        xhr = new XMLHttpRequest();
        
    if(!xhr){
      alert('Cannot create an XMLHttpRequest instance');
    }
    
    xhr.onreadystatechange = function() {
        var errordiv=document.getElementById("errortext");
        var errortxt = '';
        if (xhr.readyState === 4) {
            if(xhr.status === 200){
                var data = xhr.responseText.trim();
                if(data === "1"){
                    //redirect to main page here (happens through submit form returning true)
                    document.location.href = "main.php";
                } else {
                  //should this be just text content? 
                  errortxt += data;
                }
            }
            else if(xhr.status == 400) {
                errortxt += ' There was an error 400';
            }
            else {
                errortxt += ' something else other than 200 was returned';
            }
        }
        errordiv.textContent = errortxt;
    };
            

    //This is a bit tricky, [].fn.call(form.elements, ...) allows us to call .fn
    //on the form's elements, even though it's not an array. Effectively
    //Filtering all of the fields on the form
    var params = [].filter.call(form.elements, function(el) {
        //Allow only elements that don't have the 'checked' property
        //Or those who have it, and it's checked for them.
        // return typeof(el.checked) === 'undefined' || el.checked;
        //Practically, filter out checkboxes/radios which aren't checekd.
        return !!el.name || el.disabled;
    })
    // .filter(function(el) { return !!el.name; } //Nameless elements die.
    // .filter(function(el) { return el.disabled; } //Disabled elements die.
    .map(function(el) {
        //Map each field into a name=value string, make sure to properly escape!
        return encodeURIComponent(el.name) + '=' + encodeURIComponent(el.value);
    }).join('&'); //Then join all the strings by &
    
    params += '&action=' + action;

    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    //.bind ensures that this inside of the function is the XHR object.
    // xhr.onload = callback.bind(xhr); 

    //All preperations are clear, send the request!
    xhr.send(params);
}


document.addEventListener("DOMContentLoaded", function(event) { 
    // do stuff on load, like jequery ready
});