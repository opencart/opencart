<?php
class Session {
	public $data = array();

	public function __construct($session_id = '') {
		if (!session_id()) {
			ini_set('session.use_only_cookies', 'On');
			ini_set('session.use_cookies', 'On');
			ini_set('session.use_trans_sid', 'Off');
			ini_set('session.cookie_httponly', 'On');	
			ini_set('session.hash_function', 1);
			ini_set('session.hash_bits_per_character', 4);
		
			if (!preg_match('/^[0-9a-z]*$/i', session_id())) {
				exit();
			}
			
			if ($session_id) {
				session_id($session_id);
			}
		
			session_set_cookie_params(0, '/');
		
			session_start();
		}	
		
		$this->data =& $_SESSION;			
	}

	public function regenerateId() {
		return session_regenerate_id();
	}
			
	public function getId() {
		return session_id();
	}
	
	public function close() {
		session_write_close();
	}	
	
	public function reset() {
		session_reset();
	}
		
	public function abort() {
		session_abort();
	}
	
	public function destroy() {
		return session_destroy();
	}
}