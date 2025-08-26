<?php
/**
 * @package        OpenCart
 *
 * @author         Daniel Kerr
 * @copyright      Copyright (c) 2005 - 2022, OpenCart, Ltd. (https://www.opencart.com/)
 * @license        https://opensource.org/licenses/GPL-3.0
 *
 * @see           https://www.opencart.com
 */
namespace Opencart\System\Library;
/**
 * Class Compressor
 *
 * Provides data compression and decompression functionality using various algorithms.
 * Supports gzip, brotli, and zstd compression formats with configurable compression levels.
 *
 * @package Opencart\System\Library
 */
class Compressor {
	/**
	 * @var object
	 */
	private object $load;

	/**
	 * Constructor
	 *
	 * Initialize compression engine with specified algorithm and compression level.
	 *
	 * @param \Opencart\System\Engine\Registry $registry
	 *
	 * @example
	 *
	 * // Registry registration
	 * $registry->set('compressor', new \Opencart\System\Library\Compressor($registry));
	 */
	public function __construct(\Opencart\System\Engine\Registry $registry) {
		$this->load = $registry->get('load');
	}

	/**
	 * Compress
	 *
	 * Compresses input data using the selected compression algorithm.
	 *
	 * @param string $data      the raw data to compress
	 * @param string $algorithm the compression algorithm to use (gzip, brotli, zstd)
	 * @param int    $level     the compression level
	 *
	 * @throws \Exception
	 *
	 * @return false|string returns compressed binary data on success, false on failure
	 *
	 * @example
	 *
	 * $algorithms = [
	 *     'gzip'   => 9,
	 *     'brotli' => 11,
	 *     'zstd'   => 22
	 * ];
	 *
	 * foreach ($algorithms as $algorithm => $level) {
	 *     $compressed_data = $this->compressor->compress($data, $algorithm, $level);
	 * }
	 * @example
	 *
	 * $compressed = $this->compressor->compress($data, 'brotli', 11);
	 *
	 * if ($compressed !== false) {
	 *     // store or transmit
	 * }
	 */
	public function compress(string $data, string $algorithm, int $level): false|string {
		$adapter = $this->load->library('compressor/' . $algorithm);

		if (!$adapter) {
			return false;
		}

		if (method_exists($adapter, 'isSupported') && !$adapter->isSupported()) {
			return false;
		}

		return $adapter->compress($data, $level);
	}

	/**
	 * Decompress
	 *
	 * Decompresses previously compressed data back to its original form.
	 * Can accept an optional algorithm parameter or auto-detect the compression algorithm.
	 *
	 * @param string      $data      the compressed binary data to decompress
	 * @param string|null $algorithm optional compression algorithm (gzip, brotli, zstd), auto-detected if not provided
	 *
	 * @throws \Exception
	 *
	 * @return false|string returns original uncompressed data on success, false on failure
	 *
	 * @example
	 *
	 * // Explicit algorithm
	 * $original = $this->compressor->decompress($compressed_data, 'gzip');
	 * @example
	 *
	 * // Auto-detect
	 * $compressed = base64_decode($this->cache->get($key));
	 * $original = $this->compressor->decompress($compressed);
	 */
	public function decompress(string $data, ?string $algorithm = null): false|string {
		$detected_algorithm = $algorithm ?? $this->detect($data);

		if ($detected_algorithm === null) {
			return false;
		}

		$adapter = $this->load->library('compressor/' . $detected_algorithm);

		if (!$adapter) {
			return false;
		}

		if (method_exists($adapter, 'isSupported') && !$adapter->isSupported()) {
			return false;
		}

		$result = $adapter->decompress($data);

		return $result ?? false;
	}

	/**
	 * Get Extension
	 *
	 * Returns the standard file extension for the current compression algorithm.
	 *
	 * @param string $algorithm the compression algorithm to use (gzip, brotli, zstd)
	 *
	 * @return string file extension (gz, br, zst)
	 */
	public function getExtension(string $algorithm): string {
		return $this->load->library('compressor/' . $algorithm)?->getExtension();
	}

	/**
	 * Detect Compression Algorithm
	 *
	 * Detects the compression algorithm from a file path or raw data string.
	 * Detection order:
	 *  1. File extension (.gz, .br, .zst) if input is a readable file path
	 *  2. Magic numbers (gzip, zstd)
	 *  3. Brotli trial decompression fallback
	 *
	 * @param string $input file path or data string
	 *
	 * @return string|null the detected algorithm ('gzip', 'zstd', 'brotli') or null if not detected
	 */
	public function detect(string $input): ?string {
		// 1. File extension heuristic (fast path)
		if (is_file($input) && is_readable($input)) {
			$extension = strtolower(pathinfo($input, PATHINFO_EXTENSION));

			if ($extension === 'gz') {
				return 'gzip';
			}

			if ($extension === 'br') {
				return 'brotli';
			}

			if ($extension === 'zst') {
				return 'zstd';
			}
		}

		// Load a small chunk of data
		if (is_file($input) && is_readable($input)) {
			$handle = fopen($input, 'r');

			if (!$handle) {
				return null;
			}

			$data = fread($handle, 1024);

			fclose($handle);
		} else {
			$data = $input;
		}

		// 2. Magic numbers
		$algorithm = $this->detectByMagicNumber($data);

		if ($algorithm !== null) {
			return $algorithm;
		}

		// 3. Brotli heuristic
		if ($this->isBrotliData($data)) {
			return 'brotli';
		}

		return null;
	}

