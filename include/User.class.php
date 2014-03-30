<?php

	require_once "DataObject.class.php";
	
	class User extends DataObject{
		protected $data=array(
			"id"=>"",
			"type"=>"",
			"firstName"=>"",	
			"lastName"=>"",
			"userName"=>"",
			"gender"=>"",
			"email"=>"",	
			"password"=>"",
			"birthday"=>"",
			"cityId"=>"",
			"registered"=>"",
			"lastActivity"=>""		
		);
				
		public static function create(){
			$conn = parent::connect();
			$sql = "DROP TABLE IF EXISTS `".TBL_USERS."`";
			
			mysql_query($sql);
			
			$sql = "
					CREATE TABLE `".TBL_USERS."` (
					  `id` INT NOT NULL AUTO_INCREMENT,
					  `type` INT NOT NULL,
					  `firstName` VARCHAR(126) NOT NULL,
					  `lastName` VARCHAR(126) NOT NULL,
					  `userName` VARCHAR(126) UNIQUE NOT NULL,
					  `gender` VARCHAR(126) NOT NULL,
					  `password` VARCHAR(126) NOT NULL,
					  `email` VARCHAR(126) UNIQUE NOT NULL,
					  `birthday` DATE,
					  `cityId` INT,
					  `registered` DATETIME,
					  `lastActivity` DATETIME,
					  PRIMARY KEY (`id`),
					  FOREIGN KEY (`cityId`) REFERENCES `".TBL_CITIES."`(`id`) ON DELETE CASCADE
					);
			";
			mysql_query($sql);
			parent::disconnect($conn);
						
		}
		
		//Get the information of specific Entry
		public static function getUser($id){
			$conn = parent::connect();
			$sql = "SELECT * FROM ".TBL_USERS." WHERE `id`= ".$id;
			
			if($result = mysql_query($sql)){
				$row = mysql_fetch_assoc($result);
				if($row)return new User($row);
				else return new User(array("id" => -1));	
			}
			else{
				die("getUser Error:".mysql_error());
			}
			parent::disconnect($conn);
		}
		
		public static function getUserByUsername($username){
			$conn = parent::connect();
			$sql = "SELECT * FROM ".TBL_USERS." WHERE `userName`= '".$username."'";
			
			if($result = mysql_query($sql)){
				$row = mysql_fetch_assoc($result);
				if($row)return new User($row);
				else return new User(array("id" => -1));	
			}
			else{
				die("getUserByUsername Error:".mysql_error());
			}
			parent::disconnect($conn);
		}
		
		public static function getUserByEmail($email){
			$conn = parent::connect();
			$sql = "SELECT * FROM ".TBL_USERS." WHERE `email`= '".$email."'";
			
			if($result = mysql_query($sql)){
				$row = mysql_fetch_assoc($result);
				if($row)return new User($row);
				else return new User(array("id" => -1));	
			}
			else{
				die("getUserByEmail Error:".mysql_error());
			}
			parent::disconnect($conn);
			
		}
		
		//Insert new entry
		public function insert(){
			$conn=parent::connect();
			
			$sql= 'INSERT INTO '.TBL_USERS.' (
					`type`,
					`firstName`,	
					`lastName`,
					`userName`,
					`gender`,
					`email`,	
					`password`,
					`birthday`,
					`cityId`,
					`registered`,
					`lastActivity`
				) VALUES (
					'.$this->data["type"].',
					\''.$this->data["firstName"].'\',
					\''.$this->data["lastName"].'\',
					\''.$this->data["userName"].'\',
					\''.$this->data["gender"].'\',
					\''.$this->data["email"].'\',
					\''.$this->data["password"].'\',
					\''.$this->data["birthday"].'\',
					'.(($this->data["cityId"])?($this->data["cityId"]):(0)).',
					NOW(),
					NOW()
		           )';
			
			if(!mysql_query($sql)){
				die($sql." User insert Error:".mysql_error());
			}
			
			$this->data['id'] = mysql_insert_id();
			
			parent::disconnect($conn);
		}
		
		//Update existing entry
		public function update(){
			$conn=parent::connect();
			
			$passwordSql = "";
			
			if($this->data["password"] != "")
				$passwordSql = '`password` = \''.$this->data["password"].'\',';
			
			$sql= 'UPDATE '.TBL_USERS.' SET 
					`type` = '.$this->data["type"].',
					`firstName` = \''.$this->data["firstName"].'\',	
					`lastName` = \''.$this->data["lastName"].'\',
					`gender` = \''.$this->data["gender"].'\',
					'.$passwordSql.'
					`birthday` = \''.$this->data["birthday"].'\',
					`cityId` = '.$this->data["cityId"].'
				  WHERE `id` = '.$this->data["id"];

			if(!mysql_query($sql)){
				die("User update Error:".mysql_error());
			}
			parent::disconnect($conn);
		}
		
		//Update LastActivity
		public function updateLastActivity(){
			$conn=parent::connect();
			
			$sql= 'UPDATE '.TBL_USERS.' SET 
					`lastActivity` = NOW()
				  WHERE id = '.$this->data["id"];
			
			if(!mysql_query($sql)){
				die("User updateLastActivity Error:".mysql_error());
			}
			parent::disconnect($conn);
		}
		
		//Update Type
		public function updateType($type){
			$conn=parent::connect();
			
			$sql= 'UPDATE '.TBL_USERS.' SET 
					`type` = '.$this->data["type"].'
				  WHERE id = '.$this->data["id"];
			
			if(!mysql_query($sql)){
				die("User updateType Error:".mysql_error());
			}
			parent::disconnect($conn);
		}
		
		//Delete
		public function delete(){
			$conn=parent::connect();
			$sql='DELETE FROM '.TBL_USERS.' 
					WHERE `id`= '.$this->data["id"];
				
			if(!mysql_query($sql)){
				die("User delete Error:".mysql_error());
			}
			
			parent::disconnect($conn);
		}
		
		//Get all entries
		public static function getUsers($startRow = 0, $numRows = PAGE_SIZE, $order="", $username=""){
			$conn=parent::connect();
			if($order == "") $order = "`id` ASC";
			
			if($username!="")
				$sql="SELECT SQL_CALC_FOUND_ROWS * 
					FROM ".TBL_USERS."
					WHERE `userName` LIKE '%".$username."%'
					ORDER BY ".$order." 
					LIMIT ".$startRow." , ".$numRows;
			else
				$sql="SELECT SQL_CALC_FOUND_ROWS * 
					FROM ".TBL_USERS." 
					ORDER BY ".$order." 
					LIMIT ".$startRow." , ".$numRows;
						
			if($result = mysql_query($sql)){
				$users=array();
				
				while( $row = mysql_fetch_assoc($result)){
					$users[] = new User($row);	
				}
				if($st=mysql_query("SELECT found_rows() AS totalRows"))
					$row=mysql_fetch_assoc($st);
				else
					die("getUsers 2 Error:".mysql_error());
				
				parent::disconnect($conn);
				return array($users,$row["totalRows"]);
			}
			else{
				die("getUsers 1 Error:".mysql_error());
			}			
			
		}
		
		public function createGroup($name, $description){
			$newGroup = new Group(array(
							"name"=>$name,
							"description"=>$description
						));
			
			$newGroup->insert();
			$newMember = new Member(array(
							"userId"=>$this->data["id"],
							"groupId"=>$newGroup->getValue("id"),
							"type"=>1	
						));
			$newMember->insert();
		}
		
		public function joinGroup($groupId){
			
			$newMember = new Member(array(
							"userId"=>$this->data["id"],
							"groupId"=>$groupId,
							"type"=>2	
						));
			$newMember->insert();
		}
		
		public function checkGroup($groupId){
			
			$Member = Member::getMember($this->data["id"], $groupId);
			if($Member->getValue("userId") == -1 and $Member->getValue("groupId") == -1)
				return false;
			else
				return true;
		}
		
		public function getJoinedGroups($startRow = 0, $numRows = PAGE_SIZE, $order=""){
			return Member::getUserJoinedGroups($this->data["id"], $startRow, $numRows, $order);
		}
		
		public function getNewestPostFromUserJoinedGroups($startRow = 0, $numRows = PAGE_SIZE, $order=""){
			return Member::getNewestPostFromUserJoinedGroups($this->data["id"], $startRow, $numRows, $order);
		}
		
		public function getHotGroups($startRow = 0, $numRows = PAGE_SIZE){
			return Group::getHotGroups($this->data["id"], $startRow, $numRows);	
		}
		
		public function addPost($groupId, $title, $content){
			$Post = new Post(array(
							"authorId"=>$this->data["id"],
							"groupId"=>$groupId,
							"title"=>$title,
							"content"=>$content
						));
			$Post->insert();
		}
		
		public function likePost($postId){
			
			$like = Like::getLike($this->data["id"], $postId);
			if($like->getValue("userId")!= -1)
				return;
			
			$like = new Like(array(
								"userId"=>$this->data["id"],
								"postId"=>$postId
							));
			$like->insert();
		}
		
		public function unlikePost($postId){
			$like = Like::getLike($this->data["id"], $postId);
			if($like->getValue("userId")!= -1)
				$like->delete();
		}
		
		public function addComment($postId, $content){
			$comment = new Comment(array(
							"authorId"=>$this->data["id"],
							"parentPostId"=>$postId,
							"content"=>$content
						));
			$comment->insert();
		}
		
	}
?>