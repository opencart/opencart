<?php
class Session {
	public $data = array();

	public function __construct($session_id = '',  $key = 'default') {
		ini_set('session.use_only_cookies', 'Off');
		ini_set('session.use_cookies', 'On');
		ini_set('session.use_trans_sid', 'Off');
		ini_set('session.cookie_httponly', 'On');

		if ($session_id) {
			session_id($session_id);
		}

		if (!preg_match('/^[0-9a-z]*$/i', session_id())) {
			exit();
		}

		session_set_cookie_params(0, '/');
		session_start();

		if (!isset($_SESSION[$key])) {
			$_SESSION[$key] = array();
		}

		$this->data =& $_SESSION[$key];
		
	        $this->data['HTTP_REFERER'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
	        $this->data['USER_AGENT'] = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
	        $this->data['CLIENT_IP'] = $this->getRealIpAddr();
	        $this->data['IS_MOBILE'] = $this->isMobile();
	}

	public function getId() {
		return session_id();
	}

	public function destroy() {
		return session_destroy();
	}
	private function isMobile() {
	        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
	}
	
	public function getRealIpAddr() {
	        if (isset($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
	            $ip = $_SERVER['HTTP_CLIENT_IP'];
	        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {  //to check ip is pass from proxy
	            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
	            $ip = $_SERVER['REMOTE_ADDR'];
	        } else {
	            $ip = "UNKNOWN";
	        }
	        return $ip;
	}
}
