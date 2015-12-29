<?php
class ControllerActionDatabase extends Controller {
	public function index() {
		if (file_exists('../config.php') && filesize('../config.php') > 0) {
			$lines = file(DIR_OPENCART . 'config.php');
			
			foreach ($lines as $line) {
				if (strpos(strtoupper($line), 'DB_') !== false) {
					eval($line);
				}
			}
			
			if (defined('DB_PORT')) {
				$port = DB_PORT;
			} else {
				$port = ini_get('mysqli.default_port');
			}
			
			$this->registry->set('db', new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, $port));
		}
	}
}