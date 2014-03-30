<?php

	require_once "DataObject.class.php";
	
	class Like extends DataObject{
		protected $data=array(
			"userId"=>"",
			"postId"=>""
		);
		
		public static function create(){
			$conn = parent::connect();
			
			$sql = "DROP TABLE IF EXISTS `Likes`";
			
			mysql_query($sql);
			
			$sql = "
					CREATE TABLE `".TBL_LIKES."` (
					  `userId` INT NOT NULL,
					  `postId` INT NOT NULL,
					  PRIMARY KEY (`userId`, `postId`),
					  FOREIGN KEY (`userId`) REFERENCES `Users`(`id`) ON DELETE CASCADE,
					  FOREIGN KEY (`postId`) REFERENCES `Posts`(`id`) ON DELETE CASCADE
					);
			";
			mysql_query($sql);
			parent::disconnect($conn);
						
		}
		
		//Get the information of specific Entry
		public static function getLike($userId, $postId){
			$conn = parent::connect();
			$sql = "SELECT * FROM ".TBL_LIKES." WHERE `userId`= ".$userId." AND `postId` = ".$postId;
			
			if($result = mysql_query($sql)){
				$row = mysql_fetch_assoc($result);
				if($row)return new Like($row);
				else return new Like(array("userId" => -1, "postId" => -1));	
			}
			else{
				die("getLike Error:".mysql_error());
			}
			parent::disconnect($conn);
		}
		
		//Insert new entry
		public function insert(){
			$conn=parent::connect();
			
			$sql= 'INSERT INTO '.TBL_LIKES.' (
					`userId`,
					`postId`
				) VALUES (
					'.$this->data["userId"].',
					'.$this->data["postId"].'
		           )';
			
			if(!mysql_query($sql)){
				die("Like insert Error:".mysql_error());
			}
						
			parent::disconnect($conn);
		}
		
		//Delete
		public function delete(){
			$conn=parent::connect();
			$sql='DELETE FROM '.TBL_LIKES.' 
					WHERE `userId`= '.$this->data["userId"].' AND `postId` = '.$this->data["postId"];	
						
			if(!mysql_query($sql)){
				die("Like delete Error:".mysql_error());
			}
			
			parent::disconnect($conn);
		}
		
		//Get all entries
		public static function getNumberOfLikesOfAPost($postId){
			$conn=parent::connect();
			if($order == "") $order = "`joinedDate` ASC";
			
			$sql="SELECT COUNT(*) AS n
					FROM ".TBL_LIKES." 
					WHERE `postId` = ".$postId;
						
			if($result = mysql_query($sql)){
				
				$row = mysql_fetch_assoc($result);
				
				parent::disconnect($conn);
				return $row["n"];
			}
			else{
				die("getNumberOfLikesOfAPost Error:".mysql_error());
			}			
			
		}

		
		
	}

?>