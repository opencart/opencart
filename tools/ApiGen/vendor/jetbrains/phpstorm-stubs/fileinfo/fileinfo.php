<?php

// Start of fileinfo v.1.0.5

use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;
use JetBrains\PhpStorm\Internal\TentativeType;
use JetBrains\PhpStorm\Pure;

class finfo
{
    /**
     * @param int $flags [optional]
     * @param string $magic_database [optional]
     */
    public function __construct(
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $flags,
        #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $magic_database
    ) {}

    /**
     * @param $options [optional]
     * @param $arg [optional]
     */
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')]
    public function finfo($options, $arg) {}

    /**
     * (PHP &gt;= 5.3.0, PECL fileinfo &gt;= 0.1.0)<br/>
     * Set libmagic configuration options
     * @link https://php.net/manual/en/function.finfo-set-flags.php
     * @param int $flags <p>
     * One or disjunction of more Fileinfo
     * constants.
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    public function set_flags(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $flags) {}

    /**
     * (PHP &gt;= 5.3.0, PECL fileinfo &gt;= 0.1.0)<br/>
     * Return information about a file
     * @link https://php.net/manual/en/function.finfo-file.php
     * @param string $filename <p>
     * Name of a file to be checked.
     * </p>
     * @param int $flags [optional] <p>
     * One or disjunction of more Fileinfo
     * constants.
     * </p>
     * @param resource $context [optional] <p>
     * For a description of contexts, refer to .
     * </p>
     * @return string a textual description of the contents of the
     * <i>filename</i> argument, or <b>FALSE</b> if an error occurred.
     */
    #[Pure]
    #[TentativeType]
    public function file(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $filename,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $flags = FILEINFO_NONE,
        $context = null
    ): string|false {}

    /**
     * (PHP 5 &gt;= 5.3.0, PECL fileinfo &gt;= 0.1.0)<br/>
     * Return information about a string buffer
     * @link https://php.net/manual/en/function.finfo-buffer.php
     * @param string $string <p>
     * Content of a file to be checked.
     * </p>
     * @param int $flags [optional] <p>
     * One or disjunction of more Fileinfo
     * constants.
     * </p>
     * @param resource $context [optional]
     * @return string a textual description of the <i>string</i>
     * argument, or <b>FALSE</b> if an error occurred.
     */
    #[Pure]
    #[TentativeType]
    public function buffer(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $string,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $flags = FILEINFO_NONE,
        $context = null
    ): string|false {}
}

/**
 * (PHP &gt;= 5.3.0, PECL fileinfo &gt;= 0.1.0)<br/>
 * Create a new fileinfo resource
 * @link https://php.net/manual/en/function.finfo-open.php
 * @param int $flags <p>
 * One or disjunction of more Fileinfo
 * constants.
 * </p>
 * @param string|null $magic_database [optional] <p>
 * Name of a magic database file, usually something like
 * /path/to/magic.mime. If not specified,
 * the MAGIC environment variable is used. If this variable
 * is not set either, /usr/share/misc/magic is used by default.
 * A .mime and/or .mgc suffix is added if
 * needed.
 * </p>
 * @return resource|false a magic database resource on success or <b>FALSE</b> on failure.
 */
#[LanguageLevelTypeAware(['8.1' => 'finfo|false'], default: 'resource|false')]
function finfo_open(int $flags = 0, ?string $magic_database = null) {}

/**
 * (PHP &gt;= 5.3.0, PECL fileinfo &gt;= 0.1.0)<br/>
 * Close fileinfo resource
 * @link https://php.net/manual/en/function.finfo-close.php
 * @param resource $finfo <p>
 * Fileinfo resource returned by finfo_open.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function finfo_close(#[LanguageLevelTypeAware(['8.1' => 'finfo'], default: 'resource')] $finfo): bool {}

/**
 * (PHP &gt;= 5.3.0, PECL fileinfo &gt;= 0.1.0)<br/>
 * Set libmagic configuration options
 * @link https://php.net/manual/en/function.finfo-set-flags.php
 * @param resource $finfo <p>
 * Fileinfo resource returned by finfo_open.
 * </p>
 * @param int $flags <p>
 * One or disjunction of more Fileinfo
 * constants.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function finfo_set_flags(#[LanguageLevelTypeAware(['8.1' => 'finfo'], default: 'resource')] $finfo, int $flags): bool {}

/**
 * (PHP &gt;= 5.3.0, PECL fileinfo &gt;= 0.1.0)<br/>
 * Return information about a file
 * @link https://php.net/manual/en/function.finfo-file.php
 * @param resource $finfo <p>
 * Fileinfo resource returned by finfo_open.
 * </p>
 * @param string $filename <p>
 * Name of a file to be checked.
 * </p>
 * @param int $flags <p>
 * One or disjunction of more Fileinfo
 * constants.
 * </p>
 * @param resource $context [optional] <p>
 * For a description of contexts, refer to .
 * </p>
 * @return string|false a textual description of the contents of the
 * <i>filename</i> argument, or <b>FALSE</b> if an error occurred.
 */
