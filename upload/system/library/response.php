<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/

/**
* Response class
 *
 * Stores the response so the correct headers can go out before the response output is shown.
 *
*/
namespace Opencart\System\Library;
class Response {
	private $headers = [];
	private $level = 0;
	private $output;

	/**
	 * Constructor
	 *
	 * @param	string	$header
	 *
 	*/
	public function addHeader($header) {
		$this->headers[] = $header;
	}
	
	/**
	 * Redirect
	 *
	 * @param	string	$url
	 * @param	int		$status
	 *
 	*/
	public function redirect($url, $status = 302) {
		header('Location: ' . str_replace(['&amp;', "\n", "\r"], ['&', '', ''], $url), true, $status);
		exit();
	}
	
	/**
	 * Set Compression
	 *
	 * @param	int		$level
 	*/
	public function setCompression($level) {
		$this->level = $level;
	}
	
	/**
	 * Get Output
	 *
	 * @return	array
 	*/
	public function getOutput() {
		return $this->output;
	}
	
	/**
	 * Set Output
	 *
	 * @param	string	$output
 	*/	
	public function setOutput($output) {
		$this->output = $output;
	}
	
	/**
	 * Compress
	 *
	 * @param	string	$data
	 * @param	int		$level
	 * 
	 * @return	string
 	*/
	private function compress($data, $level = 0) {
		if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false)) {
			$encoding = 'gzip';
		}

		if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip') !== false)) {
			$encoding = 'x-gzip';
		}

		if (!isset($encoding) || ($level < -1 || $level > 9)) {
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

		$this->addHeader('Content-Encoding: ' . $encoding);

		return gzencode($data, (int)$level);
	}
	
	/**
	 * Output
	 *
	 * Displays the set HTML output
 	*/
	public function output() {
		if ($this->output) {
			$output = $this->level ? $this->compress($this->output, $this->level) : $this->output;
			
			if (!headers_sent()) {
				foreach ($this->headers as $header) {
					header($header, true);
				}
			}
			
			echo $output;
		}
	}
}
