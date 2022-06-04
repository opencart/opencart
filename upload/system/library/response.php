<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2022, OpenCart, Ltd. (https://www.opencart.com/)
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
	private array $headers = [];
	private int $level = 0;
	private string $output = '';

	/**
	 * Constructor
	 *
	 * @param	string	$header
	 *
 	*/
	public function addHeader(string $header): void {
		$this->headers[] = $header;
	}

	/**
	 * Get Headers
	 *
	 * @param	array
	 *
 	*/
	public function getHeaders(): array {
		return $this->headers;
	}

	/**
	 * Redirect
	 *
	 * @param	string	$url
	 * @param	int		$status
	 *
 	*/
	public function redirect(string $url, int $status = 302): void {
		header('Location: ' . str_replace(['&amp;', "\n", "\r"], ['&', '', ''], $url), true, $status);
		exit();
	}

	/**
	 * Set Compression
	 *
	 * @param	int		$level
 	*/
	public function setCompression(int $level): void {
		$this->level = $level;
	}

	/**
	 * Set Output
	 *
	 * @param	string	$output
 	*/	
	public function setOutput(string $output): void {
		$this->output = $output;
	}

	/**
	 * Get Output
	 *
	 * @return	array
	 */
	public function getOutput(): string {
		return $this->output;
	}

	/**
	 * Compress
	 *
	 * @param	string	$data
	 * @param	int		$level
	 * 
	 * @return	string
 	*/
	private function compress(string $data, int $level = 0): string {
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

		return gzencode($data, $level);
	}

	/**
	 * Output
	 *
	 * Displays the set HTML output
 	*/
	public function output(): void {
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
