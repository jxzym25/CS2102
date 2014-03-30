<?php
	include "../include/common.inc.php";

	$currUser = getLoginUser();
	
	if($currUser->getValue("id") == -1)
		goToUrl("login.php");
	
	if($currUser->getValue("type") != 1 )
		die("You are not admin!");
	
	$errorMsg = "";
	
	if(isset($_POST["add"])){
			
			$group = Group::getGroupByName($_POST["name"]);
			if($group->getValue("id") != -1)
				$errorMsg = "Existing name";
				
			if($errorMsg == ""){
				
				$currUser->createGroup($_POST["name"], $_POST["description"]);
				goToUrl("groups.php");
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
    <td>Name</td>
    <td><input type="text" name="name"></td>
    </tr>
  <tr>
    <td>Description</td>
    <td><textarea name="description" cols="" rows=""></textarea></td>
    </tr>
  <tr>
    <td colspan="2"><input type="submit" name="add" value="Add"></td>
    </tr>
</table>

</form>

</div>

</body>
</html>