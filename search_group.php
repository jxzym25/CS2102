<?php
	include "include/common.inc.php";
	
	$currUser = getLoginUser();
	
	if($currUser->getValue("id") == -1)
		goToUrl("login.php");
	
	$start=isset($_GET["start"])?(int)$_GET["start"]:0;
	$order=isset($_GET["order"])?$_GET["order"]:"`id`";
	$search=isset($_GET["search"])?$_GET["search"]:"";
	
	function showGroup($group){
		
		list($tmp, $nUsers) = $group->getUsers();
		list($tmp, $nPosts) = $group->getPosts();
		
		echo "<tr>";
		echo "<td>";
		echo "<a href=\"group.php?groupId=".$group->getValue("id")."\">".$group->getValue("name")."</a><br>".$group->getValue("description");
		echo "</td>";
		echo "<td>";
		echo $nUsers;
		echo "</td>";
		echo "<td>";
		echo $nPosts;
		echo "</td>";
		echo "</tr>";	
	}
	
	list($groups,$totalRows)=Group::getGroups($start,PAGE_SIZE,$order, $search);

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
<form action="search_group.php" method="get">
<input name="search" type="text" value="Search Group" onClick="this.value=''">
<input type="submit" value="Search">
</form>
</div>
<div id="menu">
	<ul>
    	<li><a href="index.php" onMouseOver="this.style.color='#FFF';" onMouseOut="this.style.color='#CCC';">Home</a></li>

        <li><a href="profile.php" onMouseOver="this.style.color='#FFF';" onMouseOut="this.style.color='#CCC';">My Profile</a></li>
        <li><a href="groups.php" id="currLi">Groups</a></li>
        <li><a href="logout.php" onMouseOver="this.style.color='#FFF';" onMouseOut="this.style.color='#CCC';">Logout</a></li>
    </ul>
</div>

<div id="wrapper">
<table>
<?php
	echo "<h2>Keyword:   ".$search."</h2>";
	
	if($totalRows > 0){
		
		

		echo "<tr><th width=\"60%\">Name</th><th width=\"20%\">Num. of Users</th><th>Num. of Posts</th></tr>";
	
		$rowCount = 0;
		foreach($groups as $group){
			
			showGroup($group);
		}
		
	}
	else
		echo "<tr><th>No Result!</th></tr>";
	
?>
</table>
<?php
	
	if($totalRows >PAGE_SIZE){
		echo "<table class=\"npPages\"><tr><th>";
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
		echo "</th></tr></table>";
	}
?>
</div>

</body>
</html>