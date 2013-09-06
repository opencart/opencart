<?php
final class DBMySQLi {
	private $mysqli;

	public function __construct($hostname, $username, $password, $database) {
		$this->link = new mysqli($hostname, $username, $password, $database);

		if ($this->link->connect_error) {
			trigger_error('Error: Could not make a database link (' . $this->link->connect_errno . ') ' . $this->link->connect_error);
		}

		$this->link->set_charset("utf-8");
	}

	public function query($sql) {
		$query = $this->link->query($sql);

		if ($this->link->errno) {
			trigger_error('Error: ' . $this->link->error . '<br />Error No: ' . $this->link->errno . '<br />' . $sql);
			exit();
		}

		if ($query){

			if (isset($query->num_rows)){
				$result = new stdClass();
				$data = array();

				while ($row = $query->fetch_array()) {
					$data[] = $row;
				}

				$result->num_rows = $query->num_rows;
				$query->close();
				$result->row = isset($data[0]) ? $data[0] : array();
				$result->rows = $data;
				unset($data);

				return $result;

			}else{

				$result = new stdClass(); // to handle older opencart actions
				$result->row = array(); // to handle older opencart actions
				$result->rows = array(); // to handle older opencart actions
				$result->num_rows = 0; // to handle older opencart actions
				return $result; // should be false

			}

		}else {
			trigger_error('Error: ' . $this->link->error . '<br />Error No: ' . $this->link->errno . '<br />' . $sql);
			exit();
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