<?php
class Session {
	private $adaptor = '';
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
			session_set_save_handler(
				array(&$this->adaptor, 'open'),
				array(&$this->adaptor, 'close'),
				array(&$this->adaptor, 'read'),
				array(&$this->adaptor, 'write'),
				array(&$this->adaptor, 'destroy'),
				array(&$this->adaptor, 'gc')
			);	
		}
	}

	public function getId() {
		return $this->session_id;
	}

	public function createId() {
		return $this->adaptor->create_sid();
	}
	
	public function start($session_id = '', $session_name = '') {
		ini_set('session.use_only_cookies', 'Off');
		ini_set('session.use_cookies', 'On');
		ini_set('session.use_trans_sid', 'Off');
		ini_set('session.cookie_httponly', 'On');
					
		if (!$session_name) {
			$session_name = session_name();
		}
						
		if ($session_id) {
			$this->session_id = $session_id;
		} elseif (isset($_COOKIE[$session_name])) {
			$this->session_id = $_COOKIE[$session_name];
		} else {
			$this->session_id = $this->createId();
		}
		
		if (!preg_match('/^[a-zA-Z0-9,\-]{22,52}$/', $this->session_id)) {
			exit('Error: Invalid session ID!');
		}
		
		setcookie($session_name, $this->session_id, ini_get('session.cookie_lifetime'), ini_get('session.cookie_path'), ini_get('session.cookie_domain'), ini_get('session.cookie_secure'), ini_get('session.cookie_httponly'));
		
		session_start();
		
		return true;
	}	
	
	public function close() {
		return $this->adaptor->close();
	}
		
	public function destroy() {
		return $this->adaptor->destroy($this->session_id);
	}
}