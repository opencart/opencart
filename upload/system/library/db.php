<?php
class DB {
	private $db;

	public function __construct($type, $hostname, $username, $password, $database) {
		if (file_exists(DIR_DATABASE . $type . '.php')) {
			require_once(DIR_DATABASE . $type . '.php');
		} else {
			exit('Error: Could not load database file ' . $driver . '!');
		}

		$this->db = new $type($hostname, $username, $password, $database);
	}

	public function query($sql) {
		return $this->db->query($sql);
	}

	public function escape($value) {
		return $this->db->escape($value);
	}

	public function countAffected() {
		return $this->db->countAffected();
	}

	public function getLastId() {
		return $this->db->getLastId();
	}
}
?>