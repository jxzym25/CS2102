<?php
	include "include/common.inc.php";
	
	$currUser = getLoginUser();
	
	if($currUser->getValue("id") == -1)
		goToUrl("login.php");
	
	$start=isset($_GET["start"])?(int)$_GET["start"]:0;
	$order=isset($_GET["order"])?$_GET["order"]:"`id` DESC";
	
	$postId=$_GET["postId"];
	
	function showComment($comment, $user, $start, $order){
		$author = User::getUser($comment->getValue("authorId"));
		$post = Post::getPost($comment->getValue("parentPostId"));
		echo "<tr>";
		echo "<td>";
		echo $comment->getValue("content");
		echo "</td>";
		
		echo "<td>";
		if($user->getValue("id") == $author->getValue("id"))
			echo "<a href=\"?postId=".$post->getValue("id")."&delete=".$comment->getValue("id")."&start=$start&amp;order=$order\">Delete</a><br>";
		echo "By <a href=\"user.php?userId=".$comment->getValue("authorId")."\">".$author->getValue("userName")."</a><br>".$comment->getValue("timestamp")."</td>";
		echo "</tr>";
	}
	
	$post=Post::getPost($postId);
	
	if(isset($_POST["submit"])){
		$currUser->addComment($postId,htmlspecialchars_decode($_POST["content"]));
	}
	
	if(isset($_POST["likeBtn"])){
		if($_POST["likeBtn"]==="Like")
		$currUser->likePost($postId);
		else $currUser->unlikePost($postId);
	}
	
	if(isset($_POST["delete"])){
		$author = User::getUser($post->getValue("authorId"));
		$groupId = $post->getValue("groupId");
		if($currUser->getValue("id") == $author->getValue("id")){
			$post->delete();
			goToUrl("group.php?groupId=".$groupId);
		}
	}
	
	if(isset($_GET["delete"])){
		$comment = Comment::getComment($_GET["delete"]);
		$author = User::getUser($comment->getValue("authorId"));
		if($currUser->getValue("id") == $author->getValue("id")){
			$comment->delete();
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
<input name="name" type="text" value="Search Group" onClick="this.value=''">
<input name="search" type="submit" value="Search">
</form>
<p>&nbsp;</p>
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
<form action="post.php?postId=<?php echo $postId?>" method="POST" name="like">
<?php
	$author = User::getUser($post->getValue("authorId"));
	echo "<tr>";
	echo "<td width=\"70%\"><a href=\"post.php?postId=".$post->getValue("id")."\">".$post->getValue("title")."</a></td>";
	echo "<td width=\"20%\" rowspan=\"2\">By <a href=\"user.php?userId=".$post->getValue("authorId")."\">".$author->getValue("userName")."</a><br>".$post->getValue("timestamp")."</td>";
	
	$like=Like::getLike($currUser->getValue("id"), $postId);
	echo "<td rowspan=\"2\"><input type=\"submit\" name=\"likeBtn\" value = \""; 
	if($like->getValue("userId")==-1)
		echo "Like";
	else 
		echo "Unlike";
	echo "\">";
	echo "<input type=\"submit\" name=\"delete\" value = \"Delete\">";
	echo "</td>";
	
	echo "</tr>";
	echo "<tr>";
	echo "<td>".$post->getValue("content")."</td>";
	
	echo "</tr>";
?>
</table>

<table>
<?php 
	list($comments, $totalRows) = $post->getComments($start,PAGE_SIZE,$order);
	
	if($totalRows > 0){
		echo "<h2>Comments</h2>";
		echo "<tr><th width=\"70%\">Content</th><th>Author</th></tr>";
	
		foreach($comments as $comment){
			
			showComment($comment, $currUser, $start, $order);
		}		
	}
?>
</table>
<?php

	if($totalRows >PAGE_SIZE){
		echo "<table class=\"npPages\"><tr><th>";
		if($start!=0){
			echo "<a href=\"?postId=".$post->getValue("id")."&start=0&amp;order=$order\">First Page</a>";
			echo "&nbsp;&nbsp;";
		}
		if($start>0){
			echo "<a href=\"?postId=".$post->getValue("id")."&start="
						.max($start-PAGE_SIZE,0)
						."&amp;order=$order\">Previous Page</a>";	
		}
		echo "&nbsp;&nbsp;";
		if($start+PAGE_SIZE < $totalRows){
			echo "<a href=\"?postId=".$post->getValue("id")."&start="
						.min($start+PAGE_SIZE,$totalRows)
						."&amp;order=$order\">Next Page</a>";	
		}
		if($start<$totalRows-PAGE_SIZE){
			echo "&nbsp;&nbsp;";
			echo "<a href=\"?postId=".$post->getValue("id")."&start=".($totalRows-PAGE_SIZE)."&amp;order=$order\">Last Page</a>";
		}
		echo "</th></tr></table>";
	}
?>


<h2>Add New Comment</h2>
<form action="" method="post" enctype="multipart/form-data" name="createPost">
<table width="300" border="0">
  <tr>
    <td>Content</td>
    <td><textarea name="content"  class="ckeditor" cols="80" rows="10"></textarea></td>
    </tr>
</table><br>
<center><input type="submit" name="submit"></center>

</form>

</div>






</body>
</html>