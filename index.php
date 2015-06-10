<?php 
require_once "functions.php";
session_start();

//kill session if logout set
if(isset($_GET['action']) && $_GET['action'] === 'logout'){
  $_SESSION = array();
  session_destroy();
  redirect("index.php");
}

//redirect user if already logged in
if(isset($_SESSION['username']) && (!isset($_GET['action']))){
  redirect("main.php");
}

?>

<!DOCTYPE html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FACTURA+ Login</title>
    
    <link rel="stylesheet" href="styles/main.css">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700' rel='stylesheet' type='text/css'>
</head>

<body class="bg-grey">
    <div class="middle-box text-center loginscreen">
        <div>
            <div>
                <h1 class="logo-name">FACTURA+</h1>
            </div>
            <h3>Welcome to FACTURA+</h3>
            <p>You invoice starting point</p>
            <p>Login in. To see it in action.</p>
            <div id="errortext"></div>
            <form class="m-t" role="form" id="form" action="auth.php" method="post" autocomplete="off" onsubmit="ajaxCall(this, 'login'); return false;">
                <div class="form-group">
                    <input type="text" class="form-control" name="username" placeholder="Username" id="username" required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Password" id="password" required>
                </div>
                <input type="submit" class="btn btn-primary block btn-block m-b" value="Login">

                <p class="text-muted text-center"><small>Do not have an account?</small></p>
                <a class="btn btn-sm btn-white btn-block" href="register_index.php">Create an account</a>
            </form>
            <p class="m-t"> <small>OSU CS290 - Rachelle Robinett &copy;2015</small> </p>
        </div>
    </div>
    <script src="scripts/app.js"></script> 
    <script src="scripts/main.js"></script>
</body>

</html>