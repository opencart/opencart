<?php
/**
 * Stubs for Zstandard
 * https://pecl.php.net/package/zstd
 * https://github.com/kjdev/php-ext-zstd
 */

namespace {
    /**
     * Minimal compress level value
     */
    define('ZSTD_COMPRESS_LEVEL_MIN', 1);

    /**
     * Maximal compress level value
     */
    define('ZSTD_COMPRESS_LEVEL_MAX', 22);

    /**
     * Default compress level value
     */
    define('ZSTD_COMPRESS_LEVEL_DEFAULT', 3);

    /**
     * libzstd version number
     */
    define('LIBZSTD_VERSION_NUMBER', 10405);

    /**
     * libzstd version string
     */
    define('LIBZSTD_VERSION_STRING', '1.4.5');

    /**
     * Zstandard compression.
     *
     * @param string $data The string to compress.
     * @param int $level The level of compression (1-22). (Defaults to 3, 0 for no compression).
     *                      A value smaller than 0 means a faster compression level. (Zstandard library 1.3.4 or later)
     *
     * @return string|false Returns the compressed data or FALSE if an error occurred.
     */
    function zstd_compress(string $data, int $level = 3): string|false {}

    /**
     * Zstandard decompression.
     *
     * @param string $data The compressed string.
     *
     * @return string|false Returns the decompressed data or FALSE if an error occurred.
     */
    function zstd_uncompress(string $data): string|false {}

    /**
     * Zstandard compression using a digested dictionary.
     *
     * @param string $data The string to compress.
     * @param string $dict The Dictionary data.
     *
     * @return string|false Returns the compressed data or FALSE if an error occurred.
     */
    function zstd_compress_dict(string $data, string $dict): string|false {}

    /**
     * Zstandard decompression using a digested dictionary.
     * An alias of {@see zstd_compress_dict}
     *
     * @param string $data The string to compress.
     * @param string $dict The Dictionary data.
     *
     * @return string|false Returns the compressed data or FALSE if an error occurred.
     */
    function zstd_compress_usingcdict(string $data, string $dict): string|false {}

    /**
     * Zstandard decompression using a digested dictionary.
     *
     * @param string $data The compressed string.
     * @param string $dict The Dictionary data.
     *
     * @return string|false Returns the decompressed data or FALSE if an error occurred.
     */
    function zstd_uncompress_dict(string $data, string $dict): string|false {}

    /**
     * Zstandard decompression using a digested dictionary.
     * An alias of {@see zstd_compress_dict}
     *
     * @param string $data The compressed string.
     * @param string $dict The Dictionary data.
     *
     * @return string|false Returns the decompressed data or FALSE if an error occurred.
     */
    function zstd_decompress_dict(string $data, string $dict): string|false {}

    /**
     * Zstandard decompression using a digested dictionary.
     * An alias of {@see zstd_compress_dict}
     *
     * @param string $data The compressed string.
     * @param string $dict The Dictionary data.
     *
     * @return string|false Returns the decompressed data or FALSE if an error occurred.
     */
    function zstd_uncompress_usingcdict(string $data, string $dict): string|false {}

    /**
     * Zstandard decompression using a digested dictionary.
     * An alias of {@see zstd_compress_dict}
     *
     * @param string $data The compressed string.
     * @param string $dict The Dictionary data.
     *
     * @return string|false Returns the decompressed data or FALSE if an error occurred.
     */
    function zstd_decompress_usingcdict(string $data, string $dict): string|false {}
}

namespace Zstd {
    /**
     * Zstandard compression.
     *
     * @param string $data The string to compress.
     * @param int $level The level of compression (1-22). (Defaults to 3, 0 for no compression).
     *                      A value smaller than 0 means a faster compression level. (Zstandard library 1.3.4 or later)
     *
     * @return string|false Returns the compressed data or FALSE if an error occurred.
     */
    function compress(string $data, int $level = 3): string|false {}

    /**
     * Zstandard decompression.
     *
     * @param string $data The compressed string.
     *
     * @return string|false Returns the decompressed data or FALSE if an error occurred.
     */
    function uncompress(string $data): string|false {}

    /**
     * Zstandard compression using a digested dictionary.
     *
     * @param string $data The string to compress.
     * @param string $dict The Dictionary data.
     *
     * @return string|false Returns the compressed data or FALSE if an error occurred.
     */
    function compress_dict(string $data, string $dict): string|false {}

    /**
     * Zstandard decompression using a digested dictionary.
     *
     * @param string $data The compressed string.
     * @param string $dict The Dictionary data.
     *
     * @return string|false Returns the decompressed data or FALSE if an error occurred.
     */
    function uncompress_dict(string $data, string $dict): string|false {}
}
