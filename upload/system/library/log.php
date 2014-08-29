<?php
class Log {
	private $handle;

	public function __construct($filename) {
		$file = DIR_LOGS . $filename;
		$this->handle = fopen($file, 'a');
	}

	public function __destruct() {
		fclose($this->handle);
	}

	public function write($message) {
		fwrite($this->handle, date('Y-m-d G:i:s') . ' - ' . print_r($message, true) . "\n");
	}
}