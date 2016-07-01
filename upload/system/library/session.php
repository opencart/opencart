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
			session_start(array('read_and_close' => true));
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
		//if (version_compare(phpversion(), '5.5.0', '>') == true) {
		//	return $this->adaptor->create_sid();
		//} elseif (function_exsits('openssl')) {
			return bin2hex(openssl_random_pseudo_bytes(16));
		//} else {
			$string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
			
		//	echo getmypid();
			
			/*
			# Offset the child rand generator by its PID
			$n = (getmypid() % 100) * (10 * abs(microtime(true) - time()));
			
			for ($n; $n > 0; $n--) {
				rand(0, $n);
			}
	
	
			
			srand((double)microtime() * 1000000);
			
			echo rand() % 33;
			
			$max = strlen($string) - 1;
			
			$token = '';
			
			for ($i = 0; $i < 32; $i++) {
				$token .= $string[rand(0, $max)];
			}	
			
			
			
			$n = (getmypid() % 100) * (10 * abs(microtime(true) - time()));
for ($n; $n > 0; $n--) {
      rand(0, $n);
}
			
			*/
			
			
			
			// Hacky way of gettign a new session ID
			//md5($_SERVER['REMOTE_IP'] . microtime() . );
			
			return $new_session_id;
		//}
	}
		
	public function destroy($key = 'default') {
		if (isset($_SESSION[$key])) {
			unset($_SESSION[$key]);
		}
		
		setcookie($key, '', time() - 42000, ini_get('session.cookie_path'), ini_get('session.cookie_domain'));
	}
}