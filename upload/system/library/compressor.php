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
	 * @var object compression adaptor instance
	 */
	private object $adaptor;

	/**
	 * Constructor
	 *
	 * Initialize compression engine with specified algorithm and compression level.
	 *
	 * @param string $adaptor the compression algorithm to use (gzip, brotli, zstd)
	 * @param int    $level   compression level (algorithm-specific ranges)
	 *
	 * @throws \Exception when compression adaptor cannot be loaded
	 *
	 * @example
	 *
	 * // Registry registration
	 * $registry->set('compressor', new \Opencart\System\Library\Compressor($config->get('compress_algorithm'), $config->get('compress_level')));
	 */
	public function __construct(string $adaptor, int $level = 6) {
		$class = 'Opencart\System\Library\Compressor\\' . $adaptor;

		if (!class_exists($class)) {
			throw new \Exception('Error: Could not load compressor adaptor ' . $adaptor . ' compressor!');
		}

		$this->adaptor = new $class($level);
	}

	/**
	 * Compress
	 *
	 * Compresses input data using the selected compression algorithm.
	 *
	 * @param string $data the raw data to compress
	 *
	 * @return false|string returns compressed binary data on success, false on failure
	 *
	 * @example
	 *
	 * $compressed = $this->compressor->compress($cache_data);
	 * if ($compressed !== false) {
	 *     $this->cache->set($key, base64_encode($compressed));
	 * }
	 */
	public function compress(string $data): false|string {
		return $this->adaptor->compress($data);
	}

	/**
	 * Decompress
	 *
	 * Decompresses previously compressed data back to its original form.
	 *
	 * @param string $data the compressed binary data to decompress
	 *
	 * @return false|string returns original uncompressed data on success, false on failure
	 *
	 * @example
	 *
	 * $compressed = base64_decode($this->cache->get($key));
	 * $original = $this->compressor->decompress($compressed);
	 */
	public function decompress(string $data): false|string {
		return $this->adaptor->decompress($data);
	}

	/**
	 * Get Compression Ratio
	 *
	 * Retrieves the compression ratio from the last compression operation.
	 *
	 * @return float compression ratio (compressed_size / original_size)
	 */
	public function getRatio(): float {
		return $this->adaptor->getRatio();
	}

	/**
	 * Get Extension
	 *
	 * Returns the standard file extension for the current compression algorithm.
	 *
	 * @return string file extension (gz, br, zst)
	 */
	public function getExtension(): string {
		return $this->adaptor->getExtension();
	}

	/**
	 * Compress File
	 *
	 * Compresses a file and saves it with the appropriate extension.
	 * Creates a new compressed file alongside the original.
	 *
	 * @param string $source_file path to the source file to compress
	 * @param string $target_file optional target file path, auto-generated if not provided
	 *
	 * @return false|string path to compressed file on success, false on failure
	 *
	 * @example
	 *
	 * // Compress file with auto-generated name
	 * $compressed_file = $this->compressor->compressFile('/path/to/data.json');
	 * // Creates: /path/to/data.json.gz (or .br/.zst depending on algorithm)
	 *
	 * // Compress file with custom name
	 * $compressed_file = $this->compressor->compressFile('/path/to/data.json', '/path/to/compressed.gz');
	 */
	public function compressFile(string $source_file, string $target_file = ''): false|string {
		if (!is_file($source_file) || !is_readable($source_file)) {
			return false;
		}

		$data = file_get_contents($source_file);

		if ($data === false) {
			return false;
		}

		$compressed_data = $this->compress($data);

		if ($compressed_data === false) {
			return false;
		}

		if (empty($target_file)) {
			$target_file = $source_file . '.' . $this->getExtension();
		}

		if (file_put_contents($target_file, $compressed_data) !== false) {
			return $target_file;
		}

		return false;
	}

	/**
	 * Decompress File
	 *
	 * Decompresses a compressed file and saves the result.
	 *
	 * @param string $source_file path to the compressed file
	 * @param string $target_file optional target file path, auto-generated if not provided
	 *
	 * @return false|string path to decompressed file on success, false on failure
	 */
	public function decompressFile(string $source_file, string $target_file = ''): false|string {
		if (!is_file($source_file) || !is_readable($source_file)) {
			return false;
		}

		$compressed_data = file_get_contents($source_file);

		if ($compressed_data === false) {
			return false;
		}

		$decompressed_data = $this->decompress($compressed_data);

		if ($decompressed_data === false) {
			return false;
		}

		if (empty($target_file)) {
			// Remove compression extension from filename
			$extension = '.' . $this->getExtension();

			if (str_ends_with($source_file, $extension)) {
				$target_file = substr($source_file, 0, -strlen($extension));
			} else {
				$target_file = $source_file . '.decompressed';
			}
		}

		if (file_put_contents($target_file, $decompressed_data) !== false) {
			return $target_file;
		}

		return false;
	}

	/**
	 * Is Supported
	 *
	 * Checks if the selected compression algorithm is available in the current PHP environment.
	 *
	 * @return bool true if algorithm is supported, false if required extensions are missing
	 */
	public function isSupported(): bool {
		return $this->adaptor->isSupported();
	}
}
