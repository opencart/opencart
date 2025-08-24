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
	/**
	 * @var int compression level (1-9)
	 */
	private int $level;

	/**
	 * @var float compression ratio from last operation
	 */
	private float $ratio = 0.0;

	/**
	 * Constructor
	 *
	 * Initialize gzip compression with specified level.
	 *
	 * @param int $level compression level between 1 and 9
	 */
	public function __construct(int $level = 6) {
		$this->level = max(1, min(9, $level));
	}

	/**
	 * Compress
	 *
	 * Compresses data using gzip algorithm.
	 *
	 * @param string $data raw data to compress
	 *
	 * @return false|string compressed gzip data on success, false on failure
	 */
	public function compress(string $data): false|string {
		if (!$this->isSupported()) {
			return false;
		}

		$original_size = strlen($data);
		$compressed = gzencode($data, $this->level);

		if ($compressed !== false) {
			$compressed_size = strlen($compressed);
			$this->ratio = $original_size > 0 ? ($compressed_size / $original_size) : 0.0;
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
	 * Get Compression Ratio
	 *
	 * Returns the compression efficiency from the last compress() operation.
	 *
	 * @return float compression ratio (0.0 to 1.0)
	 */
	public function getRatio(): float {
		return $this->ratio;
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
