<?php
class Session {
	public $data = array();
			
  	public function __construct($session_id = '') {		
		ini_set('session.use_cookies', 'On');
		ini_set('session.use_trans_sid', 'Off');
		
		if ($session_id) {
			session_id($session_id);
		}
		
		if (preg_match('/^[0-9a-z]*$/i', session_id())) {
			session_set_cookie_params(0, '/');
			session_start();
			
			$this->data =& $_SESSION;
		}
	}
	
	function getId() {
		return session_id();
	}
}
?>