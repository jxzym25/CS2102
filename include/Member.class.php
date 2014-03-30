<?php

	require_once "DataObject.class.php";
	
	class Member extends DataObject{
		protected $data=array(
			"userId"=>"",
			"groupId"=>"",
			"type"=>"",	
			"joinedDate"=>""	
		);
		
		public static function create(){
			$conn = parent::connect();
			
			$sql = "DROP TABLE IF EXISTS `".TBL_MEMBERS."`";
			
			mysql_query($sql);
			$sql = "
					CREATE TABLE `".TBL_MEMBERS."` (
					  `userId` INT NOT NULL,
					  `groupId` INT NOT NULL,
					  `type` INT NOT NULL,
					  `joinedDate` DATETIME,
					  PRIMARY KEY (`userId`,`groupId`),
					  FOREIGN KEY (`userId`) REFERENCES `".TBL_USERS."`(`id`) ON DELETE CASCADE,
					  FOREIGN KEY (`groupId`) REFERENCES `".TBL_GROUPS."`(`id`) ON DELETE CASCADE
					);
			";
			mysql_query($sql);
			parent::disconnect($conn);
						
		}
		
		//Get the information of specific Entry
		public static function getMember($userId, $groupId){
			$conn = parent::connect();
			$sql = "SELECT * FROM ".TBL_MEMBERS." WHERE `userId`= ".$userId." AND `groupId` = ".$groupId;
			
			if($result = mysql_query($sql)){
				$row = mysql_fetch_assoc($result);
				if($row)return new Member($row);
				else return new Member(array("userId" => -1, "groupId" => -1));	
			}
			else{
				die("getMember Error:".mysql_error());
			}
			parent::disconnect($conn);
		}
		
		//Insert new entry
		public function insert(){
			$conn=parent::connect();
			
			$sql= 'INSERT INTO '.TBL_MEMBERS.' (
					`userId`,
					`groupId`,
					`type`,	
					`joinedDate`
				) VALUES (
					'.$this->data["userId"].',
					'.$this->data["groupId"].',
					'.$this->data["type"].',
					NOW()
		           )';
			
			if(!mysql_query($sql)){
				die("Member insert Error:".mysql_error());
			}
						
			parent::disconnect($conn);
		}
		
		//Update existing entry
		public function update(){
			$conn=parent::connect();
			
			$sql= 'UPDATE '.TBL_MEMBERS.' SET 
					`type` = '.$this->data["type"].'
				  WHERE `id` = '.$this->data["id"];

			if(!mysql_query($sql)){
				die("Member update Error:".mysql_error());
			}
			parent::disconnect($conn);
		}
		
		//Delete
		public function delete(){
			$conn=parent::connect();
			$sql='DELETE FROM '.TBL_MEMBERS.' 
					WHERE `userId`= '.$this->data["userId"].' AND `groupId` = '.$this->data["groupId"];	
						
			if(!mysql_query($sql)){
				die("Member delete Error:".mysql_error());
			}
			
			list($users, $totalRows) = self::getUsersInAGroup($this->data["groupId"]);
			if($totalRows == 0){
				$group = Group::getGroup($this->data["groupId"]);
				$group->delete();	
			}
			
			parent::disconnect($conn);
		}
		
		//Get all entries
		public static function getUsersInAGroup($groupId, $startRow = 0, $numRows = PAGE_SIZE, $order=""){
			$conn=parent::connect();
			if($order == "") $order = "`id` ASC";
			
			$sql="SELECT SQL_CALC_FOUND_ROWS u.*, m.type 
					FROM ".TBL_USERS." AS u, ".TBL_MEMBERS." AS m
					WHERE EXISTS(
						SELECT * FROM ".TBL_MEMBERS."
						WHERE `userId` = u.`id`
						AND `groupId` = ".$groupId." 
					)
					AND u.`id` = m.`userId` AND m.`groupId` = ".$groupId." 
					ORDER BY ".$order." 
					LIMIT ".$startRow." , ".$numRows;
						
			if($result = mysql_query($sql)){
				$users=array();
				$type = array();
				
				while( $row = mysql_fetch_assoc($result)){
					$users[] = new User($row);
					$type[] = $row["type"]; 
				}
				if($st=mysql_query("SELECT found_rows() AS totalRows"))
					$row=mysql_fetch_assoc($st);
				else
					die("getUsersInAGroup 2 Error:".mysql_error());
				
				parent::disconnect($conn);
				return array($users,$row["totalRows"], $type);
			}
			else{
				die("getUsersInAGroup 1 Error:".mysql_error());
			}			
			
		}
		
		public static function getUserJoinedGroups($userId, $startRow = 0, $numRows = PAGE_SIZE, $order=""){
			$conn=parent::connect();
			if($order == "") $order = "`id` ASC";
			
			$sql="SELECT SQL_CALC_FOUND_ROWS g.*, m.type 
					FROM ".TBL_GROUPS." AS g , ".TBL_MEMBERS." AS m
					WHERE EXISTS(
						SELECT * FROM ".TBL_MEMBERS."
						WHERE `userId` = ".$userId."
						AND `groupId` = g.`id` 
					)
					AND g.`id` = m.`groupId` AND m.`userId` = ".$userId." 
					ORDER BY g.".$order." 
					LIMIT ".$startRow." , ".$numRows;
						
			if($result = mysql_query($sql)){
				$groups=array();
				$type = array();
				
				while( $row = mysql_fetch_assoc($result)){
					$groups[] = new Group($row);	
					$type[] = $row["type"]; 
				}
				if($st=mysql_query("SELECT found_rows() AS totalRows"))
					$row=mysql_fetch_assoc($st);
				else
					die("getUserJoinedGroups 2 Error:".mysql_error());
				
				parent::disconnect($conn);
				return array($groups,$row["totalRows"], $type);
			}
			else{
				die("getUserJoinedGroups 1 Error:".mysql_error());
			}			
			
		}
		
		public static function getNewestPostFromUserJoinedGroups($userId, $startRow = 0, $numRows = PAGE_SIZE, $order=""){
			$conn=parent::connect();
			if($order == "") $order = "`id` DESC";
			
			$sql="SELECT SQL_CALC_FOUND_ROWS np.*
					FROM (
						SELECT p.*  
						FROM ".TBL_POSTS." AS p, ".TBL_GROUPS." AS g 
						WHERE p.`groupId` = g.`id`
						AND  p.`timestamp`>= ALL (
							SELECT p2.`timestamp`  
							FROM ".TBL_POSTS." AS p2
							WHERE p2.`groupId` = g.`id`
						)
						GROUP BY g.`id`
					) AS np, ".TBL_MEMBERS." AS m
					WHERE m.`userId` = ".$userId."
					AND m.`groupId` = np.`groupId` 
					ORDER BY np.".$order." 
					LIMIT ".$startRow." , ".$numRows.";";
						
			if($result = mysql_query($sql)){
				$groups=array();
				
				while( $row = mysql_fetch_assoc($result)){
					$groups[] = new Post($row);	
				}
				if($st=mysql_query("SELECT found_rows() AS totalRows"))
					$row=mysql_fetch_assoc($st);
				else
					die("getNewestPostFromUserJoinedGroups 2 Error:".mysql_error());
				
				parent::disconnect($conn);
				return array($groups,$row["totalRows"]);
			}
			else{
				die("getNewestPostFromUserJoinedGroups 1 Error:".mysql_error());
			}			
			
		}
		
		
	}

?>