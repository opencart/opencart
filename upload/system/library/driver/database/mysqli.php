<?php
final class DBMySQLi {
	private $link;

	public function __construct($hostname, $username, $password, $database) {
		$this->link = new mysqli($hostname, $username, $password, $database);

		if ($this->link->connect_error) {
			trigger_error('Error: Could not make a database link (' . $this->link->connect_errno . ') ' . $this->link->connect_error);
		}

		$this->link->set_charset("utf8");
	}

	public function query($sql) {
		$query = $this->link->query($sql);

		if( FALSE === $result ) {
			trigger_error('Error: ' . $this->link->error  . '<br />Error No: ' . $this->link->errno . '<br />' . $sql);
		} else if( TRUE === $result ){
			return TRUE;
		} else {
			$data = $query->fetch_all(MYSQLI_BOTH);

			$result = new stdClass();
			$result->num_rows = $result->num_rows;
			$result->row = isset($data[0]) ? $data[0] : array();
			$result->rows = $data;
			
			$query->close();
			unset($data);

			return $result;	
		} 
	}

	public function escape($value) {
		return $this->link->real_escape_string($value);
	}

	public function countAffected() {
		return $this->link->affected_rows;
	}

	public function getLastId() {
		return $this->link->insert_id;
	}

	public function __destruct() {
		$this->link->close();
	}
}
?>
