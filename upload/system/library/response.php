<?php
final class Response {
	private $headers = array(); 
	private $output;

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

	public function setOutput($output) {
		$this->output = $output;
	}

	private function compress($data, $level = 4) {
		if (strpos(@$_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
			$encoding = 'gzip';
		} 

		if (strpos(@$_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip')) {
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

		$gzdata = gzencode($data, (int)$level);

		$this->addHeader('Content-Encoding', $encoding);

		return $gzdata;
	}

	public function output($level = 4) {
		if ($level) {
			$ouput = $this->compress($this->output, $level);
		} else {
			$ouput = $this->output;
		}		
		
		foreach ($this->headers as $key => $value) {
			header($key. ': ' . $value);
		}
				
		echo $ouput;
	}
}
?>