	/**
	 * Compress File
	 *
	 * Compresses a file and saves it with the appropriate extension.
	 * Creates a new compressed file alongside the original.
	 *
	 * @param string $source_file path to the source file to compress
	 * @param string $algorithm   the compression algorithm to use (gzip, brotli, zstd)
	 * @param int    $level       the compression level
	 * @param string $target_file optional target file path, auto-generated if not provided
	 *
	 * @throws \Exception
	 *
	 * @return false|string path to compressed file on success, false on failure
	 *
	 * @example
	 *
	 * // Auto target name
	 * $compressed_file = $this->compressor->compressFile('/path/data.json', 'gzip', 9);
	 * @example
	 *
	 * // Custom target
	 * $compressed_file = $this->compressor->compressFile('/path/data.json', 'gzip', 9, '/path/data.json.gz');
	 */
	public function compressFile(string $source_file, string $algorithm, int $level, string $target_file = ''): false|string {
		if (!is_file($source_file) || !is_readable($source_file)) {
			return false;
		}

		$data = file_get_contents($source_file);

		if ($data === false) {
			return false;
		}

		$compressed_data = $this->compress($data, $algorithm, $level);

		if ($compressed_data === false) {
			return false;
		}

		if (empty($target_file)) {
			$target_file = $source_file . '.' . $this->load->library('compressor/' . $algorithm)?->getExtension();
		}

		if (file_put_contents($target_file, $compressed_data) === false) {
			return false;
		}

		return $target_file;
	}

	/**
	 * Decompress File
	 *
	 * Decompresses a compressed file and saves the result.
	 * Can accept an optional algorithm parameter or auto-detect the compression algorithm.
	 *
	 * @param string      $source_file path to the compressed file
	 * @param string|null $algorithm   optional compression algorithm (gzip, brotli, zstd), auto-detected if not provided
	 * @param string      $target_file optional target file path, auto-generated if not provided
	 *
	 * @throws \Exception
	 *
	 * @return false|string path to decompressed file on success, false on failure
	 *
	 * @example
	 *
	 * // Explicit algorithm
	 * $decompressed_file = $this->compressor->decompressFile('/path/data.json.gz', 'gzip', '/path/output.json');
	 * @example
	 *
	 * // Auto-detect
	 * $decompressed_file = $this->compressor->decompressFile('/path/data.json.gz');
	 */
	public function decompressFile(string $source_file, ?string $algorithm = null, string $target_file = ''): false|string {
		if (!is_file($source_file) || !is_readable($source_file)) {
			return false;
		}

		$compressed_data = file_get_contents($source_file);

		if ($compressed_data === false) {
			return false;
		}

		$detected_algorithm = $algorithm ?? $this->detect($compressed_data);

		if ($detected_algorithm === null) {
			return false;
		}

		$decompressed_data = $this->decompress($compressed_data, $detected_algorithm);

		if ($decompressed_data === false) {
			return false;
		}

		if (empty($target_file)) {
			$extension = '.' . $this->load->library('compressor/' . $detected_algorithm)?->getExtension();
			if (str_ends_with($source_file, $extension)) {
				$target_file = substr($source_file, 0, -strlen($extension));
			} else {
				$target_file = $source_file . '.decompressed';
			}
		}

		if (file_put_contents($target_file, $decompressed_data) === false) {
			return false;
		}

		return $target_file;
	}

	/**
	 * Is Supported
	 *
	 * Checks if the selected compression algorithm is available in the current PHP environment.
	 *
	 * @param string $algorithm the compression algorithm to use (gzip, brotli, zstd)
	 *
	 * @return bool true if algorithm is supported, false if required extensions are missing
	 */
	public function isSupported(string $algorithm): bool {
		return $this->load->library('compressor/' . $algorithm)?->isSupported();
	}

	/**
	 * Detect Compression Algorithm by Magic Number
	 *
	 * Helper method to detect compression algorithm based on magic numbers in binary data.
	 *
	 * @param string $data binary data to analyze
	 *
	 * @return string|null The detected algorithm or null if not detected
	 */
	private function detectByMagicNumber(string $data): ?string {
		if (strlen($data) < 2) {
			return null;
		}

		// Gzip magic number: 1F 8B
		if (bin2hex(substr($data, 0, 2)) === '1f8b') {
			return 'gzip';
		}

		// Zstandard magic number: 28 B5 2F FD
		if (strlen($data) >= 4 && bin2hex(substr($data, 0, 4)) === '28b52ffd') {
			return 'zstd';
		}

		return null;
	}

	/**
	 * Check if Data is Brotli Compressed
	 *
	 * Attempts to detect Brotli compression by trying to decompress the data.
	 * This is a fallback method since Brotli doesn't have reliable magic numbers.
	 *
	 * @param string $data binary data to check
	 *
	 * @return bool true if data appears to be Brotli compressed
	 */
	private function isBrotliData(string $data): bool {
		// Skip very small data that's unlikely to be compressed
		if (strlen($data) < 6) {
			return false;
		}

		if (!function_exists('brotli_uncompress')) {
			return false;
		}

		$decompressed = @brotli_uncompress($data);

		// If decompression succeeds and produces reasonable output, it's likely Brotli
		return $decompressed !== false && $decompressed !== '';
	}
}
