<?php
	include "include/common.inc.php";

	$currUser = getLoginUser();
	
	if($currUser->getValue("id") == -1)
		goToUrl("index.php");
		
	$user = User::getUser($_GET["userId"]);	
	

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
<form action="search_group.php" method="post">
<input name="name" type="text" value="Search Group" onClick="this.value=''">
<input name="search" type="submit" value="Search">
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
<h2><?php echo $user->getValue("userName");?> Profile</h2>
<table>
  <tr>
    <th scope="row" width="40%" align="left" >Type</th>
    <td width="60%"><?php  if($currUser->getValue("type") == "1") echo "Admin"; else echo "Normal User";  ?></td>
    </tr>
  <tr>
  <tr>
    <th scope="row" width="40%" align="left" >First Name</th>
    <td width="60%"><?php echo $user->getValue("firstName"); ?></td>
    </tr>
  <tr>
    <th scope="row" align="left">Last Name</th>
    <td><?php echo $user->getValue("lastName"); ?></td>
    </tr>
  <tr>
    <th scope="row" align="left">Gender</th>
    <td><?php if($currUser->getValue("gender") == "M") echo "Male"; else echo "Female"; ?></td>
    </tr>
  <tr>
    <th scope="row" align="left">Birthday</th>
    <td><?php echo extractDate($currUser->getValue("birthday")); ?></td>
    </tr>
  <tr>
    <th scope="row" align="left">Select Country</th>
    <?php $city = City::getCityById($currUser->getValue("cityId")); ?>
    <td><?php echo $city->getValue("country"); ?></td>
    </tr>
   <tr>
    <th scope="row" align="left">City/District/State</th>
    <td><?php echo $city->getValue("name"); ?></td>
    </tr>
</table>
</div>
</body>
</html>