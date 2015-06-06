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
if(isset($_GET['action']) && $_GET['action'] == 'logout'){
  $_SESSION = array();
  session_destroy();
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Registration Page</title>
</head>

<body>
    <form action=<?php echo getPath()."/main.php"; ?> method="post">
    <div>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" />
    </div>
    <div>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" />
    </div>
    <div>
        <label for="username">Company Name:</label>
        <input type="text" id="companyname" name="companyname" />
    </div>  
    <div>
        <label for="username">First Name:</label>
        <input type="text" id="firstname" name="firstname" />
    </div> 
    <div>
        <label for="username">Last Name:</label>
        <input type="text" id="lastname" name="lastname" />
    </div> 
    <div>
        <label for="username">Address:</label>
        <input type="text" id="address" name="address" />
    </div>
    <div class="button">
        <input type="submit" name='register' value="Register"/>
    </div>
    </form>
</body>

</html>