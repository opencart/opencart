<?php
class Log {
	private $handle;

	public function __construct($filename) {

		if(!is_dir(DIR_LOGS)) {
            mkdir(DIR_LOGS,0777,true);
        }
        
		$this->handle = fopen(DIR_LOGS . $filename, 'a');
	}

	public function write($message) {
		fwrite($this->handle, date('Y-m-d G:i:s') . ' - ' . print_r($message, true) . "\n");
	}

	public function __destruct() {
		fclose($this->handle);
	}
}