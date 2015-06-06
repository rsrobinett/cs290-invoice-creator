<?php
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
    <title>Login Page</title>
</head>

<body>
    <div><?php
        if(isset($_SESSION['username'])){
            $strRedirect = '/login.php?action=logout';
            echo "you are currently logged in a s". $_SESSION['username']." if this is not you, please <a href=\"". getPath()."'/login.php?action=logout'\">logout</a>";
        }?>
    </div>
    <form action=<?php echo getPath()."/main.php"; ?> method="post">
    <div>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" />
    </div>
    <div>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" />
    </div>
    <div class="button">
        <input type="submit" name="login" value="Login"/>
    </div>
    </form>
</body>

</html>