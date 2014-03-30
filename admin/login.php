<?php
	include "../include/common.inc.php";

	$errorMsg = "";

	if(isset($_POST["submit"]) and $_POST["submit"]){
		
		$user = User::getUserByEmail($_POST["email"]);
		
		if($user->getValue("id") == -1)
			$errorMsg = "None existing User";
		else if($user->getValue("password") != password($_POST["password"]))
			$errorMsg = "Wrong Password";
		else{
			processLogin($user);
			goToUrl("index.php");
		}
	}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Login Page</title>
<link rel="stylesheet" type="text/css" href="main.css">
</head>

<body>
<div id="header">Qe Administration Control Panel</div>
<div id="main">
<div class="error"><?php echo $errorMsg; ?></div>
<form action="" method="post">
<table width="50%" border="1">
  <tr>
    <th colspan="2" scope="row">Login</th>
    </tr>
  <tr>
    <th scope="row">Email: </th>
    <td><input name="email" type="email"></td>
  </tr>
  <tr>
    <th scope="row">Password: </th>
    <td><input name="password" type="password"></td>
  </tr>
  <tr>
    <th scope="row">&nbsp;</th>
    <td><input type="submit" name="submit"></td>
  </tr>
</table>
</form>


</div>
</body>
</html>