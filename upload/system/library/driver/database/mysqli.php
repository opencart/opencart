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
		$result = $this->link->query($sql);

		if( FALSE === $result ) {
			trigger_error('Error: ' . $this->link->error  . '<br />Error No: ' . $this->link->errno . '<br />' . $sql);
			exit();
		} else if( TRUE === $result ){
			return TRUE;
		} else {
			$data = $result->fetch_all(MYSQLI_BOTH);

			$query = new stdClass();
			$query->num_rows = $result->num_rows;
			$query->rows = $data;
			if( $result->num_rows > 0 ) {
				$query->row = $data[0];
			}
			
			$result->close();
			unset($data);

			return $query;	
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
