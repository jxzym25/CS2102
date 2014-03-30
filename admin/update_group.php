<?php
	include "../include/common.inc.php";

	$currUser = getLoginUser();
	
	if($currUser->getValue("id") == -1)
		goToUrl("login.php");
	
	if($currUser->getValue("type") != 1 )
		die("You are not admin!");
		
	$start=isset($_GET["start"])?(int)$_GET["start"]:0;
	$order=isset($_GET["order"])?$_GET["order"]:"`id`";
	
	$group = Group::getGroup($_GET["groupId"]);
	
	if(isset($_GET["deleteId"]) and $_GET["deleteId"]){
		$member = Member::getMember($_GET["deleteId"], $group->getValue("id"));
		if($member->getValue("userId") != -1)
			$member->delete();	
	}
	
	if(isset($_POST["update"])){
			
			$group = new Group(array(
							"id" =>$group->getValue("id"),
							"name" =>$group->getValue("name"),
							"description" => $_POST["description"]
							));
				
			$group->update();
	}
	elseif(isset($_POST["delete"])){
		$group->delete();
		goToUrl("groups.php");
	}
	
	
	
	list($users, $totalRows, $type) = $group->getUsers();
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
<form action="" method="post" enctype="multipart/form-data" name="register">
<table width="300" border="0">
  <tr>
    <td>Name</td>
    <td><?php echo $group->getValue("name"); ?></td>
    </tr>
  <tr>
    <td>Description</td>
    <td><textarea name="description" cols="" rows=""><?php echo $group->getValue("description"); ?></textarea></td>
    </tr>
  <tr>
    <td colspan="2"><input type="submit" name="update" value="Update">
    <input type="submit" name="delete" value="Delete"></td>
    </tr>
</table>

</form>
<?php 
	if($totalRows > 0) {
?>
<h2>Joined Users</h2>
<table border="1" width="100%">
<?php

	$columntoshow=array("id"=>"ID",
						"type"=>"Type",
						"userName"=>"Username",
						"email"=>"Email"
				);

	foreach($columntoshow as $key=>$title){
		echo "<th>";
		echo $title;
		echo "</th>";
	}
	echo "<th></th>";

	$rowCount = 0;
	foreach($users as $user){
		
		echo "<tr";
		if($rowCount%2 ==1)
			echo " class=\"alt\"";
		echo ">";
		foreach($columntoshow as $key=>$title){
			echo "<td>";
			if($key == "type"){
				if($type[$rowCount]==1)
					echo "Manager";
				else
					echo "Member";
			}
			else
				echo $user->getValue($key);
			echo "</td>";
		}
		echo "<td><a href=\"?groupId=".$_GET["groupId"]."&amp;deleteId=".$user->getValue("id")."\"><center>Kick</center></a></td>";	
		
		$rowCount++;
	}
	
	echo "<tr class=\"controlrow\"><td colspan=\""
				.(count($columntoshow)+1)
				."\">";
	
	if($start!=0){
		echo "<a href=\"?start=0&amp;order=$order\">First Page</a>";
		echo "&nbsp;&nbsp;";
	}
	if($start>0){
		echo "<a href=\"?start="
					.max($start-PAGE_SIZE,0)
					."&amp;order=$order\">Previous Page</a>";	
	}
	echo "&nbsp;&nbsp;";
	if($start+PAGE_SIZE < $totalRows){
		echo "<a href=\"?start="
					.min($start+PAGE_SIZE,$totalRows)
					."&amp;order=$order\">Next Page</a>";	
	}
	if($start<$totalRows-PAGE_SIZE){
		echo "&nbsp;&nbsp;";
		echo "<a href=\"?start=".($totalRows-PAGE_SIZE)."&amp;order=$order\">Last Page</a>";
	}

?>
</table>
<?php } ?>
</div>

</body>
</html>