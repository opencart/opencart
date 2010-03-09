<?php
final class Response {
	private $headers = array(); 
	private $output;
	private $level = 0;
	
	public function addHeader($key, $value) {
		$this->headers[$key] = $value;
	}

	public function removeHeader($key) {
		if (isset($this->headers[$key])) {
			unset($this->headers[$key]);
		}
	}

	public function redirect($url) {
		header('Location: ' . $url);
		exit;
	}

	public function setOutput($output, $level = 0) {
		$this->output = $output;
		$this->level = $level;
	}

	private function compress($data, $level = 0) {
		if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== FALSE)) {
			$encoding = 'gzip';
		} 

		if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip') !== FALSE)) {
			$encoding = 'x-gzip';
		}

		if (!isset($encoding)) {
			return $data;
		}

		if (!extension_loaded('zlib') || ini_get('zlib.output_compression')) {
			return $data;
		}

		if (headers_sent()) {
			return $data;
		}

		if (connection_status()) { 
			return $data;
		}
		
		$this->addHeader('Content-Encoding', $encoding);

		return gzencode($data, (int)$level);
	}

	public function output() {
		if ($this->level) {
			$ouput = $this->compress($this->output, $this->level);
		} else {
			$ouput = $this->output;
		}	
			
		if (!headers_sent()) {
			foreach ($this->headers as $key => $value) {
				header($key . ': ' . $value);
			}
		}
		
		echo $ouput;
	}
}
?>