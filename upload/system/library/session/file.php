<?php
namespace Session;
class File extends \SessionHandler {
    public function create_sid() {
        return parent::create_sid();
    }

    public function open($path, $name) {
        return true;
    }

    public function close() {
        return true;
    }
	
    public function read($session_id) {
		$file = session_save_path() . '/sess_' . $session_id;
		
		if (is_file($file)) {
			$handle = fopen($file, 'r');
			
			flock($handle, LOCK_SH);
			
			$data = fread($handle, filesize($file));
			
			flock($handle, LOCK_UN);
			
			fclose($handle);
			
			return $data;
		}
		
		return null;
	}

    public function write($session_id, $data) {
		$file = session_save_path() . '/sess_' . $session_id;
		
		$handle = fopen($file, 'w');
		
		flock($handle, LOCK_EX);

		fwrite($handle, $data);

		fflush($handle);

		flock($handle, LOCK_UN);
		
		fclose($handle);
		
		return true;
    }

    public function destroy($session_id) {
		$file = session_save_path() . '/sess_' . $session_id;
		
		if (is_file($file)) {
			unset($file);
		}
    }

    public function gc($maxlifetime) {
        return parent::gc($maxlifetime);
    }	
}