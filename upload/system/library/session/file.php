<?php
namespace Session;
class File {
	private $directory;

	public function __construct() {
		$pos = strrpos(session_save_path(), ';');

		if ($pos === false) {
			$this->directory = session_save_path();
		} else {
			$this->directory = substr(session_save_path(), $pos + 1);
		}
	}

	public function read($session_id) {
		$file = $this->directory . '/sess_' . basename($session_id);

		if (is_file($file)) {
			$handle = fopen($file, 'r');

			flock($handle, LOCK_SH);

			$data = fread($handle, filesize($file));

			flock($handle, LOCK_UN);

			fclose($handle);

			return unserialize($data);
		} else {
			return array();
		}
	}

	public function write($session_id, $data) {
		$file = $this->directory . '/sess_' . basename($session_id);

		$handle = fopen($file, 'w');

		flock($handle, LOCK_EX);

		fwrite($handle, serialize($data));

		fflush($handle);

		flock($handle, LOCK_UN);

		fclose($handle);

		return true;
	}

	public function destroy($session_id) {
		$file = $this->directory . '/sess_' . basename($session_id);

		if (is_file($file)) {
			unset($file);
		}
	}

	public function __destruct() {
		if (ini_get('session.gc_divisor')) {
			$gc_divisor = ini_get('session.gc_divisor');
		} else {
			$gc_divisor = 1;
		}

		if (ini_get('session.gc_probability')) {
			$gc_probability = ini_get('session.gc_probability');
		} else {
			$gc_probability = 1;
		}

		if ((rand() % $gc_divisor) < $gc_probability) {
			$expire = time() - ini_get('session.gc_maxlifetime');

			$files = glob($this->directory . '/sess_*');

			foreach ($files as $file) {
				if (filemtime($file) < $expire) {
					unlink($file);
				}
			}
		}
	}
}