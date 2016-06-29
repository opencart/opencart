<?php
namespace Session;
class File extends \SessionHandler {
    private $session;
	private $handle;

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
			$this->handle = fopen($file, 'r');
			
			flock($this->handle, LOCK_SH);
			
			$data = fread($this->handle, filesize($file));
			
			flock($this->handle, LOCK_UN);
			
			fclose($this->handle);
			
			return $data;
		}
		
		return null;
	}

    public function write($session_id, $data) {
		$file = session_save_path() . '/sess_' . $session_id;
		
		$this->handle = fopen($file, 'w');
		
		flock($this->handle, LOCK_EX);

		fwrite($this->handle, $data);

		fflush($this->handle);

		flock($this->handle, LOCK_UN);
		
		fclose($this->handle);
		
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