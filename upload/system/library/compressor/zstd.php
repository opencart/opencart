<?php
namespace Opencart\System\Library\Compressor;
/**
 * Class Zstd
 *
 * Zstandard compression adaptor implementing zstd compression format.
 *
 * @package Opencart\System\Library\Compressor
 */
class Zstd {
	private const LEVEL_MIN = 1;
	private const LEVEL_MAX = 22;

	/**
	 * Compress
	 *
	 * Compress data with Zstandard at the given compression level (1-22).
	 * Values outside the range are clamped.
	 *
	 * @param string $data  Raw input data
	 * @param int    $level Compression level (1-22)
	 *
	 * @return false|string Compressed data or false on error / missing support
	 */
	public function compress(string $data, int $level): false|string {
		if (!$this->isSupported()) {
			return false;
		}

		$level = max(self::LEVEL_MIN, min(self::LEVEL_MAX, $level));

		return zstd_compress($data, $level);
	}

	/**
	 * Decompress
	 *
	 * Decompresses zstd-encoded data back to original form.
	 *
	 * @param string $data zstd-compressed data
	 *
	 * @return false|string original uncompressed data on success, false on failure
	 */
	public function decompress(string $data): false|string {
		if (!$this->isSupported()) {
			return false;
		}

		return zstd_uncompress($data);
	}

	/**
	 * Get Extension
	 *
	 * Returns the standard file extension for zstd compressed files.
	 *
	 * @return string file extension
	 */
	public function getExtension(): string {
		return 'zst';
	}

	/**
	 * Is Supported
	 *
	 * Verifies that zstd compression functions are available.
	 *
	 * @return bool true if zstd compression is available, false if zstd extension is missing
	 */
	public function isSupported(): bool {
		return function_exists('zstd_compress') && function_exists('zstd_uncompress');
	}
}
