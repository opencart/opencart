<?php
class Session {
	public $data = array();

	public function __construct($driver = '') {
		$args = func_get_args();
		
		array_shift($args);
		
		$class = 'Session\\' . $driver;
		
		if ($driver && class_exists($class)) {
			call_user_func($class, $registry);
		
			session_set_save_handler(
				array($session, 'open'),
				array($session, 'close'),
				array($session, 'read'),
				array($session, 'write'),
				array($session, 'destroy'),
				array($session, 'gc')
			);	
		}
	}

	public function getId() {
		return session_id();
	}

	public function start($session_id = '', $key = 'default') {
		if (!session_id()) {
			ini_set('session.use_only_cookies', 'Off');
			ini_set('session.use_cookies', 'On');
			ini_set('session.use_trans_sid', 'Off');
			ini_set('session.cookie_httponly', 'On');
		
			if ($session_id) {
				session_id($session_id);
			}	
				
			if (isset($_COOKIE[session_name()]) && !preg_match('/^[a-zA-Z0-9,\-]{22,52}$/', $_COOKIE[session_name()])) {
				exit('Error: Invalid session ID!');
			}

			session_set_cookie_params(0, '/');
			session_start();
		}
		
		if (!isset($_SESSION[$key])) {
			$_SESSION[$key] = array();
		}
				
		$this->data =& $_SESSION[$key];	
		
		return true;			
	}

	public function destroy() {
		return session_destroy();
	}
}
