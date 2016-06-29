<?php
class Session {
	private $adaptor = '';
	public $session_id = '';
	public $data = array();

	public function __construct($adaptor = 'file') {
		$class = 'Session\\' . $adaptor;
		
		if (class_exists($class)) {
			$this->adaptor = new $class($this);
		} else {
			throw new \Exception('Error: Could not load session adaptor ' . $adaptor . ' session!');
		}		
		
		if ($this->adaptor) {
			session_set_save_handler($this->adaptor);
		}
	}
	
	public function start($session_id = '', $session_name = '') {
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
		
		if (!session_id()) { 
			session_start();
		
		
		}
		
		$this->adaptor->open(session_save_path(), $session_name);
		
		$this->data = unserialize($this->adaptor->read($this->session_id));
		
		return true;
	}	

	public function getId() {
		return $this->session_id;
	}

	public function createId() {
		if (version_compare(phpversion(), '5.5.0', '>') == true) {
			return $this->adaptor->create_sid();
		} else {
			return mt_rand(0, 0xffff);
		}
	}
		
	public function close() {
		$this->adaptor->write($this->session_id, serialize($this->data));
		$this->adaptor->close();
	}
		
	public function destroy() {
		$this->adaptor->destroy($this->session_id);
		$this->adaptor->close();
	}
	
	public function __destruct() {
		$this->adaptor->write($this->session_id, serialize($this->data));
		$this->adaptor->close();
	}
}