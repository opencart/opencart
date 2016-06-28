<?php
class Session {
	public $session_id = '';
	public $adatptor = '';
	public $data = array();

	public function __construct($handler = '', $session_id = '') {
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
		
		if ($session_id && !preg_match('/^[a-zA-Z0-9,\-]{22,52}$/', $session_id)) {
			exit('Error: Invalid session ID!');
		}
					
		if ($session_id) {
			$this->session_id = $session_id;
			
		} else {
			session_start();
			
			$this->session_id = session_id();
			
			session_abort();
		}

		$file = session_save_path() . '/sess_' . $this->session_id;
		
		if (is_file($file)) {
			$handle = fopen($file, 'r');
			
			$json = fread($handle, filesize($file));
			
			fclose($handle);
			
			$this->data = unserialize($json);
		}
	}

	public function getId() {
		return $this->session_id;
	}

	public function start($session_id = '') {
		if ($session_id) {
			session_id($session_id);
		}
			
		if (session_status() != PHP_SESSION_ACTIVE) {
			ini_set('session.use_only_cookies', 'Off');
			ini_set('session.use_cookies', 'On');
			ini_set('session.use_trans_sid', 'Off');
			ini_set('session.cookie_httponly', 'On');
		
			if (isset($_COOKIE[session_name()]) && !preg_match('/^[a-zA-Z0-9,\-]{22,52}$/', $_COOKIE[session_name()])) {
				exit('Error: Invalid session ID!');
			}

			session_set_cookie_params(0, '/');
			session_start();
			
			$this->session_id = session_id();
			
			session_abort();
		}
		
		return true;
	}
	
	public function destroy() {
		$file = session_save_path() . '/sess_' . session_id();
			
		if (is_file($file)) {
			unset($file);
		}
		
		return session_destroy();
	}
	
	public function __destruct() {
		$file = session_save_path() . '/sess_' . session_id();
		
		$handle = fopen($file, 'w');

		flock($handle, LOCK_EX);

		fwrite($handle, serialize($this->data));

		fflush($handle);

		flock($handle, LOCK_UN);

		fclose($handle);
	}	
}