<?php
class Session {
	public $session_id = '';
	public $adatptor = '';
	public $data = array();

	public function __construct($handler = '') {
		/*
		if ($handler) {
			session_set_save_handler(
				array($handler, 'open'),
				array($handler, 'close'),
				array($handler, 'read'),
				array($handler, 'write'),
				array($handler, 'destroy'),
				array($handler, 'gc')
			);	
		}
		*/
	}

	public function getId() {
		return $this->session_id;
	}

	public function createId() {
		session_start();
		
		session_regenerate_id();
		
		$session_id = session_id();
		
		session_destroy();
		
		return $session_id;
	}		

	public function start($session_id = '') {
		if ($session_id) {
			$this->session_id = $session_id;
		} elseif (isset($_COOKIE[session_name])) {
			$this->session_id = $_COOKIE[session_name];
		} else {
			$this->session_id = $this->createId();
		}
		
		if (!preg_match('/^[a-zA-Z0-9,\-]{22,52}$/', $this->session_id)) {
			exit('Error: Invalid session ID!');
		}
		
		$file = session_save_path() . '/sess_' . $this->session_id;
		
		if (is_file($file)) {
			$handle = fopen($file, 'r');
			
			$data = fread($handle, filesize($file));
			
			fclose($handle);
			
			$this->data = unserialize($data);
		}		
		
		return true;
	}
	
	public function destroy() {
		if ($this->session_id) {
			$file = session_save_path() . '/sess_' . $this->session_id;
				
			if (is_file($file)) {
				unset($file);
			}
			
			return session_destroy();
		}
	}
	
	public function __destruct() {
		if ($this->session_id) {
			$file = session_save_path() . '/sess_' . $this->session_id;
			
			$handle = fopen($file, 'w');
	
			flock($handle, LOCK_EX);
	
			fwrite($handle, serialize($this->data));
	
			fflush($handle);
	
			flock($handle, LOCK_UN);
	
			fclose($handle);
		}
	}	
}