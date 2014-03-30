<?php
	include "include/common.inc.php";
	
	$currUser = getLoginUser();
	
	if($currUser->getValue("id") == -1)
		goToUrl("login.php");
	
	$errorMsg = "";
	
	if(isset($_POST["add"])){
			
			if($_POST["name"] == "")
				$errorMsg = "Please give your group a name.";
			
			$group = Group::getGroupByName($_POST["name"]);
			if($group->getValue("id") != -1)
				$errorMsg = "Existing name";
				
			if($errorMsg == ""){
				
				$currUser->createGroup($_POST["name"], htmlspecialchars_decode($_POST["description"]));
				goToUrl("groups.php");
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
<script src="include/ckeditor/ckeditor.js"></script>
</head>

<body>
<div id="header">
<img src="images/logo.png">&nbsp;Truly Black & White
<form action="search_group.php" method="post">
<input name="search" type="text" value="Search Group" onClick="this.value=''">
<input name="searchBtn" type="submit" value="Search">
</form>
</div>
<div id="menu">
	<ul>
    	<li><a href="index.php" onMouseOver="this.style.color='#FFF';" onMouseOut="this.style.color='#CCC';">Home</a></li>

        <li><a href="profile.php" onMouseOver="this.style.color='#FFF';" onMouseOut="this.style.color='#CCC';">My Profile</a></li>
        <li><a href="groups.php" onMouseOver="this.style.color='#FFF';" onMouseOut="this.style.color='#CCC';">Groups</a></li>
        <li><a href="logout.php" onMouseOver="this.style.color='#FFF';" onMouseOut="this.style.color='#CCC';">Logout</a></li>
    </ul>
</div>


<div id="wrapper">
<h2>Add a New Group</h2>
<div class="error"><?php echo $errorMsg; ?></div>
<form action="" method="post" enctype="multipart/form-data" name="register">
<table>
  <tr>
    <td>Name</td>
    <td><input type="text" name="name" size="550"></td>
    </tr>
  <tr>
    <td>Description</td>
    <td><textarea name="description" class="ckeditor" cols="80" rows="10"></textarea></td>
    </tr>
</table><br>

<center><input type="submit" name="add" value="Add a New Group"></center>

</form>
</div>

</body>
</html>