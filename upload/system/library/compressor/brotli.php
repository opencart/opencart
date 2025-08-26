<?php
namespace Opencart\System\Library\Compressor;
/**
 * Class Brotli
 *
 * Brotli compression adaptor implementing RFC 7932 brotli format.
 *
 * @package Opencart\System\Library\Compressor
 */
class Brotli {
	private const LEVEL_MIN = 0;
	private const LEVEL_MAX = 11;

	/**
	 * Compress
	 *
	 * Compress data with Brotli at the given compression level (0-11).
	 * Values outside the range are clamped.
	 *
	 * @param string $data  Raw input data
	 * @param int    $level Compression level (0-11)
	 *
	 * @return false|string Compressed data or false on error / missing support
	 */
	public function compress(string $data, int $level): false|string {
		if (!$this->isSupported()) {
			return false;
		}

		$level = max(self::LEVEL_MIN, min(self::LEVEL_MAX, $level));

		return brotli_compress($data, $level);
	}

	/**
	 * Decompress
	 *
	 * Decompresses brotli-encoded data back to original form.
	 *
	 * @param string $data brotli-compressed data
	 *
	 * @return false|string original uncompressed data on success, false on failure
	 */
	public function decompress(string $data): false|string {
		if (!$this->isSupported()) {
			return false;
		}

		return brotli_uncompress($data);
	}

	/**
	 * Get Extension
	 *
	 * Returns the standard file extension for brotli compressed files.
	 *
	 * @return string file extension
	 */
	public function getExtension(): string {
		return 'br';
	}

	/**
	 * Is Supported
	 *
	 * Verifies that brotli compression functions are available.
	 *
	 * @return bool true if brotli compression is available, false if brotli extension is missing
	 */
	public function isSupported(): bool {
		return function_exists('brotli_compress') && function_exists('brotli_uncompress');
	}
}
