<?php
	include "include/common.inc.php";

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
<title>Qe: Truly Black & White</title>
<link rel="stylesheet" type="text/css" href="main.css">
<link rel="shortcut icon" type="image/x-icon" href="images/icon.ico">
</head>

<body>
<div id="header">
<img src="images/logo.png">&nbsp;Truly Black & White
</div>
<div id="menu">
<ul>
	<li><a href="index.php" onMouseOver="this.style.color='#FFF';" onMouseOut="this.style.color='#CCC';">Home</a></li>
	<li><a href="login.php" id="currLi">Login</a></li>
    <li><a href="register.php" onMouseOver="this.style.color='#FFF';" onMouseOut="this.style.color='#CCC';">Register</a></li>
</ul>
</div>

<div id="wrapper">
<div class="error"><?php echo $errorMsg; ?></div>
<form action="" method="post">
<table cellpadding="0" cellspacing="0" width="50%">
  <tr>
    <th scope="row">Email: </th>
    <td><input name="email" type="email"></td>
  </tr>
  <tr>
    <th scope="row">Password: </th>
    <td><input name="password" type="password"></td>
  </tr>
  <tr>
    <th colspan="2" scope="row"><input type="submit" name="submit"></th>
  </tr>
</table>
</form>
</div>

</body>
</html>