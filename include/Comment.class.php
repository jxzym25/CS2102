<?php

	require_once "DataObject.class.php";
	
	class Comment extends DataObject{
		protected $data=array(
			"id"=>"",
			"authorId"=>"",
			"parentPostId"=>"",	
			"timestamp"=>"",
			"content"=>""
		);
		
		public static function create(){
			$conn = parent::connect();
			
			$sql = "DROP TABLE IF EXISTS `".TBL_COMMENTS."`";
			
			mysql_query($sql);
			
			$sql = "
					CREATE TABLE `".TBL_COMMENTS."` (
					  `id` INT NOT NULL AUTO_INCREMENT,
					  `authorId` INT NOT NULL,
					  `parentPostId` INT NOT NULL,
					  `timestamp` DATETIME,
					  `content` TEXT NOT NULL,
					  PRIMARY KEY (`id`),
					  FOREIGN KEY (`authorId`) REFERENCES `".TBL_USERS."`(`id`) ON DELETE CASCADE,
					  FOREIGN KEY (`parentPostId`) REFERENCES `".TBL_COMMENTS."`(`id`) ON DELETE CASCADE
					);
			";
			mysql_query($sql);
			parent::disconnect($conn);
						
		}
		
		//Get the information of specific Entry
		public static function getComment($id){
			$conn = parent::connect();
			$sql = "SELECT * FROM ".TBL_COMMENTS." WHERE `id`= ".$id;
			
			if($result = mysql_query($sql)){
				$row = mysql_fetch_assoc($result);
				if($row)return new Comment($row);
				else return new Comment(array("id" => -1));	
			}
			else{
				die("getComment Error:".mysql_error());
			}
			parent::disconnect($conn);
		}
		
		//Insert new entry
		public function insert(){
			$conn=parent::connect();
			
			$sql= 'INSERT INTO '.TBL_COMMENTS.' (
					`authorId`,
					`parentPostId`,	
					`timestamp`,
					`content`
				) VALUES (
					'.$this->data["authorId"].',
					'.$this->data["parentPostId"].',
					NOW(),
					\''.$this->data["content"].'\'
		           )';
			
			if(!mysql_query($sql)){
				die("Comment insert Error:".mysql_error());
			}
			
			$this->data['id'] = mysql_insert_id();
			
			parent::disconnect($conn);
		}
		
		//Update existing entry
		public function update(){
			$conn=parent::connect();
			
			$sql= 'UPDATE '.TBL_COMMENTS.' SET 
					`content` = \''.$this->data["content"].'\'
				  WHERE `id` = '.$this->data["id"];

			if(!mysql_query($sql)){
				die("Comment update Error:".mysql_error());
			}
			parent::disconnect($conn);
		}
		
		//Delete
		public function delete(){
			$conn=parent::connect();
			$sql='DELETE FROM '.TBL_COMMENTS.' 
				WHERE `id`= '.$this->data["id"];
				
			if(!mysql_query($sql)){
				die("Comment delete Error:".mysql_error());
			}
			
			parent::disconnect($conn);
		}
		
		//Get all entries
		public static function getCommentsInAPost($parentPostId, $startRow = 0, $numRows = PAGE_SIZE, $order=""){
			$conn=parent::connect();
			if($order == "") $order = "`id` ASC";
			
			$sql="SELECT SQL_CALC_FOUND_ROWS * 
					FROM ".TBL_COMMENTS."
					WHERE `parentPostId` = ".$parentPostId." 
					ORDER BY ".$order." 
					LIMIT ".$startRow." , ".$numRows;
						
			if($result = mysql_query($sql)){
				$comments=array();
				
				while( $row = mysql_fetch_assoc($result)){
					$comments[] = new Comment($row);	
				}
				if($st=mysql_query("SELECT found_rows() AS totalRows"))
					$row=mysql_fetch_assoc($st);
				else
					die("getCommentsInAPost 2 Error:".mysql_error());
				
				parent::disconnect($conn);
				return array($comments,$row["totalRows"]);
			}
			else{
				die("getCommentsInAPost 1 Error:".mysql_error());
			}			
			
		}		

		
		
	}

?>