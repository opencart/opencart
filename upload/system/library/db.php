<?php
class DB {
	private $db;

	public function __construct($engine, $hostname, $username, $password, $database) {
		$file = dirname(__FILE__) . '/engine/database/' . $engine . '.php';
		
		if (file_exists($file)) {
			require_once($file);
			
			$class = 'DB' . $engine;
			
			$this->db = new $class($hostname, $username, $password, $database);
		} else {
			exit('Error: Could not load database engine ' . $engine . '!');
		}		
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