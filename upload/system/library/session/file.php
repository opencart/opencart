<?php
namespace Session;
class File {
	private $directory;

	public function read($session_id) {
		$file = DIR_SESSION . 'sess_' . basename($session_id);

		if (is_file($file)) {
			$size = filesize($file);

			if ($size) {
				$handle = fopen($file, 'r');

				flock($handle, LOCK_SH);

				$data = fread($handle, $size);

				flock($handle, LOCK_UN);

				fclose($handle);

				return unserialize($data);
			}
		}

		return array();
	}

	public function write($session_id, $data) {
		$file = DIR_SESSION . 'sess_' . basename($session_id);

		$handle = fopen($file, 'w');

		flock($handle, LOCK_EX);

		fwrite($handle, serialize($data));

		fflush($handle);

		flock($handle, LOCK_UN);

		fclose($handle);

		return true;
	}

	public function destroy($session_id) {
		$file = DIR_SESSION . 'sess_' . basename($session_id);

		if (is_file($file)) {
			unlink($file);
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

			$files = glob(DIR_SESSION . 'sess_*');

			foreach ($files as $file) {
				if (filemtime($file) < $expire) {
					unlink($file);
				}
			}
		}
	}
}
