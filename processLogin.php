<?php
include('secureAuth.php');
require_once('config.php'); //it contains the connection variable. You may have a different file. For help check mysqli_connect
$secureAuthCheck = new SecureAuth($con_user);
if(!($secureAuthCheck->loginRequiredVar)){
    if(isset($_POST["submit"])){
        $username = $_POST["username"];
        $password = $_POST["password"];
        $secureAuthCheck->loginUser($username,$password);    
    }else{
        $secureAuthCheck->loggedInRedirect();
    }
}
