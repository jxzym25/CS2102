<?php
	include "include/common.inc.php";

	$user = getLoginUser();
	
	$start=isset($_GET["start"])?(int)$_GET["start"]:0;
	$order=isset($_GET["order"])?$_GET["order"]:"`id` DESC";

	function showPost($post){
		$group = Group::getGroup($post->getValue("groupId"));
		$author = User::getUser($post->getValue("authorId"));
		echo "<tr>";
		echo "<td><a href=\"post.php?postId=".$post->getValue("id")."\">".$post->getValue("title")."</a></td>";
		echo "<th><a href=\"group.php?groupId=".$post->getValue("groupId")."\">".$group->getValue("name")."</a></th>";
		echo "<td>By <a href=\"user.php?userId=".$post->getValue("authorId")."\">".$author->getValue("userName")."</a><br>".$post->getValue("timestamp")."</td>";
		echo "</tr>";
	}

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
<?php if($user->getValue("id") == -1){?>
<form action="login.php" method="post">
<input name="email" type="email" value="Email" onClick="this.value=''">
<input name="password" type="text" value="Password" onClick="this.type='password'; this.value=''">
<input type="hidden" name="submit" value="1">
<input name="login" type="submit" value="Login">
<input name="register" type="button" value="Register" onClick="window.location = 'register.php'">
</form>
<?php }else{?>
<form action="search_group.php" method="post">
<input name="name" type="text" value="Search Group" onClick="this.value=''">
<input name="search" type="submit" value="Search">
</form>
<?php } ?>
</div>
<div id="menu">
	<ul>
    	<li><a href="index.php"  id="currLi">Home</a></li>
<?php if($user->getValue("id") != -1){?>
        <li><a href="profile.php" onMouseOver="this.style.color='#FFF';" onMouseOut="this.style.color='#CCC';">My Profile</a></li>
        <li><a href="groups.php" onMouseOver="this.style.color='#FFF';" onMouseOut="this.style.color='#CCC';">Groups</a></li>
        <li><a href="logout.php" onMouseOver="this.style.color='#FFF';" onMouseOut="this.style.color='#CCC';">Logout</a></li>
<?php }else{ ?>
		<li><a href="login.php" onMouseOver="this.style.color='#FFF';" onMouseOut="this.style.color='#CCC';">Login</a></li>
        <li><a href="register.php" onMouseOver="this.style.color='#FFF';" onMouseOut="this.style.color='#CCC';">Register</a></li>
<?php } ?>
    </ul>
</div>

<div id="wrapper">
<?php if($user->getValue("id") != -1){ ?>
<p><center>Welcome <?php echo $user->getValue("userName"); ?>.</center></p>
<h2>Newest Posts</h2>
<table>
<tr><th width="60%">Title</th><th width="20%">Group</th><th>Author</th></tr>
<?php
	list($posts, $totalRows)=$user->getNewestPostFromUserJoinedGroups($start,PAGE_SIZE,$order);
	
	if($totalRows > 0){

		foreach($posts as $post)
			showPost($post);
	}
	else{
		echo "<tr><th colspan=\"3\">No New Post! </th></tr>";	
	}
?>
</table>
<?php

	if($totalRows > PAGE_SIZE){
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

<?php }else{ ?>
	<p></p>
    <p></p>
	<h2>Welcome to Qe, A Truly Black & White Forum.</h2>
    <center><img src="images/main.jpg"></center>
<?php } ?>
<?php  
	list($tmp, $nUsers) = User::getUsers();
	list($tmp, $nGroups) = Group::getGroups();
	list($tmp, $nPosts) = Post::getPosts();
	
?>
    <p><center>Currently we have <?php echo $nUsers; ?> users, <?php echo $nGroups; ?> groups and <?php echo $nPosts; ?> posts.</center></p>
</div>

</body>
</html>