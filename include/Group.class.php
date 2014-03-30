<?php

	require_once "DataObject.class.php";
	
	class Group extends DataObject{
		protected $data=array(
			"id"=>"",
			"name"=>"",
			"description"=>"",	
			"createdDate"=>""	
		);
		
		public static function create(){
			$conn = parent::connect();
			$sql = "DROP TABLE IF EXISTS `".TBL_GROUPS."`";
			
			mysql_query($sql);
			
			$sql = "
					CREATE TABLE `".TBL_GROUPS."` (
					  `id` INT NOT NULL AUTO_INCREMENT,
					  `name` VARCHAR(126) UNIQUE NOT NULL,
					  `description` TEXT NOT NULL,
					  `createdDate` DATETIME,
					  PRIMARY KEY (`id`)
					);
			";
			mysql_query($sql);
			parent::disconnect($conn);
						
		}
		
		//Get the information of specific Entry
		public static function getGroup($id){
			$conn = parent::connect();
			$sql = "SELECT * FROM ".TBL_GROUPS." WHERE `id`= ".$id;
			
			if($result = mysql_query($sql)){
				$row = mysql_fetch_assoc($result);
				if($row)return new Group($row);
				else return new Group(array("id" => -1));	
			}
			else{
				die("getGroup Error:".mysql_error());
			}
			parent::disconnect($conn);
		}
		
		//Get the information of specific Entry
		public static function getGroupByName($name){
			$conn = parent::connect();
			$sql = "SELECT * FROM ".TBL_GROUPS." WHERE `name`= '".$name."'";
			
			if($result = mysql_query($sql)){
				$row = mysql_fetch_assoc($result);
				if($row)return new Group($row);
				else return new Group(array("id" => -1));	
			}
			else{
				die("getGroup Error:".mysql_error());
			}
			parent::disconnect($conn);
		}
		
		//Insert new entry
		public function insert(){
			$conn=parent::connect();
			
			$sql= 'INSERT INTO '.TBL_GROUPS.' (
					`name`,
					`description`,	
					`createdDate`
				) VALUES (
					\''.$this->data["name"].'\',
					\''.$this->data["description"].'\',
					NOW()
		           )';
			
			if(!mysql_query($sql)){
				die("Group insert Error:".mysql_error());
			}
			
			$this->data['id'] = mysql_insert_id();
			
			parent::disconnect($conn);
		}
		
		//Update existing entry
		public function update(){
			$conn=parent::connect();
			
			$sql= 'UPDATE '.TBL_GROUPS.' SET 
					`description` = \''.$this->data["description"].'\'
				  WHERE `id` = '.$this->data["id"];

			if(!mysql_query($sql)){
				die("Group update Error:".mysql_error());
			}
			parent::disconnect($conn);
		}
		
		//Delete
		public function delete(){
			$conn=parent::connect();
			$sql='DELETE FROM '.TBL_GROUPS.' 
				WHERE `id`= '.$this->data["id"];
				
			if(!mysql_query($sql)){
				die("Group delete Error:".mysql_error());
			}
			
			parent::disconnect($conn);
		}
		
		//Get all entries
		public static function getGroups($startRow = 0, $numRows = PAGE_SIZE, $order="", $name=""){
			$conn=parent::connect();
			if($order == "") $order = "`id` ASC";
			
			if($name!="")
				$sql="SELECT SQL_CALC_FOUND_ROWS * 
					FROM ".TBL_GROUPS." 
					WHERE `name` LIKE '%".$name."%'
					ORDER BY ".$order." 
					LIMIT ".$startRow." , ".$numRows;
			else
				$sql="SELECT SQL_CALC_FOUND_ROWS * 
					FROM ".TBL_GROUPS." 
					ORDER BY ".$order." 
					LIMIT ".$startRow." , ".$numRows;
						
			if($result = mysql_query($sql)){
				$groups=array();
				
				while( $row = mysql_fetch_assoc($result)){
					$groups[] = new Group($row);	
				}
				if($st=mysql_query("SELECT found_rows() AS totalRows"))
					$row=mysql_fetch_assoc($st);
				else
					die("getGroups 2 Error:".mysql_error());
				
				parent::disconnect($conn);
				return array($groups,$row["totalRows"]);
			}
			else{
				die("getGroups 1 Error:".mysql_error());
			}			
			
		}
		
		//Get all entries
		public static function getHotGroups($userId, $startRow = 0, $numRows = PAGE_SIZE){
			$conn=parent::connect();
			
			$sql="SELECT SQL_CALC_FOUND_ROWS g.* 
					FROM ".TBL_GROUPS." AS g, ".TBL_MEMBERS." AS m
					WHERE g.`id` = m.`groupId`
					AND NOT EXISTS(
						SELECT *
						FROM ".TBL_MEMBERS." AS m2
						WHERE m2.`userId` = ".$userId."
						AND m2.`groupId` = g.`id`
					)
					GROUP BY m.`groupId`
					ORDER BY COUNT(m.`userId`) DESC
					LIMIT ".$startRow." , ".$numRows;
						
			if($result = mysql_query($sql)){
				$groups=array();
				
				while( $row = mysql_fetch_assoc($result)){
					$groups[] = new Group($row);	
				}
				if($st=mysql_query("SELECT found_rows() AS totalRows"))
					$row=mysql_fetch_assoc($st);
				else
					die("getGroups 2 Error:".mysql_error());
				
				parent::disconnect($conn);
				return array($groups,$row["totalRows"]);
			}
			else{
				die("getGroups 1 Error:".mysql_error());
			}			
			
		}
		
		public function getUsers($startRow = 0, $numRows = PAGE_SIZE, $order=""){
			return Member::getUsersInAGroup($this->data["id"], $startRow, $numRows, $order);
		}
		
		public function getPosts($startRow = 0, $numRows = PAGE_SIZE, $order=""){
			return Post::getPostsInAGroup($this->data["id"], $startRow, $numRows, $order);
		}
		
	}

?>