<?php
namespace Opencart\System\Library\Compressor;
/**
 * Class Gzip
 *
 * Gzip compression adaptor implementing RFC 1952 gzip format.
 *
 * @package Opencart\System\Library\Compressor
 */
class Gzip {
	private const LEVEL_MIN = 1;
	private const LEVEL_MAX = 9;

	/**
	 * Compress
	 *
	 * Compress data with gzip at the given compression level.
	 * Level range: 1-9. Values outside the range are clamped.
	 *
	 * @param string $data  Raw input data
	 * @param int    $level Compression level (1-9)
	 *
	 * @return false|string Compressed data or false on error / missing support
	 */
	public function compress(string $data, int $level): false|string {
		if (!$this->isSupported()) {
			return false;
		}

		$level = max(self::LEVEL_MIN, min(self::LEVEL_MAX, $level));

		return gzencode($data, $level);
	}

	/**
	 * Decompress
	 *
	 * Decompresses gzip-encoded data back to original form.
	 *
	 * @param string $data gzip-compressed data
	 *
	 * @return false|string original uncompressed data on success, false on failure
	 */
	public function decompress(string $data): false|string {
		if (!$this->isSupported()) {
			return false;
		}

		return gzdecode($data);
	}

	/**
	 * Get Extension
	 *
	 * Returns the standard file extension for gzip compressed files.
	 *
	 * @return string file extension
	 */
	public function getExtension(): string {
		return 'gz';
	}

	/**
	 * Is Supported
	 *
	 * Verifies that gzip compression functions are available.
	 *
	 * @return bool true if gzip compression is available, false if zlib extension is missing
	 */
	public function isSupported(): bool {
		return function_exists('gzencode') && function_exists('gzdecode');
	}
}
