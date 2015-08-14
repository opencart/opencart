<?php
class Session {
	public $data = array();

	public function __construct($session_id = '',  $key = 'default') {
		if (!session_id()) {
			ini_set('session.use_only_cookies', 'Off');
			ini_set('session.use_cookies', 'On');
			ini_set('session.use_trans_sid', 'Off');
			ini_set('session.cookie_httponly', 'On');

			if ($session_id) {
				session_id($session_id);
			}

			session_set_cookie_params(0, '/');
			session_start();
		}

		if (!preg_match('/^[0-9a-z]*$/i', session_id())) {
			exit();
		}

		if (!isset($_SESSION[$key])) {
			$_SESSION[$key] = array();
		}

		$this->data =& $_SESSION[$key];
	}

	public function getId() {
		return session_id();
	}

	public function destroy() {
		return session_destroy();
	}
}
