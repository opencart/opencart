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
					
			session_set_cookie_params(0, '/');
		
			session_start();
		}	
		
		$this->data =& $_SESSION;			
	}
	
	function php_combined_lcg() {
		$tv = gettimeofday();
		$lcg['s1'] = $tv['sec'] ^ (~$tv['usec']);
		$lcg['s2'] = posix_getpid();

		$q = (int) ($lcg['s1'] / 53668);
		$lcg['s1'] = (int) (40014 * ($lcg['s1'] - 53668 * $q) - 12211 * $q);
		if ($lcg['s1'] < 0)
			$lcg['s1'] += 2147483563;

		$q = (int) ($lcg['s2'] / 52774);
		$lcg['s2'] = (int) (40692 * ($lcg['s2'] - 52774 * $q) - 3791 * $q);
		if ($lcg['s2'] < 0)
			$lcg['s2'] += 2147483399;

		$z = (int) ($lcg['s1'] - $lcg['s2']);
		if ($z < 1) {
			$z += 2147483562;
		}

		return $z * 4.656613e-10;
	}
			
	function session_regenerate_id() {
		$tv = gettimeofday();
		
		$buf = sprintf("%.15s%ld%ld%0.8f", $_SERVER['REMOTE_ADDR'], $tv['sec'], $tv['usec'], php_combined_lcg() * 10);
		
		session_id(md5($buf));
		
		setcookie('PHPSESSID', session_id(), NULL, '/');
		
		return TRUE;
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
	
	public function start($name = '', $limit = 0, $path = '/', $domain = null, $secure = null) {
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
		} else {
			return false;
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