<?php
//include config
require_once('includes/db.php');
//check if already logged in move to home page
if( $user->is_logged_in() ){ header('Location: index.php'); } 
//process login form if submitted
if(isset($_POST['submit'])){
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	if($user->login($username,$password)){ 
		$_SESSION['username'] = $username;
		header('Location: main.php');
		exit;
	
	} else {
		$error[] = 'Wrong username or password or your account has not been activated.';
	}
}//end if submit
?>

<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FACTURA+ Login</title>
    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    
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
            <p>You invonce start point</p>
            <p>Login in. To see it in action.</p>
            <div id="errortext"></div>
            <form class="m-t" role="form" id="form" action="" method="post">
                <div class="form-group">
                    <input type="text" class="form-control" name="username" placeholder="Username" id="username" required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Password" id="password" required>
                </div>
                <input type="submit" class="btn btn-primary block btn-block m-b" value="Login">

                <p class="text-muted text-center"><small>Do not have an account?</small></p>
                <a class="btn btn-sm btn-white btn-block" href="index.php">Create an account</a>
            </form>
            <p class="m-t"> <small>OSU CS290 - Rachelle Robinett &copy;2015</small> </p>
        </div>
    </div>
    <script src="app.js"></script> <!-- this one -->
    <script src="scripts/vendor.js"></script>
    <script src="scripts/plugins.js"></script>
    <script src="scripts/main.js"></script>
</body>

</html>