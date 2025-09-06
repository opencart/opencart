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
	 * @var int
	 */
	private int $original_size = 0;
	/**
	 * @var int
	 */
	private int $compressed_size = 0;
	/**
	 * @var int
	 */
	private int $last_level = 0;

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
		$this->last_level = $level;

		$this->original_size = strlen($data);
		$compressed = gzencode($data, $level);

		if ($compressed !== false) {
			$this->compressed_size = strlen($compressed);
		} else {
			// Reset on failure
			$this->compressed_size = 0;
		}

		return $compressed;
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

	/**
	 * Get original size from last successful compression.
	 *
	 * @return int
	 */
	public function getOriginalSize(): int {
		return $this->original_size;
	}

	/**
	 * Get compressed size from last successful compression.
	 *
	 * @return int
	 */
	public function getCompressedSize(): int {
		return $this->compressed_size;
	}

	/**
	 * Get level used in last compression.
	 *
	 * @return int
	 */
	public function getLastLevel(): int {
		return $this->last_level;
	}

	/**
	 * Reset internal statistics (sizes, level).
	 *
	 * @return void
	 */
	public function reset(): void {
		$this->original_size = 0;
		$this->compressed_size = 0;
		$this->last_level = 0;
	}
}
