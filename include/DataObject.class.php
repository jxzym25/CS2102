<?php

	require_once "config.inc.php";
	abstract class DataObject{
		protected $data=array();
		public function __construct($data){
				foreach($data as $key=>$value){
				if(array_key_exists($key,$this->data))$this->data[$key]=$value;
			}
		}
	
		public function getValue($field){
			if(array_key_exists($field,$this->data)){
				return $this->data[$field];
			}else{
				die("ERROR Fieldnotfound: ".$field);
			}
		}
		
		public function getValueEncoded($field){
			return htmlspecialchars($this->getValue($field));
		}
		
		static protected function connect(){
			$conn=mysql_connect(HOST,DB_USERNAME,DB_PASSWORD);
			if (mysql_errno())
				die("Connection failed:".mysql_errno());
			else{
				$db_selected = mysql_select_db(DB, $conn);
				if (!$db_selected) {
					die ('Can\'t use DB : ' . mysql_error());
				}
				return $conn;
			}
		}
		
		static protected function disconnect($conn){
			mysql_close($conn);
		}
	}
?>