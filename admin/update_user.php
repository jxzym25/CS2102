<?php
	include "../include/common.inc.php";

	$currUser = getLoginUser();
	
	if($currUser->getValue("id") == -1)
		goToUrl("login.php");
	
	if($currUser->getValue("type") != 1 )
		die("You are not admin!");
		
	$user = User::getUser($_GET["userId"]);
	
	if(isset($_GET["deleteId"]) and $_GET["deleteId"]){
		$member = Member::getMember($user->getValue("id"), $_GET["deleteId"]);
		if($member->getValue("groupId") != -1)
			$member->delete();	
	}
	
	if(isset($_POST["update"])){
			
			$city = City::getCity(addslashes($_POST["country"]), addslashes($_POST["state"]));
			
			if($_POST["password"] != "")
				$user = new User(array(
							"id" => $user->getValue("id"),
							"type" => $_POST["type"],
							"firstName" => $_POST["firstname"],
							"lastName" => $_POST["lastname"],
							"gender" => $_POST["gender"],
							"password" => password($_POST["password"]),
							"birthday" => storeDate($_POST["birthday"]),
							"cityId" => $city->getValue("id")
							));
			else
				$user = new User(array(
							"id" => $user->getValue("id"),
							"type" => $_POST["type"],
							"firstName" => $_POST["firstname"],
							"lastName" => $_POST["lastname"],
							"gender" => $_POST["gender"],
							"birthday" => storeDate($_POST["birthday"]),
							"cityId" => $city->getValue("id")
							));
			$user->update();
	}
	elseif(isset($_POST["delete"])){
		$user->delete();
		goToUrl("users.php");
	}
	
	list($groups, $totalRows, $type) = $user->getJoinedGroups();
	

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
    <td>Type</td>
    <td><select name="type">
    	<option value="1" <?php if($user->getValue("type") == "1") echo 'selected="selected"' ?>>Admin</option>
        <option value="2" <?php if($user->getValue("type") == "2") echo 'selected="selected"' ?>>Normal</option>
    	</select></td>
    </tr>
  <tr>
  <tr>
    <td>First Name</td>
    <td><input type="text" name="firstname" value="<?php echo $user->getValue("firstName"); ?>"></td>
    </tr>
  <tr>
    <td>Last Name</td>
    <td><input type="text" name="lastname" value="<?php echo $user->getValue("lastName"); ?>"></td>
    </tr>
  <tr>
    <td>Gender</td>
    <td><select name="gender">
      <option value="M" <?php if($user->getValue("gender") == "M") echo 'selected="selected"' ?>>M</option>
      <option value="F" <?php if($user->getValue("gender") == "F") echo 'selected="selected"' ?>>F</option>
      </select></td>
    </tr>
  <tr>
    <td>Password</td>
    <td><input type="password" name="password" autocomplete="off"></td>
    </tr>
  <tr>
    <td>Birthday</td>
    <td><input type="date" name="birthday" value="<?php echo $user->getValue("birthday"); ?>"></td>
    </tr>
  <tr>
    <td>Select Country:</td>
    <?php $city = City::getCityById($user->getValue("cityId")); ?>
    <td><select onchange="print_state('state',this.value);" id="country" name = "country"></select></td>
    </tr>
   <tr>
    <td>City/District/State:</td>
    <td><select name ="state" id = "state"></select>
    <script language="javascript">print_state("state",'<?php echo $city->getValue("country"); ?>','<?php echo $city->getValue("name"); ?>');</script>
<script language="javascript">print_country("country",'<?php echo $city->getValue("country"); ?>');</script></td>
    </tr>
  <tr>
    <td colspan="2"><input type="submit" name="update" value="Update">
    <input type="submit" name="delete" value="Delete"></td>
    </tr>
</table>

</form>
<?php
	if($totalRows > 0){
?>
<h2>User Joined Groups</h2>
<table border="1" width="100%">
<?php

	$columntoshow=array("id"=>"ID",
						"name"=>"Name",
						"type"=>"Type"
				);

	foreach($columntoshow as $key=>$title){
		echo "<th>";
		echo $title;
		echo "</th>";
	}
	echo "<th></th>";

	$rowCount = 0;
	foreach($groups as $group){
		
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
				echo $group->getValue($key);
			echo "</td>";
		}
		echo "<td><a href=\"?userId=".$_GET["userId"]."&amp;deleteId=".$group->getValue("id")."\"><center>Leave</center></a></td>";	
		
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