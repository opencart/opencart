<?php
class DB {
	private $driver;

	public function __construct($driver, $hostname, $username, $password, $database) {
		$file = DIR_DATABASE . $driver . '.php';

		if (file_exists($file)) {
			require_once($file);

			$class = 'DB' . $driver;

			$this->driver = new $class($hostname, $username, $password, $database);
		} else {
			exit('Error: Could not load database driver type ' . $driver . '!');
		}
	}

	public function query($sql) {
		return $this->driver->query($sql);
	}

	public function escape($value) {
		return $this->driver->escape($value);
	}

	public function countAffected() {
		return $this->driver->countAffected();
	}

	public function getLastId() {
		return $this->driver->getLastId();
	}
}
?>