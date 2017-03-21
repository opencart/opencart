<?php
namespace Session;
class File {
    public function read($session_id) {
		$file = session_save_path() . '/sess_' . basename($session_id);
		
		$handle = fopen($file, 'r');
		
		flock($handle, LOCK_SH);
		
		$data = fread($handle, filesize($file));
		
		flock($handle, LOCK_UN);
		
		fclose($handle);
		
		return unserialize($data);
	}

    public function write($session_id, $data) {
		$file = session_save_path() . '/sess_' . basename($session_id);
		
		$handle = fopen($file, 'w');
		
		flock($handle, LOCK_EX);

		fwrite($handle, serialize($data));

		fflush($handle);

		flock($handle, LOCK_UN);
		
		fclose($handle);
		
		return true;
	}
	
    public function destroy($session_id) {
		$file = session_save_path() . '/sess_' . basename($session_id);
		
		if (is_file($file)) {
			unset($file);
		}
    }

    public function __destruct() {
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
}