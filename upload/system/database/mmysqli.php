<?php
final class mMySQLi {
	private $mysqli;

	public function __construct($hostname, $username, $password, $database) {

		$this->mysqli = new mysqli($hostname, $username, $password, $database);

		if ($this->mysqli->connect_error) {
			trigger_error('Error: Could not make a database link (' . $this->mysqli->connect_errno . ') ' . $this->mysqli->connect_error);
		}

		$this->mysqli->set_charset("utf-8");

	}

	public function query($sql) {

		$query = $this->mysqli->query($sql);

		if ($this->mysqli->errno) {
			trigger_error('Error: ' . $this->mysqli->error . '<br />Error No: ' . $this->mysqli->errno . '<br />' . $sql);
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
			trigger_error('Error: ' . $this->mysqli->error . '<br />Error No: ' . $this->mysqli->errno . '<br />' . $sql);
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