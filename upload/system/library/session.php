<?php
class Session {
	public $data = array();

	public function __construct() {
		if (!session_id()) {
			ini_set('session.use_only_cookies', 'On');
			ini_set('session.use_cookies', 'On');
			ini_set('session.use_trans_sid', 'Off');
			ini_set('session.cookie_httponly', 'On');	
			
			session_set_cookie_params(0, '/');
		
			session_start();
		}	
		
		$this->data =& $_SESSION;			
	}
	
	public function getId() {
		return session_id();
	}
	
	public function setId($session_id = '') {
		return session_id($session_id);
	}
		
	public function getName() {
		return session_name();
	}
		
	public function setName() {
		return session_name();
	}
	
	public function start() {
		if (!session_id()) {
			ini_set('session.use_only_cookies', 'On');
			ini_set('session.use_cookies', 'On');
			ini_set('session.use_trans_sid', 'Off');
			ini_set('session.cookie_httponly', 'On');	

			session_set_cookie_params(0, '/');
		
			if (!preg_match('/^[0-9a-z]*$/i', session_id())) {
				exit();
			}
					
			return session_start();
		}	
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