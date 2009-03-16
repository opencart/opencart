<?php
final class Download {
	private $file;
	private $mime = 'application/octet-stream';
	private $encoding = 'binary';
	private $mask;

	public function __construct($file) {
		$this->file = $file;
	}
	
	public function setMime($mime) {
		$this->mime = $mime;
	}

	public function setEncoding($encoding) {
		$this->encoding = $encoding;
	}

	public function setMask($mask) {
		$this->mask = basename($mask);
	}

	public function output() {
		if (!headers_sent()) {
			header('Pragma: public');
			header('Expires: 0');
			header('Content-Description: File Transfer');
			header('Content-Type: ' . $this->mime);
			header('Content-Transfer-Encoding: ' . $this->encoding);
			header('Content-Disposition: attachment; filename=' . ($this->mask ? $this->mask : basename($this->file)));
			header('Content-Length: ' . filesize($this->file));
			
			if (file_exists($this->file)) {
				$file = readfile($this->file, 'rb');
				
				print($file);
			} else {
				exit('Error: Could not find file ' . $this->file . '!');
			}
		} else {
			exit('Error: Headers already sent out!');
		}
	}
}
?>