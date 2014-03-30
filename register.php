<?php
	include "include/common.inc.php";

	$errorMsg = "";
	
	if(isset($_POST["submit"]) and $_POST["submit"]){
	
		$user = User::getUserByEmail($_POST["email"]);
	
		if($_POST["username"]=="")
			$errorMsg="Enter User Name Please!";
		else if($_POST["email"]=="")
			$errorMsg="Enter Email Address Please!";
		else if($_POST["password"]=="")
			$errorMsg="Enter Password Please!";
		else if($user->getValue("id") != -1)
			$errorMsg = "User Existed!";
		else{	
			$city = City::getCity(addslashes($_POST["country"]), addslashes($_POST["state"]));
		
			$user = new User(array(
					"type" => 2,
					"firstName" => $_POST["firstname"],
					"lastName" => $_POST["lastname"],
					"userName" => $_POST["username"],
					"gender" => $_POST["gender"],
					"email" => $_POST["email"],
					"password" => password($_POST["password"]),
					"birthday" => storeDate($_POST["birthday"]),
					"cityId" => $city->getValue("id")
			));
			$user->insert();
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
<script type= "text/javascript" src = "js/countries2.js"></script>
</head>

<body>
<div id="header">
<img src="images/logo.png">&nbsp;Truly Black & White
</div>
<div id="menu">
<ul>
	<li><a href="index.php" onMouseOver="this.style.color='#FFF';" onMouseOut="this.style.color='#CCC';">Home</a></li>
	<li><a href="login.php" onMouseOver="this.style.color='#FFF';" onMouseOut="this.style.color='#CCC';">Login</a></li>
    <li><a href="register.php" id="currLi">Register</a></li>
</ul>
</div>

<div id="wrapper">
<div class="error"><?php echo $errorMsg; ?></div>
<form action="" method="post">
<table cellpadding="0" cellspacing="0" width="50%">
  <tr>
    <th scope="row">First Name: </th>
    <td><input name="firstname" type="text"></td>
  </tr>
  <tr>
    <th scope="row">Last Name: </th>
    <td><input name="lastname" type="text"></td>
  </tr>
  <tr>
    <th scope="row">User Name: </th>
    <td><input type="text" name="username"></td>
    </tr>
  <tr>
    <th scope="row">Gender: </th>
    <td><select name="gender">
      <option value="M">M</option>
      <option value="F">F</option>
      &nbsp;</select></td>
    </tr>
  <tr>
    <th scope="row">Email: </th>
    <td><input type="email" name="email"></td>
    </tr>
  <tr>
    <th scope="row">Password: </th>
    <td><input type="password" name="password"></td>
    </tr>
  <tr>
    <th scope="row">Birthday: </th>
    <td><input type="date" name="birthday"></td>
    </tr>
  <tr>
    <th scope="row">Country: </th>
    <td><select onchange="print_state('state',this.value);" id="country" name = "country"></select></td>
    </tr>
   <tr>
    <th scope="row">City: </th>
    <td><select name ="state" id = "state"></select>
<script language="javascript">print_country("country");</script></td>
    </tr>
  <tr>
    <th colspan="2" scope="row"><input type="submit" name="submit"></th>
  </tr>
</table>
</form>
</div>

</body>
</html>