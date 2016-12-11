<?php
class Session {
	public $session_id = '';
	public $data = array();

	public function __construct($adaptor = 'native') {
		$class = 'Session\\' . $adaptor;
		
		if (class_exists($class)) {
			$this->adaptor = new $class($this);
		} else {
			throw new \Exception('Error: Could not load session adaptor ' . $adaptor . ' session!');
		}		
		
		if ($this->adaptor) {
			session_set_save_handler($this->adaptor);
		}
			
		if ($this->adaptor && !session_id()) {
			ini_set('session.use_only_cookies', 'Off');
			ini_set('session.use_cookies', 'On');
			ini_set('session.use_trans_sid', 'Off');
			ini_set('session.cookie_httponly', 'On');
		
			if (isset($_COOKIE[session_name()]) && !preg_match('/^[a-zA-Z0-9,\-]{22,52}$/', $_COOKIE[session_name()])) {
				exit('Error: Invalid session ID!');
			}
			
			session_set_cookie_params(0, '/');
			session_start();
		}			
	}
		
	public function start($key = 'default', $value = '') {
		if ($value) {
			$this->session_id = $value;
		} elseif (isset($_COOKIE[$key])) {
			$this->session_id = $_COOKIE[$key];
		} else {
			$this->session_id = $this->createId();
		}	
		
		if (!isset($_SESSION[$this->session_id])) {
			$_SESSION[$this->session_id] = array();
		}
		
		$this->data = &$_SESSION[$this->session_id];
		
		if ($key != 'PHPSESSID') {
			setcookie($key, $this->session_id, ini_get('session.cookie_lifetime'), ini_get('session.cookie_path'), ini_get('session.cookie_domain'), ini_get('session.cookie_secure'), ini_get('session.cookie_httponly'));
		}
		
		return $this->session_id;
	}	

	public function getId() {
		return $this->session_id;
	}
	
	public function createId() {
		if (version_compare(phpversion(), '5.5.4', '>') == true && method_exists($this->adaptor,'create_sid')) {
			return $this->adaptor->create_sid();
		} elseif (function_exists('random_bytes')) {
        	return substr(bin2hex(random_bytes(26)), 0, 26);
		} elseif (function_exists('openssl_random_pseudo_bytes')) {
			return substr(bin2hex(openssl_random_pseudo_bytes(26)), 0, 26);
		} else {
			return substr(bin2hex(mcrypt_create_iv(26, MCRYPT_DEV_URANDOM)), 0, 26);
		}
	}
		
	public function destroy($key = 'default') {
		if (isset($_SESSION[$key])) {
			unset($_SESSION[$key]);
		}
		
		setcookie($key, '', time() - 42000, ini_get('session.cookie_path'), ini_get('session.cookie_domain'));
	}
}