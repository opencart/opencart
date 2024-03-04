<?php

/**
 * Compresses the given data string using LZF encoding.
 *
 * @link https://www.php.net/manual/en/function.lzf-compress.php
 *
 * @param string $data Data to be compressed
 *
 * @return false|string Returns the compressed data or FALSE if an error occurred.
 *
 * @since 0.1.0
 */
function lzf_compress($data) {}

/**
 * Decompresses the given data string containing lzf encoded data.
 *
 * @link https://www.php.net/manual/en/function.lzf-decompress.php
 *
 * @param string $data The compressed string.
 *
 * @return false|string Returns the decompressed data or FALSE if an error occurred.
 *
 * @since 0.1.0
 */
function lzf_decompress($data) {}

/**
 * Determines what was LZF extension optimized for during compilation.
 *
 * @link https://www.php.net/manual/en/function.lzf-optimized-for.php
 *
 * @return int Returns 1 if LZF was optimized for speed, 0 for compression.
 *
 * @since 1.0.0
 */
function lzf_optimized_for() {}
