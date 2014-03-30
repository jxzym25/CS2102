<?php
	include "common.inc.php";

	if(isset($_POST["submit"]) and $_POST["submit"]){
		
		if(isset($_POST["cleanInstall"])){
			City::create();
			User::create();
			Group::create();
			
			Post::create();
			Comment::create();
			Like::create();
			Member::create();
		}
		if(isset($_POST["addAdmin"])){
			
			$city = City::getCity(addslashes($_POST["country"]), addslashes($_POST["state"]));
			
			$user = new User(array(
						"type" => 1,
						"firstName" => $_POST["firstname"],
						"lastName" => $_POST["lastname"],
						"userName" => $_POST["username"],
						"gender" => $_POST["gender"],
						"email" => $_POST["email"],
						"password" => password($_POST["password"]),
						"birthday" => storeDate($_POST["birthday"]),
						"cityId" => $city->getValue("id")
						));
			$user->insert();
			echo "<h1>DONE!</h1>";
			die();
		}

	}
	
	
	
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title> Installation Page</title>
<script type= "text/javascript" src = "../js/countries2.js"></script>
</head>

<body>
<h1>Welcome to the Qe Installation Page</h1>
<?php
	if(isset($_POST["submit"]) and $_POST["submit"] and isset($_POST["cleanInstall"])){
?>

<form action="" method="post" enctype="multipart/form-data" name="form">
<table width="500" border="0" style="margin:auto">
  <tr>
    <td colspan="2">Please key in a new administrator:</td>
  </tr>
  <tr>
    <td>First Name</td>
    <td><input type="text" name="firstname"></td>
    </tr>
  <tr>
    <td>Last Name</td>
    <td><input type="text" name="lastname"></td>
    </tr>
  <tr>
    <td>User Name</td>
    <td><input type="text" name="username"></td>
    </tr>
  <tr>
    <td>Gender</td>
    <td><select name="gender">
      <option value="M">M</option>
      <option value="F">F</option>
      &nbsp;</select></td>
    </tr>
  <tr>
    <td>Email</td>
    <td><input type="email" name="email"></td>
    </tr>
  <tr>
    <td>Password</td>
    <td><input type="password" name="password"></td>
    </tr>
  <tr>
    <td>Birthday</td>
    <td><input type="date" name="birthday"></td>
    </tr>
  <tr>
    <td>Select Country:</td>
    <td><select onchange="print_state('state',this.value);" id="country" name = "country"></select></td>
    </tr>
   <tr>
    <td>City/District/State:</td>
    <td><select name ="state" id = "state"></select>
<script language="javascript">print_country("country");</script></td>
    </tr>
  <tr>
    <td colspan="2"><input type="hidden" name="addAdmin" id="addAdmin">
    <input type="submit" name="submit" id="submit" value="Submit"></td>
    </tr>
</table>
</form>
<?php }else{ ?>
<form action="" method="post" enctype="multipart/form-data" name="form">
<table width="500" border="0" style="margin:auto">
  <tr>
    <td>Start a clean installation
      <input type="hidden" name="cleanInstall" id="cleanInstall"></td>
  </tr>
  <tr>
    <td><input type="submit" name="submit" id="submit" value="Submit"></td>
  </tr>
</table>
</form>
<?php } ?>




</body>
</html>