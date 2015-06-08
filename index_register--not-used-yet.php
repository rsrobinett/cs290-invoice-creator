<?php 
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
</head>

<body class="bg-grey">
    <div class="middle-box text-center loginscreen">
        <div>
            <div>
                <h1 class="logo-name">FACTURA+</h1>
            </div>
            <h3>Register to FACTURA+</h3>
            <p>Create account to see it in action.</p>
            <div id="errortext"></div>
            <form class="m-t" role="form" id="form" action="" method="post" autocomplete="off">
                <!--<div class="form-group">-->
                <!--    <input type="text" class="form-control" placeholder="Company" id="companyname" name="companyname" required="">-->
                <!--</div>-->
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Username" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Retype Password" id="passwordConfirm" name="passwordConfirm" required>
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
