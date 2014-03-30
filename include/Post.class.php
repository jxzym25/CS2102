<?php

	require_once "DataObject.class.php";
	
	class Post extends DataObject{
		protected $data=array(
			"id"=>"",
			"authorId"=>"",
			"groupId"=>"",	
			"timestamp"=>"",
			"title"=>"",
			"content"=>""
		);
		
		public static function create(){
			$conn = parent::connect();
			
			$sql = "DROP TABLE IF EXISTS `".TBL_POSTS."`";
			
			mysql_query($sql);
			
			$sql = "
					CREATE TABLE `".TBL_POSTS."` (
					  `id` INT NOT NULL AUTO_INCREMENT,
					  `authorId` INT NOT NULL,
					  `groupId` INT NOT NULL,
					  `timestamp` DATETIME,
					  `title` TEXT NOT NULL,
					  `content` TEXT NOT NULL,
					  PRIMARY KEY (`id`),
					  FOREIGN KEY (`authorId`) REFERENCES `".TBL_USERS."`(`id`) ON DELETE CASCADE,
					  FOREIGN KEY (`groupId`) REFERENCES `".TBL_POSTS."`(`id`) ON DELETE CASCADE
					);
			";
			mysql_query($sql);
			parent::disconnect($conn);
						
		}
		
		//Get the information of specific Entry
		public static function getPost($id){
			$conn = parent::connect();
			$sql = "SELECT * FROM ".TBL_POSTS." WHERE `id`= ".$id;
			
			if($result = mysql_query($sql)){
				$row = mysql_fetch_assoc($result);
				if($row)return new Post($row);
				else return new Post(array("id" => -1));	
			}
			else{
				die("getPost Error:".mysql_error());
			}
			parent::disconnect($conn);
		}
		
		//Insert new entry
		public function insert(){
			$conn=parent::connect();
			
			$sql= 'INSERT INTO '.TBL_POSTS.' (
					`authorId`,
					`groupId`,	
					`timestamp`,
					`title`,
					`content`
				) VALUES (
					'.$this->data["authorId"].',
					'.$this->data["groupId"].',
					NOW(),
					\''.$this->data["title"].'\',
					\''.$this->data["content"].'\'
		           )';
			
			if(!mysql_query($sql)){
				die("Post insert Error:".mysql_error());
			}
			
			$this->data['id'] = mysql_insert_id();
			
			parent::disconnect($conn);
		}
		
		//Update existing entry
		public function update(){
			$conn=parent::connect();
			
			$sql= 'UPDATE '.TBL_POSTS.' SET 
					`title` = \''.$this->data["title"].'\',
					`content` = \''.$this->data["content"].'\'
				  WHERE `id` = '.$this->data["id"];

			if(!mysql_query($sql)){
				die("Post update Error:".mysql_error());
			}
			parent::disconnect($conn);
		}
		
		//Delete
		public function delete(){
			$conn=parent::connect();
			$sql='DELETE FROM '.TBL_POSTS.' 
				WHERE `id`= '.$this->data["id"];
				
			if(!mysql_query($sql)){
				die("Post delete Error:".mysql_error());
			}
			
			parent::disconnect($conn);
		}
		
		//Get all entries
		public static function getPosts($startRow = 0, $numRows = PAGE_SIZE, $order=""){
			$conn=parent::connect();
			if($order == "") $order = "`id` ASC";
			
			$sql="SELECT SQL_CALC_FOUND_ROWS * 
					FROM ".TBL_POSTS."
					ORDER BY ".$order." 
					LIMIT ".$startRow." , ".$numRows;
						
			if($result = mysql_query($sql)){
				$posts=array();
				
				while( $row = mysql_fetch_assoc($result)){
					$posts[] = new Post($row);	
				}
				if($st=mysql_query("SELECT found_rows() AS totalRows"))
					$row=mysql_fetch_assoc($st);
				else
					die("getPosts 2 Error:".mysql_error());
				
				parent::disconnect($conn);
				return array($posts,$row["totalRows"]);
			}
			else{
				die("getPosts 1 Error:".mysql_error());
			}			
			
		}
		
		public static function getPostsInAGroup($groupId, $startRow = 0, $numRows = PAGE_SIZE, $order=""){
			$conn=parent::connect();
			if($order == "") $order = "`id` ASC";
			
			$sql="SELECT SQL_CALC_FOUND_ROWS * 
					FROM ".TBL_POSTS."
					WHERE `groupId` = ".$groupId." 
					ORDER BY ".$order." 
					LIMIT ".$startRow." , ".$numRows;
						
			if($result = mysql_query($sql)){
				$posts=array();
				
				while( $row = mysql_fetch_assoc($result)){
					$posts[] = new Post($row);	
				}
				if($st=mysql_query("SELECT found_rows() AS totalRows"))
					$row=mysql_fetch_assoc($st);
				else
					die("getPostsInAGroup 2 Error:".mysql_error());
				
				parent::disconnect($conn);
				return array($posts,$row["totalRows"]);
			}
			else{
				die("getPostsInAGroup 1 Error:".mysql_error());
			}			
			
		}
		
		public function getComments($startRow = 0, $numRows = PAGE_SIZE, $order=""){
			return Comment::getCommentsInAPost($this->data["id"], $startRow, $numRows, $order);
		}
		
		public function getNumberOfLikes(){
			return Like::getNumberOfLikesOfAPost($this->data["id"]);	
		}
		
				
	}

?>