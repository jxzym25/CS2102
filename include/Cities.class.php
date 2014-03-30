<?php

	require_once "DataObject.class.php";
	
	class City extends DataObject{
		protected $data=array(
			"id"=>"",
			"country"=>"",
			"name"=>""
		);
		
		public static function create(){
			$conn = parent::connect();
			$sql = "DROP TABLE IF EXISTS `".TBL_CITIES."`";
			
			mysql_query($sql);
			
			$sql = "
					CREATE TABLE `".TBL_CITIES."` (
					  `id` INT NOT NULL AUTO_INCREMENT,
					  `country` VARCHAR(126) NOT NULL,
					  `name` VARCHAR(126) NOT NULL,
					  PRIMARY KEY (`id`)
					);
			";
			
			mysql_query($sql);
			parent::disconnect($conn);
			
		}
		
		public static function getCityById($id){
			$conn = parent::connect();
			$sql = "SELECT * FROM ".TBL_CITIES." WHERE `id`= ".$id;
			
			if($result = mysql_query($sql)){
				$row = mysql_fetch_assoc($result);
				if($row)return new City($row);
			}
			else{
				die($sql." getCityById Error:".mysql_error());
			}
			parent::disconnect($conn);	
		}
		
		public static function getCity($country, $name){
			$conn = parent::connect();
			$sql = "SELECT * FROM ".TBL_CITIES." WHERE `country`= '".$country."' AND `name`= '".$name."'";
			
			if($result = mysql_query($sql)){
				$row = mysql_fetch_assoc($result);
				if($row)return new City($row);
				else{
					$city = new City(array(
								"country" => $country,
								"name" => $name
							));	
					$city->insert();
					return $city;
				}
			}
			else{
				die($sql." getCity Error:".mysql_error());
			}
			parent::disconnect($conn);	
		}
		
		public function insert(){
			$conn=parent::connect();
			
			$sql= 'INSERT INTO '.TBL_CITIES.' (
					`country`,
					`name`
				) VALUES (
					\''.$this->data["country"].'\',
					\''.$this->data["name"].'\'
		           )';
			
			if(!mysql_query($sql)){
				die($sql." City insert Error:".mysql_error());
			}
			
			$this->data['id'] = mysql_insert_id();
			
			parent::disconnect($conn);
		}
		
		//Update existing entry
		public function update(){
			$conn=parent::connect();
			
			$sql= 'UPDATE '.TBL_CITIES.' SET 
					`country` = \''.$this->data["country"].'\',
					`name` = \''.$this->data["name"].'\'
				  WHERE `id` = '.$this->data["id"];

			if(!mysql_query($sql)){
				die("City update Error:".mysql_error());
			}
			parent::disconnect($conn);
		}
		
		//Delete
		public function delete(){
			$conn=parent::connect();
			$sql='DELETE FROM '.TBL_CITIES.' 
				WHERE `id`= '.$this->data["id"];
				
			if(!mysql_query($sql)){
				die("City delete Error:".mysql_error());
			}
			
			parent::disconnect($conn);
		}
		
		//Get all entries
		public static function getCities($startRow = 0, $numRows = PAGE_SIZE, $order=""){
			$conn=parent::connect();
			if($order == "") $order = "`id` ASC";
			
			$sql="SELECT SQL_CALC_FOUND_ROWS * 
					FROM ".TBL_CITIES." 
					ORDER BY ".$order." 
					LIMIT ".$startRow." , ".$numRows;
						
			if($result = mysql_query($sql)){
				$cities=array();
				
				while( $row = mysql_fetch_assoc($result)){
					$cities[] = new City($row);	
				}
				if($st=mysql_query("SELECT found_rows() AS totalRows"))
					$row=mysql_fetch_assoc($st);
				else
					die("getCities 2 Error:".mysql_error());
				
				parent::disconnect($conn);
				return array($cities,$row["totalRows"]);
			}
			else{
				die("getCities 1 Error:".mysql_error());
			}			
			
		}
		
		public static function citiesStats(){
				
			$sql="SELECT SQL_CALC_FOUND_ROWS * 
					FROM ".TBL_CITIES." 
					ORDER BY ".$order." 
					LIMIT ".$startRow." , ".$numRows;
					
			if($result = mysql_query($sql)){
				$cities=array();
				
				while( $row = mysql_fetch_assoc($result)){
					$cities[] = new City($row);	
				}
				if($st=mysql_query("SELECT found_rows() AS totalRows"))
					$row=mysql_fetch_assoc($st);
				else
					die("getCities 2 Error:".mysql_error());
				
				parent::disconnect($conn);
				return array($cities,$row["totalRows"]);
			}
			else{
				die("getCities 1 Error:".mysql_error());
			}
			
		}
		
	}

?>