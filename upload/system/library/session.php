<?php
class Session {
	public $data = array();

	public function __construct($session_id = '') {
		if (!session_id()) {
			if ($session_id) {
				session_id($session_id);
			}			
			
			ini_set('session.use_only_cookies', 'On');
			ini_set('session.use_cookies', 'On');
			ini_set('session.use_trans_sid', 'Off');
			ini_set('session.cookie_httponly', 'On');
			
			session_set_cookie_params(0, '/');
			session_start();
		}	
		
		$this->data =& $_SESSION;
	}

	public function getId($session_id = '') {
		if ($session_id) {
			return session_id($session_id);
		} else {
			return session_id();
		}
	}
	
	public function regenerateId($delete = false) {
		return session_regenerate_id($delete);
	}
	
	public function start() {
		return session_start();
	}

	public function commit() {
		session_commit();
	}
	
	public function reset() {
		session_reset();
	}
		
	public function destroy() {
		return session_destroy();
	}
}