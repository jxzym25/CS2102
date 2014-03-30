<?php
	include "../include/common.inc.php";

	$currUser = getLoginUser();
	
	if($currUser->getValue("id") == -1)
		goToUrl("login.php");
	
	if($currUser->getValue("type") != 1 )
		die("You are not admin!");
	
	$start=isset($_GET["start"])?(int)$_GET["start"]:0;
	$order=isset($_GET["order"])?$_GET["order"]:"`id`";
	$search=isset($_GET["search"])?$_GET["search"]:"";
	
	$columntoshow=array("id"=>"ID",
						"name"=>"Name",
						"users" => "Number of Users",
						"posts" => "Number of Posts"	
				);
				
	list($groups,$totalRows)=Group::getGroups($start,PAGE_SIZE,$order, $search);

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
<table border="1" width="100%">
<?php

	echo "<tr class=\"controlrow\"><td colspan=\""
				.(count($columntoshow)+1)
				."\">";
?>
<a href="add_group.php">Add a new group</a>
<?php
	
	echo "</tr>";
	echo "<tr class=\"controlrow\"><td colspan=\""
				.(count($columntoshow)+1)
				."\">";
?>
<form action="" method="get">
Search by name: <input name="search" type="text" value="<?php echo $search; ?>"><input name="submit" type="submit">
</form>
<?php
	
	echo "</tr>";

	foreach($columntoshow as $key=>$title){
		echo "<th>";
		if($key != "users" && $key != "posts"){
			if ( $order != "`".$key."`" )
				echo "<a href=\"?order=`$key`\">";
			else
				echo "<a href=\"?order=`$key` DESC\">";
		}
		echo $title;
		if($key != "users" && $key != "posts")
			echo "</a>";
		echo "</th>";
	}
	echo "<th></th>";

	$rowCount = 0;
	foreach($groups as $group){
		
		$rowCount++;
		echo "<tr";
		if($rowCount%2 ==0)
			echo " class=\"alt\"";
		echo ">";
		foreach($columntoshow as $key=>$title){
			echo "<td>";
			if($key == "users"){
				list($users, $number) = $group->getUsers();
				echo $number;
			}
			elseif($key == "posts"){
				list($posts, $number) = $group->getPosts();
				echo $number;
			}
			else
				echo $group->getValue($key);
			echo "</td>";
		}
		echo "<td><a href=\"update_group.php?groupId=".$group->getValue("id")."\"><center>Update</center></a></td>";	
	}
	
	echo "<tr class=\"controlrow\"><td colspan=\""
				.(count($columntoshow)+1)
				."\">";
	
	if($start!=0){
		echo "<a href=\"?start=0&amp;order=$order&amp;search=$search\">First Page</a>";
		echo "&nbsp;&nbsp;";
	}
	if($start>0){
		echo "<a href=\"?start="
					.max($start-PAGE_SIZE,0)
					."&amp;order=$order&amp;search=$search\">Previous Page</a>";	
	}
	echo "&nbsp;&nbsp;";
	if($start+PAGE_SIZE < $totalRows){
		echo "<a href=\"?start="
					.min($start+PAGE_SIZE,$totalRows)
					."&amp;order=$order&amp;search=$search\">Next Page</a>";	
	}
	if($start<$totalRows-PAGE_SIZE){
		echo "&nbsp;&nbsp;";
		echo "<a href=\"?start=".($totalRows-PAGE_SIZE)."&amp;order=$order&amp;search=$search\">Last Page</a>";
	}

?>
</table>
</div>

</body>
</html>