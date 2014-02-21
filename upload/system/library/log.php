<?php
class Log {
	private $filehandle;

	public function __construct($filename) {
		$file = DIR_LOGS . $filename;
		$this->filehandle = fopen($file, 'a');
	}

	public function __destruct() {
		fclose($this->filehandle); 
	}

	public function write($message) {
		fwrite($this->filehandle, date('Y-m-d G:i:s') . ' - ' . $message . "\n");
	}
}
