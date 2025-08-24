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
	/**
	 * @var int compression level (1-22)
	 */
	private int $level;

	/**
	 * @var float compression ratio from last operation
	 */
	private float $ratio = 0.0;

	/**
	 * Constructor
	 *
	 * Initialize zstd compression with specified level.
	 *
	 * @param int $level compression level between 1 and 22
	 */
	public function __construct(int $level = 6) {
		$this->level = max(1, min(22, $level));
	}

	/**
	 * Compress
	 *
	 * Compresses data using zstd algorithm.
	 *
	 * @param string $data raw data to compress
	 *
	 * @return false|string compressed zstd data on success, false on failure
	 */
	public function compress(string $data): false|string {
		if (!$this->isSupported()) {
			return false;
		}

		$original_size = strlen($data);
		$compressed = zstd_compress($data, $this->level);

		if ($compressed !== false) {
			$compressed_size = strlen($compressed);
			$this->ratio = $original_size > 0 ? ($compressed_size / $original_size) : 0.0;
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
	 * Get Compression Ratio
	 *
	 * Returns compression efficiency from the last compress() operation.
	 *
	 * @return float compression ratio (0.0 to 1.0)
	 */
	public function getRatio(): float {
		return $this->ratio;
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
