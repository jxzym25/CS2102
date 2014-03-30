<?php
	include "../include/common.inc.php";

	$currUser = getLoginUser();
	
	if($currUser->getValue("id") == -1)
		goToUrl("login.php");
	
	if($currUser->getValue("type") != 1 )
		die("You are not admin!");
	
	$errorMsg =  "";
	
	if(isset($_POST["add"])){
			
			$user = User::getUserByEmail($_POST["email"]);
			if($user->getValue("id") == -1){
				$user = User::getUserByUsername($_POST["username"]);
				if($user->getValue("id") != -1)
					$errorMsg = "Existing username";
			}
			else
				$errorMsg = "Existing email";
				
			if($errorMsg == ""){
				$city = City::getCity(addslashes($_POST["country"]), addslashes($_POST["state"]));
			
				$user = new User(array(
							"type" => $_POST["type"],
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
				goToUrl("users.php");
			}
	}
	
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Qe Administration Control Panel</title>
<link rel="stylesheet" type="text/css" href="main.css">
<script type= "text/javascript" src = "../js/countries2.js"></script>
</head>

<body>
<div id="header">Qe Administration Control Panel</div>
<div id="menu">
	<ul>
    	<li><a href="users.php">Users</a></li>
        <li><a href="groups.php">Groups</a></li>
        <li><a href="stats.php">Statistic</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>
<div id="main">
<div class="error"><?php echo $errorMsg; ?></div>
<form action="" method="post" enctype="multipart/form-data" name="register">
<table width="300" border="0">
  <tr>
    <td>Type</td>
    <td><select name="type">
    	<option value="1">Admin</option>
        <option value="2" selected="selected">Normal</option>
    	</select></td>
    </tr>
  <tr>
  <tr>
    <td>First Name</td>
    <td><input type="text" name="firstname"></td>
    </tr>
  <tr>
    <td>Last Name</td>
    <td><input type="text" name="lastname"></td>
    </tr>
  <tr>
    <td>User Name</td>
    <td><input type="text" name="username"></td>
    </tr>
  <tr>
    <td>Gender</td>
    <td><select name="gender">
      <option value="M">M</option>
      <option value="F">F</option>
      </select></td>
    </tr>
  <tr>
    <td>Email</td>
    <td><input type="email" name="email" autocomplete="off"></td>
    </tr>
  <tr>
    <td>Password</td>
    <td><input type="password" name="password" autocomplete="off"></td>
    </tr>
  <tr>
    <td>Birthday</td>
    <td><input type="date" name="birthday"></td>
    </tr>
  <tr>
    <td>Select Country:</td>
    <td><select onchange="print_state('state',this.value);" id="country" name = "country"></select></td>
    </tr>
   <tr>
    <td>City/District/State:</td>
    <td><select name ="state" id = "state"></select>
<script language="javascript">print_country("country");</script></td>
    </tr>
  <tr>
    <td colspan="2"><input type="submit" name="add" value="Add"></td>
    </tr>
</table>

</form>
</div>

</body>
</html>