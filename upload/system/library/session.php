<?php
class Session {
	public $data = array();

	public function __construct($session_id = '') {
		ini_set('session.use_only_cookies', 'Off');
		ini_set('session.use_trans_sid', 'Off');
		ini_set('session.cookie_httponly', 'Off');		
		
		if ($session_id) {
			session_id($session_id);
		}

		session_set_cookie_params(0, '/');
		session_start();
		
		$this->data =& $_SESSION;
	}

	public function getId() {
		return session_id();
	}
	
	public function regeneratId() {
		return regenerate_id();
	}
	
	public function destroy() {
		return session_destroy();
	}
}