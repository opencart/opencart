<?php
class Session {
	private $session_id;
	public $data = array();

	public function __construct($session_id = '') {
		// Garbage collection
		$files = glob(ini_get('session.save_path') . '/oc.*');

		if ($files) {
			foreach ($files as $file) {
				if (filemtime($file) < (time() - ini_get('session.gc_maxlifetime'))) {
					if (file_exists($file)) {
						unlink($file);
					}
				}
			}
		}

		if (!preg_match('/^[0-9a-z]*$/i', $session_id)) {
			exit();
		}

		$this->session_id = $session_id;
	}

	public function getId() {
		return $this->session_id;
	}

	public function start($name = 'PHPSESSID', $expire = 0) {
		if (!$this->session_id) {
			if (isset($_COOKIE[$name])) {
				$this->session_id = $_COOKIE[$name];
			} else {
				$this->session_id = bin2hex(openssl_random_pseudo_bytes(16));
			}
		}

		// Protect the session from starting if invalid characters
		if (!preg_match('/^[0-9a-z]*$/i', $this->session_id)) {
			exit();
		}

		setcookie($name, $this->session_id, $expire, '/');

		$file = ini_get('session.save_path') . '/oc.' . $this->session_id;

		if (is_file($file)) {
			$data = file_get_contents($file);

			if ($data) {
				$this->data = unserialize($data);
			}

			return true;
		} else {
			return false;
		}
	}

	public function destroy() {
		$file = ini_get('session.save_path') . '/oc.' . $this->session_id;

		if (is_file($file)) {
			@unlink($file);

			$this->session_id = '';
			$this->data = array();

			return true;
		} else {
			return false;
		}
	}

	public function __destruct() {
		if ($this->session_id) {
			$file = ini_get('session.save_path') . '/oc.' . $this->session_id;

			$handle = fopen($file, 'w');

			flock($handle, LOCK_EX);

			fwrite($handle, serialize((array)$this->data));

			fflush($handle);

			flock($handle, LOCK_UN);

			fclose($handle);
		}
	}
}
