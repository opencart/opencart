<?php
class Session {
	protected $session_id;
	public $data = array();

	public function __construct($session_id) {
		if (preg_match('/^[a-zA-Z0-9,\-]{22,52}$/', $session_id)) {
			$this->session_id = $session_id;
		} else {
			exit('Error: Invalid session ID!');
		}
		
		$file = session_save_path() . '/sess_' . $session_id;
		
		if (is_file($file)) {
			$handle = fopen($file, 'r');
			
			flock($handle, LOCK_SH);
			
			$data = fread($handle, filesize($file));
			
			flock($handle, LOCK_UN);
			
			fclose($handle);
			
			$this->data = unserialize($data);
		}
	}
	
	public function getId() {
		return $this->session_id;
	}
	
	public function __destruct() {
		$file = session_save_path() . '/sess_' . $this->session_id;
		
		$handle = fopen($file, 'w');
		
		flock($handle, LOCK_EX);

		fwrite($handle, serialize($this->data));

		fflush($handle);

		flock($handle, LOCK_UN);
		
		fclose($handle);
		
		if ((rand() % ini_get('session.gc_divisor')) < ini_get('session.gc_probability')) {
			$expire = time() - ini_get('session.gc_maxlifetime');
			
			$files = glob(session_save_path() . '/sess_');
				
			foreach ($files as $file) {
				if (filemtime($file) < $expire) {
					unlink($file);
				}
			}
		}				
	}
		
	public function __destory() {
		$file = session_save_path() . '/sess_' . $session_id;
		
		if (is_file($file)) {
			unset($file);
		}		
		
		setcookie(session_name(), '', time() - 42000, ini_get('session.cookie_path'), ini_get('session.cookie_domain'));
	}
}