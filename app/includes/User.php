<?php

class User {
	
	private $id, $fields;
	
	function __construct($userId = null) {
		global $db;
		if ($userId != null) {
			$this->id = $userId;
			$db->select('users', null, array('id_users'=>$userId));
			$this->loadInfo($db->loadRow());		
		}
	}
	
	function loadInfo($row) {
		foreach($row as $k=>$v) {
			$this->{$k} = $v;
			$this->fields[] = $k;
		}		
	}
		
	function getInfo() {
		print_r($this->fields);
	}
	
	function getFields() {
		global $db;
		$db->query("SHOW COLUMNS FROM `users`");
		$fields = $db->loadColumn('Field');
		unset($fields[0]);
		return $fields;
	}
	
	function create($source) {
		foreach($this->getFields() as $k=>$v) {
			$this->{$v} = $source[$v];
			$this->fields[] = $v;
		}
		$this->reg_date = date("Y-m-d H:i:s");
	}
	
	function save() {
		global $db;
		if ($this->id == null) {
			$ins = array();
			foreach($this->fields as $k=>$v)
				$ins[$v] = $this->{$v};
			$db->insert('users',$ins);
		}
	}
	
}
?>