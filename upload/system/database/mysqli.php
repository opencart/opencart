<?php
final class MySQLi {
	private $mysqli;
	
	public function __construct($hostname, $username, $password, $database) {
		$this->mysqli = new mysqli($hostname, $username, $password, $database);
		
		if ($this->mysqli->connect_error) {
      		trigger_error('Error: Could not make a database link (' . $this->mysqli->connect_errno . ') ' . $this->mysqli->connect_error);
		}
		
		$this->mysqli->query("SET NAMES 'utf8'");
		$this->mysqli->query("SET CHARACTER SET utf8");
		$this->mysqli->query("SET CHARACTER_SET_CONNECTION=utf8");
		$this->mysqli->query("SET SQL_MODE = ''");
  	}
		
  	public function query($sql) {
		$result = $this->mysqli->query($sql);

		

		if ($this->mysqli->errno) {
		//$mysqli->errno
		}
		
			if (is_resource($resource)) {
				$i = 0;
    	
				$data = array();
		
				while ($row = $result->fetch_object()) {
					$data[$i] = $row;
    	
					$i++;
				}

				$result->close();
				
				$query = new stdClass();
				$query->row = isset($data[0]) ? $data[0] : array();
				$query->rows = $data;
				$query->num_rows = $result->num_rows;
				
				unset($data);
				
				
				
				
				return $query;	
    		} else {
				return true;
			}
		} else {
			trigger_error('Error: ' . mysql_error($this->link) . '<br />Error No: ' . mysql_errno($this->link) . '<br />' . $sql);
			exit();
    	}
  	}
	
	public function escape($value) {
		return $this->mysqli->real_escape_string($value);
	}
	
  	public function countAffected() {
    	return $this->mysqli->affected_rows;
  	}

  	public function getLastId() {
    	return $this->mysqli->insert_id;
  	}	
	
	public function __destruct() {
		$this->mysqli->close();
	}
}
?>