<?php

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;
use JetBrains\PhpStorm\Pure;

/**
 * Opens a bzip2 compressed file
 * @link https://php.net/manual/en/function.bzopen.php
 * @param string $file <p>
 * The name of the file to open, or an existing stream resource.
 * </p>
 * @param string $mode <p>
 * Similar to the <b>fopen</b> function, only 'r' (read)
 * and 'w' (write) are supported. Everything else will cause bzopen
 * to return <b>FALSE</b>.
 * </p>
 * @return resource|false If the open fails, <b>bzopen</b> returns <b>FALSE</b>, otherwise
 * it returns a pointer to the newly opened file.
 */
#[Pure]
function bzopen($file, string $mode) {}

/**
 * Binary safe bzip2 file read
 * @link https://php.net/manual/en/function.bzread.php
 * @param resource $bz <p>
 * The file pointer. It must be valid and must point to a file
 * successfully opened by <b>bzopen</b>.
 * </p>
 * @param int<1024, 8192> $length [optional] <p>
 * If not specified, <b>bzread</b> will read 1024
 * (uncompressed) bytes at a time. A maximum of 8192
 * uncompressed bytes will be read at a time.
 * </p>
 * @return string|false the uncompressed data, or <b>FALSE</b> on error.
 */
function bzread($bz, int $length = 1024): string|false {}

/**
 * Binary safe bzip2 file write
 * @link https://php.net/manual/en/function.bzwrite.php
 * @param resource $bz <p>
 * The file pointer. It must be valid and must point to a file
 * successfully opened by <b>bzopen</b>.
 * </p>
 * @param string $data <p>
 * The written data.
 * </p>
 * @param int|null $length [optional] <p>
 * If supplied, writing will stop after <i>length</i>
 * (uncompressed) bytes have been written or the end of
 * <i>data</i> is reached, whichever comes first.
 * </p>
 * @return int|false the number of bytes written, or <b>FALSE</b> on error.
 */
function bzwrite($bz, string $data, ?int $length): int|false {}

/**
 * Force a write of all buffered data
 * @link https://php.net/manual/en/function.bzflush.php
 * @param resource $bz <p>
 * The file pointer. It must be valid and must point to a file
 * successfully opened by <b>bzopen</b>.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function bzflush($bz): bool {}

/**
 * Close a bzip2 file
 * @link https://php.net/manual/en/function.bzclose.php
 * @param resource $bz <p>
 * The file pointer. It must be valid and must point to a file
 * successfully opened by <b>bzopen</b>.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function bzclose($bz): bool {}

/**
 * Returns a bzip2 error number
 * @link https://php.net/manual/en/function.bzerrno.php
 * @param resource $bz <p>
 * The file pointer. It must be valid and must point to a file
 * successfully opened by <b>bzopen</b>.
 * </p>
 * @return int the error number as an integer.
 */
#[Pure]
#[LanguageLevelTypeAware(['8.1' => 'int', '8.0' => 'int|false'], default: 'int')]
function bzerrno($bz) {}

/**
 * Returns a bzip2 error string
 * @link https://php.net/manual/en/function.bzerrstr.php
 * @param resource $bz <p>
 * The file pointer. It must be valid and must point to a file
 * successfully opened by <b>bzopen</b>.
 * </p>
 * @return string a string containing the error message.
 */
#[Pure]
#[LanguageLevelTypeAware(['8.1' => 'string', '8.0' => 'string|false'], default: 'string')]
function bzerrstr($bz) {}

/**
 * Returns the bzip2 error number and error string in an array
 * @link https://php.net/manual/en/function.bzerror.php
 * @param resource $bz <p>
 * The file pointer. It must be valid and must point to a file
 * successfully opened by <b>bzopen</b>.
 * </p>
 * @return array an associative array, with the error code in the
 * errno entry, and the error message in the
 * errstr entry.
 */
#[Pure]
#[LanguageLevelTypeAware(['8.1' => 'array', '8.0' => 'array|false'], default: 'array')]
#[ArrayShape(["errno" => "int", "errstr" => "string"])]
function bzerror($bz) {}

/**
 * Compress a string into bzip2 encoded data
 * @link https://php.net/manual/en/function.bzcompress.php
 * @param string $data <p>
 * The string to compress.
 * </p>
 * @param int $block_size <p>
 * Specifies the blocksize used during compression and should be a number
 * from 1 to 9 with 9 giving the best compression, but using more
 * resources to do so.
 * </p>
 * @param int $work_factor [optional] <p>
 * Controls how the compression phase behaves when presented with worst
 * case, highly repetitive, input data. The value can be between 0 and
 * 250 with 0 being a special case.
 * </p>
 * <p>
 * Regardless of the <i>workfactor</i>, the generated
 * output is the same.
 * </p>
 * @return string|int The compressed string, or an error number if an error occurred.
 */
#[Pure]
function bzcompress(
    string $data,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.0')] int $blocksize,
    #[PhpStormStubsElementAvailable(from: '7.1')] int $block_size = 4,
    int $work_factor = 0
): string|int {}

/**
 * Decompresses bzip2 encoded data
 * @link https://php.net/manual/en/function.bzdecompress.php
 * @param string $data <p>
 * The string to decompress.
 * </p>
 * @param bool $use_less_memory [optional] <p>
 * If <b>TRUE</b>, an alternative decompression algorithm will be used which
 * uses less memory (the maximum memory requirement drops to around 2300K)
 * but works at roughly half the speed.
 * </p>
 * <p>
 * See the bzip2 documentation for more
 * information about this feature.
 * </p>
 * @return string|int|false The decompressed string, or an error number if an error occurred.
 */
#[Pure]
function bzdecompress(string $data, bool $use_less_memory = false): string|int|false {}
