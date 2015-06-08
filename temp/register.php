<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
session_start();
function getPath(){
    $filepath = explode('/', $_SERVER['PHP_SELF'], -1);
    $filepath = implode('/', $filepath);
    $redirect = "http://" .$_SERVER['HTTP_HOST'].$filepath;
    return $redirect;
}
if(isset($_SESSION['username'])){
    header("Location: ".getPath()."/main.php", true);
}
?>

<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FACTURA+ Register</title>
    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    
    <link rel="stylesheet" href="styles/main.css">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700' rel='stylesheet' type='text/css'>
    <script src="scripts/vendor/modernizr.js"></script>
    <script>
    var validateRegistration = function(){
    var username=document.getElementById("username").value;
    var password=document.getElementById("password").value;
    var companyname=document.getElementById("companyname").value;
    //do form checking here. 
    var httpRequest = new XMLHttpRequest();
    var url = "src/validation.php";
    
    if(!httpRequest){
       alert('Cannot create an XMLHttpRequest instance');
    }
    
    httpRequest.onreadystatechange = function() {
    var errordiv=document.getElementById("errortext");
    var errortxt = '';
    if (httpRequest.readyState === 4) {
       if(httpRequest.status === 200){
           var data = httpRequest.responseText.trim();
           if(data == "ok"){
                //redirect to main page here (happens through submit form returning true)
                document.getElementById("form").submit();
                href.location = "main.php";
                return true;
           } else {
               //should this be just text content? 
               errortxt += " " + data;
           }
        }
       else if(httpRequest.status == 400) {
          errortxt += ' There was an error 400';
       }
       else {
           errortxt += ' something else other than 200 was returned';
       }
      }
    //   errordiv.textContent = errortxt;
    };
    
    var formData = "username="+username+"&password="+password+"&companyname="+companyname+"&validateregister=true";
    
    httpRequest.open("POST", url, true);
    httpRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    httpRequest.send(formData);

    return false;
}
        
    </script>
</head>

<body class="bg-grey">
    <div class="middle-box text-center loginscreen">
        <div>
            <div>
                <h1 class="logo-name">FACTURA+</h1>
            </div>
            <h3>Register to FACTURA+</h3>
            <p>Create account to see it in action.</p>
            <div class="errortext"></div>
            <form class="m-t" role="form" id="form" action="main.php" method="post" onsubmit="validateRegistration(); return false;">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Company" id="companyname" name="companyname" required="">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Username" id="username" name="username" required="">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password" id="password" name="password" required="">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Retype Password" id="repassword" name="repassword" required="">
                </div>
                <input type="submit" class="btn btn-primary block full-width m-b" value="Register" name="register">

                <p class="text-muted text-center"><small>Already have an account?</small></p>
                <a class="btn btn-sm btn-white btn-block" href="/">Login</a>
            </form>
            <p class="m-t"> <small>OSU CS290 - Rachelle Robinett &copy;2015</small> </p>
        </div>
    </div>
    <script src="app.js"></script>
    <script src="scripts/vendor.js"></script>
    <script src="scripts/plugins.js"></script>
    <script src="scripts/main.js"></script>
</body>

</html>
