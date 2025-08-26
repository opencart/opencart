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
		$this->last_level = $level;
		$this->original_size = strlen($data);

		$compressed = zstd_compress($data, $level);

		if ($compressed !== false) {
			$this->compressed_size = strlen($compressed);
		} else {
			$this->compressed_size = 0;
		}

		return $compressed;
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
