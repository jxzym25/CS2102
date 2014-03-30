<?php

	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	require_once( "config.inc.php" );
	require_once( "Cities.class.php" );
	require_once( "User.class.php" );
	require_once( "Group.class.php" );
	require_once( "Member.class.php" );
	require_once( "Post.class.php" );
	require_once( "Comment.class.php" );
	require_once( "Like.class.php" );
	
	//include_once( "fckeditor/fckeditor.php" );
	//include_once( "ckeditor/ckeditor.php" );
	
	function goToUrl($var="") {
			echo @header('Location: '.$var);
			echo '<script type="text/javascript">
				<!--
				window.location = "'.$var.'"
				//-->
				</script>
				';
			echo 'Please enable Javascript in your browser setting!';
			exit();
	}
	
	function password( $string){
		return md5(md5($string).HASH_STR);	
	}
	
	function processLogin($user){
		setcookie("username",$user->getValueEncoded("userName"),(time()+604800));
		setcookie("password",$user->getValueEncoded("password"),(time()+604800));	
	}
	
	function processLogout(){
		setcookie("username","",(time()-3600));
		setcookie("password","",(time()-3600));
	}
	
	
	function getLoginUser(){
		if(isset($_COOKIE["username"]))
			return User::getUserByUsername($_COOKIE["username"]);
		else
			return new User(array("id"=>-1));
	}
	
	function storeDate($dateString){
		return date('Y-m-d', strtotime($dateString));
	}
	
	function extractDate($dateString){
		return date('d/m/Y', strtotime($dateString));
	}
	
	function storeDateTime($dateString){
		return date('Y-m-d H:i:s', strtotime($dateString));
	}
	
	function extractDateTime($dateString){
		return date('d/m/Y H:i:s', strtotime($dateString));
	}
	
	function nukeMagicQuotes() {
			function htmlspecialchars_deep($value) {
				$value = is_array($value) ? array_map('htmlspecialchars_deep', $value) : htmlspecialchars($value, ENT_QUOTES);
				return $value;
			}
			$_POST = array_map('htmlspecialchars_deep', $_POST);
			$_GET = array_map('htmlspecialchars_deep', $_GET);
			$_COOKIE = array_map('htmlspecialchars_deep', $_COOKIE);
	}
	nukeMagicQuotes();
	
?>