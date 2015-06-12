<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();

require_once 'src/dbOperations.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['action'])){
        $action = $_POST['action'];
    } 
    if(isset($_POST['username'])){
        $username = $_POST['username'];
    }
    if(isset($_POST['password']))
    {
        $password = $_POST['password'];
    }
    if(isset($_POST['repassword']))
    {
        $repassword = $_POST['repassword'];
    }
    if(isset($_POST['companyname'])){
        $companyname = $_POST['companyname'];
    }
    if(isset($_POST['streetaddress'])){
        $streetaddress = $_POST['streetaddress'];
    }
    if(isset($_POST['city'])){
        $city = $_POST['city'];
    }
    if(isset($_POST['state'])){
        $state = $_POST['state'];
    }
    if(isset($_POST['zip'])){
        $zip = $_POST['zip'];
    }
}

if (isset($action) && ($action === 'register')) {
    $errormsg="";
    //check if username exists
    if(usernameExists($username, $password)){
        $errormsg = $errormsg."The username ($username) is already taken please choose another username.  ";
    }
    
    //check if companyname exits
    if(companyExists($companyname)){
        $errormsg = $errormsg."This company name ($companyname) is already taken please choose another company name.  ";
    }
    
    //check that passwords exist
    if(empty($password)){
        $errormsg = $errormsg."Please enter a password with length atleast 5 characters. ";
    }
    
    if(empty($repassword)){
        $errormsg = $errormsg."Please confirm your password. ";
    }
    
    if($password !== $repassword){
        $errormsg = $errormsg."Your passwords do not match.  ";
    }
    
    if(strlen($password) < 5){
        $errormsg = $errormsg."Your password must be at least 5 character in length.  ";
    }
    
    //check that passwords are equal
    
    if(empty($errormsg))
    {
        //write data to database
        $_SESSION['username'] = $username;
        $userid = createUser($username, $password);
        $companyid = createCompany($companyname, $userid, $streetaddress, $city, $state, $zip);
        exit("success");
    }
    
    exit($errormsg);
}

if(isset($action) && ($action === 'login')){
    if (empty($username))
    {
        exit('Username cannot be empty');
    }
    if (empty($password)) 
    {
        exit('Password cannot be empty');
    }
    
    if (usernameAndPasswordInDB($username, $password, $mysqli, $db))
    {
        $_SESSION['username'] = $username;
        exit('success');
    } else {
        exit('The username and password that you entered are not correct or you are not registerd');
    } 
}

exit('You have made an invalid request to the Server');

?>

