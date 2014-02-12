<?php
class DB {
	private $db;

	public function __construct($driver, $hostname, $username, $password, $database) {
		$pdoDriver = '';
		if (strpos($driver, 'mpdo') === 0) {
			$pdoDriver = substr($driver, 5); //mpdo.pdoDriver
			$driver = 'mpdo';
		}

		$file = dirname(__FILE__) . '/driver/database/' . $driver . '.php';
		
		if (file_exists($file)) {
			require_once($file);
			
			$class = 'DB' . $driver;
			
			if ($pdoDriver) {
				$this->db = new $class($pdoDriver, $hostname, $username, $password, $database);
			} else {
				$this->db = new $class($hostname, $username, $password, $database);
			}
		} else {
			exit('Error: Could not load database driver ' . $driver . '!');
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
