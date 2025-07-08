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
	 * @var int Minimum size (bytes) for compression to avoid CPU overhead on small files
	 */
	private int $min_compress_size = 1024;

	/**
	 * Constructor
	 *
	 * @param string $header
	 */
	public function addHeader(string $header): void {
		$this->headers[] = $header;
	}

	/**
	 * Remove header by name
	 *
	 * Example: removeHeader('Content-Length') will remove "Content-Length: 1234"
	 *
	 * @param string $header_name Header name to remove
	 *
	 * @return void
	 */
	public function removeHeader(string $header_name): void {
		foreach ($this->headers as $key => $header) {
			// Check if header starts with the name followed by colon
			if (stripos($header, $header_name . ':') === 0) {
				unset($this->headers[$key]);
			}
		}

		// Reindex array to maintain sequential keys
		$this->headers = array_values($this->headers);
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
	 * Set minimum size for compression
	 *
	 * @param int $size Minimum size in bytes
	 * @return void
	 */
	public function setMinCompressSize(int $size): void {
		$this->min_compress_size = max(0, $size);
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
	 * Parse Accept-Encoding header
	 * Follows RFC 9110 Section 12.5.3: https://www.rfc-editor.org/rfc/rfc9110.html#name-accept-encoding
	 * Example: "gzip, deflate, br;q=0.8, zstd;q=0.9" -> ['gzip' => 1.0, 'deflate' => 1.0, 'br' => 0.8, 'zstd' => 0.9]
	 *
	 * @param string $accept_encoding
	 *
	 * @return array<string, float>
	 */
	private function parseAcceptEncoding(string $accept_encoding): array {
		$encodings = [];
		$parts = explode(',', $accept_encoding);

		foreach ($parts as $part) {
			$part = trim($part);

			if (str_contains($part, ';')) {
				[$encoding, $quality] = explode(';', $part, 2);
				$encoding = trim($encoding);
				$q_value = 1.0;

				// Parse quality value (q=0.8)
				if (preg_match('/q=([0-9.]+)/', $quality, $matches)) {
					$q_value = (float)$matches[1];
				}

				$encodings[$encoding] = $q_value;
			} else {
				$encodings[trim($part)] = 1.0;
			}
		}

		// Handle wildcard '*' (RFC 9110: means any encoding is acceptable)
		// Add missing algorithms with wildcard quality, but don't overwrite explicit ones
		if (isset($encodings['*']) && $encodings['*'] > 0) {
			$wildcard_quality = $encodings['*'];
			$supported_algorithms = ['zstd', 'br', 'gzip', 'deflate'];

			foreach ($supported_algorithms as $algorithm) {
				// Only add if not explicitly specified
				if (!isset($encodings[$algorithm])) {
					$encodings[$algorithm] = $wildcard_quality;
				}
			}

			// Remove wildcard - it's not a real compression algorithm
			// and would interfere with getBestEncoding() logic
			unset($encodings['*']);
		}

		return $encodings;
	}

	/**
	 * Get the best available encoding
	 * Priority order: zstd > br > gzip > deflate
	 *
	 * @param array<string, float> $accepted_encodings
	 * @return string|null
	 */
	private function getBestEncoding(array $accepted_encodings): ?string {
		$priority = [
			'zstd'    => 4,
			'br'      => 3,
			'gzip'    => 2,
			'deflate' => 1
		];

		$best_encoding = null;
		$best_priority = 0;
		$best_quality = 0;

		foreach ($accepted_encodings as $encoding => $quality) {
			// Skip encodings with zero or negative quality
			if ($quality <= 0) {
				continue;
			}

			$current_priority = $priority[$encoding] ?? 0;

			// Skip if this encoding has lower priority
			if ($current_priority < $best_priority) {
				continue;
			}

			// If same priority, check quality value
			if ($current_priority === $best_priority && $quality <= $best_quality) {
				continue;
			}

			// Check if encoding extension is available on the server
			$is_available = match($encoding) {
				'zstd'            => extension_loaded('zstd'),
				'br'              => extension_loaded('brotli'),
				'gzip', 'deflate' => extension_loaded('zlib'),
				default => false
			};

			if ($is_available) {
				$best_encoding = $encoding;
				$best_priority = $current_priority;
				$best_quality = $quality;
			}
		}

		return $best_encoding;
	}

	/**
	 * Compress data using the best available compression algorithm
	 * Supports zstd, brotli, gzip, and deflate compression
	 *
	 * @param  string  $data
	 * @param  int     $level  Compression level (0-9, where 0 = no compression)
	 *
	 * @return string
	 */
	private function compress(string $data, int $level = 0): string {
		if (!isset($_SERVER['HTTP_ACCEPT_ENCODING']) || ($level < -1 || $level > 9)) {
			return $data;
		}

		if (headers_sent() || connection_status()) {
			return $data;
		}

		// Skip compression for small data to avoid unnecessary CPU overhead
		if ($this->min_compress_size > 0 && strlen($data) < $this->min_compress_size) {
			return $data;
		}

		// Parse browser's accepted encodings
		$accepted_encodings = $this->parseAcceptEncoding($_SERVER['HTTP_ACCEPT_ENCODING']);
		$best_encoding = $this->getBestEncoding($accepted_encodings);

		if (!$best_encoding) {
			return $data;
		}

		$compressed_data = match($best_encoding) {
			'zstd'    => zstd_compress($data, $level),
			'br'      => brotli_compress($data, $level),
			'gzip'    => !ini_get('zlib.output_compression') ? gzencode($data, $level) : false,
			'deflate' => !ini_get('zlib.output_compression') ? gzdeflate($data, $level) : false,
			default => false
		};

		// Check for successful compression
		// false = compression failed or zlib.output_compression is enabled
		if ($compressed_data !== false) {
			// Remove Content-Length header as compressed size will differ
			$this->removeHeader('Content-Length');

			$this->addHeader('Content-Encoding: ' . $best_encoding);
			$this->addHeader('Content-Length: ' . strlen($compressed_data));

			// Add Vary for proper caching (proxies/CDNs need to know encoding varies)
			$this->addHeader('Vary: Accept-Encoding');

			return $compressed_data;
		}

		// Return original data if compression unavailable or failed
		return $data;
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
				foreach ($this->headers as $header) {
					header($header);
				}
			}

			echo $output;
		}
	}
}
