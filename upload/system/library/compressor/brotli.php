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
	/**
	 * @var int compression level (0-11)
	 */
	private int $level;

	/**
	 * @var float compression ratio from last operation
	 */
	private float $ratio = 0.0;

	/**
	 * Constructor
	 *
	 * Initialize brotli compression with specified level.
	 *
	 * @param int $level compression level between 0 and 11
	 */
	public function __construct(int $level = 6) {
		$this->level = max(0, min(11, $level));
	}

	/**
	 * Compress
	 *
	 * Compresses data using brotli algorithm.
	 *
	 * @param string $data raw data to compress
	 *
	 * @return false|string compressed brotli data on success, false on failure
	 */
	public function compress(string $data): false|string {
		if (!$this->isSupported()) {
			return false;
		}

		$original_size = strlen($data);
		$compressed = brotli_compress($data, $this->level);

		if ($compressed !== false) {
			$compressed_size = strlen($compressed);
			$this->ratio = $original_size > 0 ? ($compressed_size / $original_size) : 0.0;
		}

		return $compressed;
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
