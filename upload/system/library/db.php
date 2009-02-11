<?php
final class DB {
	private $driver;
	private $prefix;
	
	public function __construct($driver, $hostname, $username, $password, $database, $prefix = NULL) {
		if (!@require_once(DIR_DATABASE . $driver . '.php')) {
			exit('Error: Could not load database file ' . $driver . '!');
		}
				
		$this->driver = new $driver($hostname, $username, $password, $database, $prefix);
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