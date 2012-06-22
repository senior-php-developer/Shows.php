<?php
require 'utils.php';

class Factory {
	static private $database;
	
	function getDB() {
		if (!class_exists("Database"))
			include("Database.php");
		
		if (self::$database == NULL)
			self::$database = new Database();
			
		return self::$database;
	}
	
	function getUser($userId = null) {
		if (!class_exists("User"))
			include("User.php");
		
		$user = new User($userId);
		return $user;
	}
	


}


?>