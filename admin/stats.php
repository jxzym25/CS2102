<?php
	include "../include/common.inc.php";

	$user = getLoginUser();
	
	if($user->getValue("id") == -1)
		goToUrl("login.php");
	
	if($user->getValue("type") != 1 )
		die("You are not admin!");
	

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Qe Administration Control Panel</title>
<link rel="stylesheet" type="text/css" href="main.css">
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
<center>
Welcome Adminstrator: <?php echo $user->getValue("userName"); ?>
</center>
</div>

</body>
</html>