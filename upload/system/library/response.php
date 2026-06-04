<?php
/**
 * @package		OpenCart
 *
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2022, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 *
 * @see		https://www.opencart.com
 */
namespace Opencart\System\Library;
/**
 * Class Response
 *
 * Stores the response so the correct headers can go out before the response output is shown.
 */
class Response {
	/**
	 * @var array<int, string>
	 */
	private array $headers = [];
	/**
	 * @var int
	 */
	private int $level = 0;
	/**
	 * @var string
	 */
	private string $output = '';

	/**
	 * Constructor
	 *
	 * @param string $header
	 */
	public function addHeader(string $header): void {
		$this->headers[] = $header;
	}

	/**
	 * Get Headers
	 *
	 * @return array<int, string>
	 */
	public function getHeaders(): array {
		return $this->headers;
	}

	/**
	 * Redirect
	 *
	 * @param string $url
	 * @param int    $status
	 *
	 * @return void
	 */
	public function redirect(string $url, int $status = 302): void {
		header('Location: ' . str_replace(['&amp;', "\n", "\r"], ['&', '', ''], $url), true, $status);
		exit();
	}

	/**
	 * Set Compression
	 *
	 * @param int $level
	 *
	 * @return void
	 */
	public function setCompression(int $level): void {
		$this->level = $level;
	}

	/**
	 * Set Output
	 *
	 * @param string $output
	 *
	 * @return void
	 */
	public function setOutput(string $output): void {
		$this->output = $output;
	}

	/**
	 * Get Output
	 *
	 * @return string
	 */
	public function getOutput(): string {
		return $this->output;
	}

	/**
	 * Compress
	 *
	 * @param string $data
	 * @param int    $level
	 *
	 * @return string
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
	 * Add Security Headers
	 *
	 * CWE-20: Protection against clickjacking, MIME sniffing, and XSS
	 *
	 * @return void
	 */
	private function addSecurityHeaders(): void {
		// CWE-1033: X-Frame-Options prevents clickjacking attacks
		$this->addHeader('X-Frame-Options: SAMEORIGIN');

		// CWE-16: X-Content-Type-Options prevents MIME-type sniffing
		$this->addHeader('X-Content-Type-Options: nosniff');

		// CWE-79: X-XSS-Protection for legacy browser support
		$this->addHeader('X-XSS-Protection: 1; mode=block');

		// CWE-1023: Content-Security-Policy to prevent XSS and data injection
		// Note: Configure this CSP policy according to your application needs
		// This is a restrictive default that allows only same-origin resources
		$this->addHeader("Content-Security-Policy: default-src 'self'");
	}

	/**
	 * Output
	 *
	 * Displays the set HTML output
	 *
	 * @return void
	 */
	public function output(): void {
		if ($this->output) {
			$output = $this->level ? $this->compress($this->output, $this->level) : $this->output;

			if (!headers_sent()) {
				// Add security headers before other headers
				$this->addSecurityHeaders();

				foreach ($this->headers as $header) {
					header($header, true);
				}
			}

			echo $output;
		}
	}
}
