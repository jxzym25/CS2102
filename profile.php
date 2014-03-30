<?php
	include "include/common.inc.php";

	$currUser = getLoginUser();
	
	if($currUser->getValue("id") == -1)
		goToUrl("index.php");
		
	if(isset($_POST["update"])){
			
			$city = City::getCity(addslashes($_POST["country"]), addslashes($_POST["state"]));
			
			if($_POST["password"] != "")
				$user = new User(array(
							"id" => $currUser->getValue("id"),
							"type" => $currUser->getValue("type"),
							"firstName" => $_POST["firstname"],
							"lastName" => $_POST["lastname"],
							"gender" => $_POST["gender"],
							"password" => password($_POST["newPassword"]),
							"birthday" => storeDate($_POST["birthday"]),
							"cityId" => $city->getValue("id")
							));
			else
				$user = new User(array(
							"id" => $currUser->getValue("id"),
							"type" => $currUser->getValue("type"),
							"firstName" => $_POST["firstname"],
							"lastName" => $_POST["lastname"],
							"gender" => $_POST["gender"],
							"birthday" => storeDate($_POST["birthday"]),
							"cityId" => $city->getValue("id")
							));
			$user->update();
	}

	$currUser = getLoginUser();
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
<form action="search_group.php" method="post">
<input name="name" type="text" value="Search Group" onClick="this.value=''">
<input name="search" type="submit" value="Search">
</form>
</div>


<div id="menu">
	<ul>
    	<li><a href="index.php" onMouseOver="this.style.color='#FFF';" onMouseOut="this.style.color='#CCC';">Home</a></li>

        <li><a href="profile.php" id="currLi">My Profile</a></li>
        <li><a href="groups.php" onMouseOver="this.style.color='#FFF';" onMouseOut="this.style.color='#CCC';">Groups</a></li>
        <li><a href="logout.php" onMouseOver="this.style.color='#FFF';" onMouseOut="this.style.color='#CCC';">Logout</a></li>
    </ul>
</div>

<div id="wrapper">
<h2>Please Update Your Profile</h2>
<form action="" method="post" enctype="multipart/form-data" name="register">
<table>
  <tr>
    <th scope="row" width="40%" align="left" >First Name</th>
    <td width="60%"><input type="text" name="firstname" value="<?php echo $currUser->getValue("firstName"); ?>"></td>
    </tr>
  <tr>
    <th scope="row" align="left">Last Name</th>
    <td><input type="text" name="lastname" value="<?php echo $currUser->getValue("lastName"); ?>"></td>
    </tr>
  <tr>
    <th scope="row" align="left">Gender</th>
    <td><select name="gender">
      <option value="M" <?php if($currUser->getValue("gender") == "M") echo 'selected="selected"' ?>>M</option>
      <option value="F" <?php if($currUser->getValue("gender") == "F") echo 'selected="selected"' ?>>F</option>
      </select></td>
    </tr>
  <tr>
    <th scope="row" align="left">New Password</th>
    <td><input type="password" name="password" autocomplete="off"></td>
  </tr>
  <tr>
    <th scope="row" align="left">Birthday</th>
    <td><input type="date" name="birthday" value="<?php echo $currUser->getValue("birthday"); ?>"></td>
    </tr>
  <tr>
    <th scope="row" align="left">Select Country</th>
    <?php $city = City::getCityById($currUser->getValue("cityId")); ?>
    <td><select onchange="print_state('state',this.value);" id="country" name = "country"></select></td>
    </tr>
   <tr>
    <th scope="row" align="left">City/District/State</th>
    <td><select name ="state" id = "state"></select>
    <script language="javascript">print_state("state",'<?php echo $city->getValue("country"); ?>','<?php echo $city->getValue("name"); ?>');</script>
<script language="javascript">print_country("country",'<?php echo $city->getValue("country"); ?>');</script></td>
    </tr>
</table><br>

<center><input type="submit" name="update" value="Update"></center>

</form>
</div>
</body>
</html>