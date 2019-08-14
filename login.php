<?php
include('secureAuth.php');
require_once('config.php'); //it contains the connection variable. You may have a different file. For help check mysqli_connect
$secureAuthCheck = new SecureAuth($con_user);

if(!($secureAuthCheck->loginRequiredVar)){
?>
<!--HTML GOES HERE-->
<!DOCTYPE html>
<html>
<head>
<title>Sign In</title>
</head>
<body>
<form action="processLogin.php" method="POST">
<label for="username">username:</label><input type="email" name="username" required>
<label for="password">password:</label><input type="password" name="password" required>
<button type="submit" name="submit" value="login">Login</button>
</form>
</body>
</html>
<!--HTML ENDS HERE-->
<?php
}else{
    header('Location: index.php');
}
?>