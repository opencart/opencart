<?php
final class Session {
	public $data = array();
			
  	public function __construct() {		
		if (!session_id()) {
			ini_set('session.use_cookies', 'On');
			ini_set('session.use_trans_sid', 'Off');
			
			if (isset($_COOKIE[session_name()])) {
				session_id($_COOKIE[session_name()]);
			}
			
			session_start();
			setcookie(session_name(), session_id(), 0, '/', ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false);		
		}
		
		$this->data =& $_SESSION;
	}
}
?>