<?php
class Session {
	public $session_id;
	public $data = array();
	private $adaptor;

	public function __construct($session_id, $adaptor = 'file') {
		if (preg_match('/^[a-zA-Z0-9,\-]{22,52}$/', $session_id)) {
			$this->session_id = $session_id;
		} else {
			exit('Error: Invalid session ID!');
		}
		
		if (is_object($adaptor)) {
			$this->adaptor = $adaptor;
		} else {
			throw new \Exception('Error: Could not load session adaptor ' . $adaptor . ' session!');
		}
		
		$this->data = $this->adaptor->read($this->session_id);
	}
	
	public function getId() {
		return $this->session_id;
	}
	
	public function __destruct() {
		$this->adaptor->write($this->session_id, $this->data);
	}
		
	public function __destory() {
		setcookie(session_name(), '', time() - 42000, ini_get('session.cookie_path'), ini_get('session.cookie_domain'));
	
		$this->adaptor->destroy($this->session_id);
	}
}