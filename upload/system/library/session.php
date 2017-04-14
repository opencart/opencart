<?php
class Session {
	protected $adaptor;
	protected $session_id;
	public $data = array();

	public function __construct($adaptor, $registry = '') {
		$class = 'Session\\' . $adaptor;
		
		if ($registry) {
			$this->adaptor = new $class($registry);
		} else {
			$this->adaptor = new $class();
		}
		
		register_shutdown_function(array($this, 'close'));
	}
	
	public function getId() {
		return $this->session_id;
	}
	
	public function start($session_id = '') {
		if (!$session_id) {
			if (function_exists('random_bytes')) {
				$session_id = substr(bin2hex(random_bytes(26)), 0, 26);
			} elseif (function_exists('openssl_random_pseudo_bytes')) {
				$session_id = substr(bin2hex(openssl_random_pseudo_bytes(26)), 0, 26);
			} else {
				$session_id = substr(bin2hex(mcrypt_create_iv(26, MCRYPT_DEV_URANDOM)), 0, 26);
			}
		}

		if (preg_match('/^[a-zA-Z0-9,\-]{22,52}$/', $session_id)) {
			$this->session_id = $session_id;
		} else {
			exit('Error: Invalid session ID!');
		}
		
		$this->data = $this->adaptor->read($session_id);
		
		return $session_id;
	}

	public function close() {
		$this->adaptor->write($this->session_id, $this->data);
	}
				
	public function __destruct() {
		$this->adaptor->write($this->session_id, $this->data);
	}
		
	public function __destory() {
		//$this->adaptor->destory($this->session_id);
	}
}