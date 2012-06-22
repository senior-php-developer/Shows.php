<?php

class Database {
	private $connection;
	private $result;
	
	function __construct() {
		include("config/database.php");
		$connection = mysql_connect($conn[0], $conn[1], $conn[2]) or die(mysql_error());
		mysql_select_db($conn[3]) or die(mysql_error());	
	}
	
	function query($query) {
		$this->result = mysql_query($query);
	}
	
	/* adds record to database
	*
	* table: table to work with
	* fields: array where 1) values are column names; 2) keys are column names, values are field values
	* post: if true, use $_request array for values, otherwise get values from fields array 
	* $db->insert('users',array('username','password'),true); // inserts values from $_request array
	* $db->insert('users',array('username'=>'guest','password'=>'12345')); // insert values from argument
	*
	*/
	function insert($table, $fields) {
		// parse fields array to construct query string
		foreach($fields as $k=>$v) {
			$keys[] = "`".mysql_real_escape_string($k)."`";
			$values[] = "'".mysql_real_escape_string($v)."'";
		}
		$query = "INSERT INTO `$table` (".implode(',',$keys).") VALUES(".implode(',',$values).")";
		mysql_query($query) or die(mysql_error());
		return true;
	}
	
	/* selects records from database
	*
	* table: table to select from
	* fields: array of field names which select from database
	* where: array of key-value, where key = field name and value = constrain
	*
	*/
	function select($table, $fields, $where = null, $order = null, $limit = 100, $asc = true) {
		// construct fields list for selection
		if (count($fields) > 0)
			$strFields = implode(',',$fields);
		else 
			$strFields = '*';
		// construct where clause for query
		$strWhere = '';
		if (is_array($where) and count($where) > 0) {
			$strWhere = "WHERE ";
			foreach($where as $k=>$v)
				$argWhere[] = "`$k` = '$v'";
			if (count($argWhere) > 1)
				$strWhere .= implode(' AND ',$argWhere);
			else
				$strWhere .= $argWhere[0];
		} else if ($where != null)
			$strWhere = "WHERE ".$where;
		if ($asc) $by = 'ASC'; else $by = 'DESC';
		if ($order != null) $or = "ORDER BY `$order` $by"; else $or = '';
		$sql = "SELECT $strFields FROM $table $strWhere $or LIMIT $limit";
		//print_r('DEBUG:'.$sql);
		$this->result = mysql_query($sql) or die(count($where));

	}
	
	function loadResult() {
		$arr = mysql_fetch_array($this->result);

		return $arr[0];
	}
	
	function loadRow() {
		mysql_data_seek($this->result, 0);
		$arr = mysql_fetch_assoc($this->result);
		return $arr;
	}
	
	function loadColumn($id) {
		$arr = array();
		while ($row = mysql_fetch_assoc($this->result))
			$arr[] = $row[$id];
		return $arr;	
	}
	
	function loadAll() {
		$arr = array();
		while ($row = mysql_fetch_assoc($this->result))
			array_push($arr, $row);
		return $arr;
	}
	
	function delete($table, $where) {
		// constructing where clause
		if (count($where) > 0) {
			$strWhere = "WHERE ";
			foreach($where as $k=>$v)
				$argWhere[] = "`$k` = '$v'";
			if (count($argWhere) > 1)
				$strWhere .= implode(' AND ',$argWhere);
			else
				$strWhere .= $argWhere[0];
		}
		// running sql query
		mysql_query("DELETE FROM $table $strWhere") or die(mysql_query());
	}
	
	function numRows() {
		return mysql_num_rows($this->result);
	
	}


}

?>