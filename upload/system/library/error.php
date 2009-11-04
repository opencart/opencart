<?php
final class Error {
	private $filename;
	private $error_display;
	private $error_log;
	
	public function __construct($filename, $error_display = '', $error_log = '') {
		$this->filename = $filename;
		$this->error_display = $error_display;
		$this->error_log = $error_log;
	}
	
	public function handle_error($code, $message, $file, $line) {
		switch ($code) {
			case E_NOTICE:
			case E_USER_NOTICE:
				$error = "Notice";
				break;
			case E_WARNING:
			case E_USER_WARNING:
				$error = "Warning";
				break;
			case E_ERROR:
			case E_USER_ERROR:
				$error = "Fatal Error";
				break;
			default:
				$error = "Unknown";
				break;
		}
		
    	if ($this->error_display) {
        	print('<b>' . $error . '</b>: ' . $message . ' in <b>' .$file . '</b> on line <b>' . $line . '</b>');
		}
	
		if ($this->error_log) {
			$file = DIR_LOGS . $this->filename;
		
			$handle = fopen($file, 'a+'); 
		
			fwrite($handle, date('Y-m-d G:i:s') . ' - PHP ' . $error . ':  ' . $message . ' in ' . $file . ' on line ' . $line . "\n");
			
			fclose($handle); 			
		}
	}
}
?>