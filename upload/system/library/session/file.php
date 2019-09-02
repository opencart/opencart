<?php
namespace Session;
class File {
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

				return json_decode($data, true);
			} else {
				return array();
			}
		}

		return array();
	}

	public function write($session_id, $data) {
		$file = DIR_SESSION . 'sess_' . basename($session_id);

		$handle = fopen($file, 'c');

		flock($handle, LOCK_EX);

		fwrite($handle, json_encode($data));
		ftruncate($handle, ftell($handle));
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

	public function clean() {
		$expire = time() - ini_get('session.gc_maxlifetime');

		$files = glob(DIR_SESSION . 'sess_*');

		foreach ($files as $file) {
			if (filemtime($file) < $expire) {
				unlink($file);
			}
		}
	}
}