function finfo_file(#[LanguageLevelTypeAware(['8.1' => 'finfo'], default: 'resource')] $finfo, string $filename, int $flags = 0, $context): string|false {}

/**
 * (PHP 5 &gt;= 5.3.0, PECL fileinfo &gt;= 0.1.0)<br/>
 * Return information about a string buffer
 * @link https://php.net/manual/en/function.finfo-buffer.php
 * @param resource $finfo <p>
 * Fileinfo resource returned by finfo_open.
 * </p>
 * @param string $string <p>
 * Content of a file to be checked.
 * </p>
 * @param int $flags [optional] One or disjunction of more
 * <a href="https://php.net/manual/en/fileinfo.constants.php">Fileinfo</a> constants.
 * @param resource $context [optional]
 * @return string|false a textual description of the <i>string</i>
 * argument, or <b>FALSE</b> if an error occurred.
 */
function finfo_buffer(#[LanguageLevelTypeAware(['8.1' => 'finfo'], default: 'resource')] $finfo, string $string, int $flags = FILEINFO_NONE, $context): string|false {}

/**
 * Detect MIME Content-type for a file
 * @link https://php.net/manual/en/function.mime-content-type.php
 * @param string $filename <p>
 * Path to the tested file.
 * </p>
 * @return string|false the content type in MIME format, like
 * text/plain or application/octet-stream.
 */
function mime_content_type($filename): string|false {}

/**
 * No special handling.
 * @link https://php.net/manual/en/fileinfo.constants.php
 */
define('FILEINFO_NONE', 0);

/**
 * Follow symlinks.
 * @link https://php.net/manual/en/fileinfo.constants.php
 */
define('FILEINFO_SYMLINK', 2);

/**
 * Return the mime type and mime encoding as defined by RFC 2045.
 * @link https://php.net/manual/en/fileinfo.constants.php
 */
define('FILEINFO_MIME', 1040);

/**
 * Return the mime type.
 * @link https://php.net/manual/en/fileinfo.constants.php
 */
define('FILEINFO_MIME_TYPE', 16);

/**
 * Return the mime encoding of the file.
 * @link https://php.net/manual/en/fileinfo.constants.php
 */
define('FILEINFO_MIME_ENCODING', 1024);

/**
 * Look at the contents of blocks or character special devices.
 * @link https://php.net/manual/en/fileinfo.constants.php
 */
define('FILEINFO_DEVICES', 8);

/**
 * Return all matches, not just the first.
 * @link https://php.net/manual/en/fileinfo.constants.php
 */
define('FILEINFO_CONTINUE', 32);

/**
 * If possible preserve the original access time.
 * @link https://php.net/manual/en/fileinfo.constants.php
 */
define('FILEINFO_PRESERVE_ATIME', 128);

/**
 * Don't translate unprintable characters to a \ooo octal
 * representation.
 * @link https://php.net/manual/en/fileinfo.constants.php
 */
define('FILEINFO_RAW', 256);

/**
 * Returns the file extension appropriate for a the MIME type detected in the file.
 * For types that commonly have multiple file extensions, such as JPEG images, then the return value is multiple extensions speparated by a forward slash e.g.: "jpeg/jpg/jpe/jfif".
 * For unknown types not available in the magic.mime database, then return value is "???". Available since PHP 7.2.0.
 * @since 7.2
 */
define('FILEINFO_EXTENSION', 2097152);

/**
 * @since 8.2
 */
define('FILEINFO_APPLE', 2048);

// End of fileinfo v.1.0.5
