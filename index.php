<?php
include('secureAuth.php');
require_once('config.php'); //it contains the connection variable. You may have a different file. For help check mysqli_connect
$secureAuthCheck = new SecureAuth($con_user);
$secureAuthCheck->loginRequired();

if($secureAuthCheck->loginRequiredVar){
    echo "Continue here";
    echo "<a href='logout.php'>Logout</a>";
}else{
    echo "Redirecting you to login now";
}