<?php
final class MySQL {
	private $link;
	private $prefix;
	
	public function __construct($hostname, $username, $password, $database, $prefix = NULL) {
		if (!$this->link = mysql_connect($hostname, $username, $password)) {
      		exit('Error: Could not make a database connection using ' . $username . '@' . $hostname);
    	}

    	if (!mysql_select_db($database, $this->link)) {
      		exit('Error: Could not connect to database ' . $database);
    	}
		
		$this->prefix = $prefix;
		
		mysql_query("SET NAMES 'utf8'", $this->link);
		mysql_query("SET CHARACTER SET utf8", $this->link);
  	}
		
  	public function query($sql) {
		$resource = mysql_query(str_replace('#__', $this->prefix, $sql), $this->link);

		if ($resource) {
			if (is_resource($resource)) {
				$i = 0;
    	
				$data = array();
		
				while ($result = mysql_fetch_assoc($resource)) {
					$data[$i] = $result;
    	
					$i++;
				}
				
				mysql_free_result($resource);
				
				$query = new stdClass();
				$query->row      = isset($data[0]) ? $data[0] : array();
				$query->rows     = $data;
				$query->num_rows = $i;
				
				unset($data);

				return $query;	
    		} else {
				return TRUE;
			}
		} else {
      		exit('Error: ' . mysql_error() . '<br />Error No: ' . mysql_errno() . '<br />' . $sql);
    	}
  	}
	
	public function escape($value) {
		return mysql_real_escape_string($value, $this->link);
	}
	
  	public function countAffected() {
    	return mysql_affected_rows($this->link);
  	}

  	public function getLastId() {
    	return mysql_insert_id($this->link);
  	}	
}
?>