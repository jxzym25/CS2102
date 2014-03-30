<?php
	include "include/common.inc.php";
	$errorMsg="";
	
	$currUser = getLoginUser();
	
	if($currUser->getValue("id") == -1)
		goToUrl("login.php");


	$start=isset($_GET["start"])?(int)$_GET["start"]:0;
	$order=isset($_GET["order"])?$_GET["order"]:"`id` DESC";
	$groupId = $_GET["groupId"];
	
	$group = Group::getGroup($groupId);
	
	if(isset($_POST["post"])){
     if($_POST["title"]!="" and $_POST["content"]!="")
     {
	   $post = new Post(array(
					"authorId" => $currUser->getValue("id"),
					"groupId" => $groupId,
					"title" => $_POST["title"],
					"content" => htmlspecialchars_decode($_POST["content"]),
			)); 
		$post->insert();
	 }
     else
	 {
		$errorMsg="Title or content cannot be empty!";
	 }
	   
   	}
	if(isset($_POST["groupLJ"])){
		if($currUser->checkGroup($groupId)){
			Member::getMember($currUser->getValue("id"),$groupId)->delete();
			goToUrl("groups.php");
		}
		else{
			$currUser->joinGroup($groupId);
		}
   	}
	
	if(isset($_POST["Delete"])){
		$member = Member::getMember($currUser->getValue("id"),$groupId);
		if($member->getValue("type")==1){
			$group->delete();
			goToUrl("groups.php");
		}
		
	}
	
	function showPost($post){
		$author = User::getUser($post->getValue("authorId"));
		echo "<tr>";
		echo "<td><a href=\"post.php?postId=".$post->getValue("id")."\">".$post->getValue("title")."</a></td>";
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
<script src="include/ckeditor/ckeditor.js"></script>
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
<form action="" method="post">
<div id="wrapper">
<table cellpadding="0" cellspacing="0" width="50%">
  <tr>
   	<th width="31%">Group Name: </th>
    <td width="69%" align="center"><?php echo $group->getValue("name") ?>
    <td rowspan="2"><input type="submit" name="groupLJ" value =" <?php 
		if($currUser->checkGroup($groupId)){
			echo "Leave";
		}
		else 
			echo "Join";
	?>">
    <?php
	$member = Member::getMember($currUser->getValue("id"),$groupId);
	if($member->getValue("type")==1)
   		echo '<input type="submit" name="delete" value ="Delete">';
	
	?></td>
   </td>
  </tr>
  <tr>
    <th>Discription: </th>
    <td align="center"><?php echo $group->getValue("description") ?></td>
  	
  </tr>
	
</table>


</div>
</form>
<?php if($currUser->checkGroup($groupId)){?>
<div id="wrapper">
<div class="error"><?php echo $errorMsg; ?></div>
<form action="" method="post">
<table cellpadding="0" cellspacing="0" width="80%" >
  <tr>
    <th colspan="2">Add New Post</th>
    </tr>
  <tr>
    <th scope="row">Title</th>
    <td><input type="text" name="title" size="550"></td>
  </tr>
  <tr>
    <th scope="row" height="40">Content</th>
    <td><textarea name="content" class="ckeditor" cols="80" rows="10"></textarea></td>
  </tr>
</table>
<center><input type="submit" name="post" value="Post"></center>

</form>
</div>
<?php }?>
<div id="wrapper">
<table>
<?php 

	list($posts, $totalRows)=Post::getPostsInAGroup($groupId,$start,PAGE_SIZE,$order);
	
	if($totalRows > 0){

		echo "<tr><th width=\"70%\">Title</th><th>Author</th></tr>";
	
		foreach($posts as $post){
			
			showPost($post);
		}
		
	}
?>
</table>
<?php
	if($totalRows >PAGE_SIZE){
		echo "<table class=\"npPages\"><tr><th>";
		if($start!=0){
			echo "<a href=\"?groupId=".$group->getValue("id")."&start=0&amp;order=$order\">First Page</a>";
			echo "&nbsp;&nbsp;";
		}
		if($start>0){
			echo "<a href=\"?groupId=".$group->getValue("id")."&start="
						.max($start-PAGE_SIZE,0)
						."&amp;order=$order\">Previous Page</a>";	
		}
		echo "&nbsp;&nbsp;";
		if($start+PAGE_SIZE < $totalRows){
			echo "<a href=\"?groupId=".$group->getValue("id")."&start="
						.min($start+PAGE_SIZE,$totalRows)
						."&amp;order=$order\">Next Page</a>";	
		}
		if($start<$totalRows-PAGE_SIZE){
			echo "&nbsp;&nbsp;";
			echo "<a href=\"?groupId=".$group->getValue("id")."&start=".($totalRows-PAGE_SIZE)."&amp;order=$order\">Last Page</a>";
		}
		echo "</th></tr></table>";
	}
?>

</div>

</body>
</html>