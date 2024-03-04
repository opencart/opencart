<?php

use JetBrains\PhpStorm\Deprecated;
use JetBrains\PhpStorm\Pure;

/**
 * Loads a php extension at runtime
 * @param string $extension_filename <p>
 * This parameter is only the filename of the
 * extension to load which also depends on your platform. For example,
 * the sockets extension (if compiled
 * as a shared module, not the default!) would be called
 * sockets.so on Unix platforms whereas it is called
 * php_sockets.dll on the Windows platform.
 * </p>
 * <p>
 * The directory where the extension is loaded from depends on your
 * platform:
 * </p>
 * <p>
 * Windows - If not explicitly set in the <i>php.ini</i>, the extension is
 * loaded from C:\php4\extensions\ (PHP 4) or
 * C:\php5\ (PHP 5) by default.
 * </p>
 * <p>
 * Unix - If not explicitly set in the <i>php.ini</i>, the default extension
 * directory depends on
 * whether PHP has been built with --enable-debug
 * or not</p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure. If the functionality of loading modules is not available
 * or has been disabled (either by setting
 * enable_dl off or by enabling safe mode
 * in <i>php.ini</i>) an <b>E_ERROR</b> is emitted
 * and execution is stopped. If <b>dl</b> fails because the
 * specified library couldn't be loaded, in addition to <b>FALSE</b> an
 * <b>E_WARNING</b> message is emitted.
 * Loads a PHP extension at runtime
 * @link https://php.net/manual/en/function.dl.php
 */
#[Deprecated(since: '5.3')]
function dl(string $extension_filename): bool {}

/**
 * Sets the process title
 * @link https://php.net/manual/en/function.cli-set-process-title.php
 * @param string $title <p>
 * The new title.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 * @since 5.5
 */
function cli_set_process_title(string $title): bool {}

/**
 * Returns the current process title, as set by cli_set_process_title(). Note that this may not exactly match what is shown in ps or top, depending on your operating system.
 *
 * @link https://php.net/manual/en/function.cli-get-process-title.php
 * @return string|null Return a string with the current process title or <b>NULL</b> on error.
 * @since 5.5
 */
#[Pure(true)]
function cli_get_process_title(): ?string {}

/**
 * Verify that the contents of a variable is accepted by the iterable pseudo-type, i.e. that it is an array or an object implementing Traversable
 * @param mixed $value
 * @return bool
 * @since 7.1
 * @link https://php.net/manual/en/function.is-iterable.php
 */
#[Pure]
function is_iterable(mixed $value): bool {}

/**
 * Encodes an ISO-8859-1 string to UTF-8
 * @link https://php.net/manual/en/function.utf8-encode.php
 * @param string $string <p>
 * An ISO-8859-1 string.
 * </p>
 * @return string the UTF-8 translation of <i>data</i>.
 * @deprecated 8.2 Consider to use {@link mb_convert_encoding}, {@link UConverter::transcode()} or {@link iconv()}
 */
#[Pure]
#[Deprecated(replacement: "mb_convert_encoding(%parameter0%, 'UTF-8')", since: "8.2")]
function utf8_encode(string $string): string {}

/**
 * Converts a string with ISO-8859-1 characters encoded with UTF-8
 * to single-byte ISO-8859-1
 * @link https://php.net/manual/en/function.utf8-decode.php
 * @param string $string <p>
 * An UTF-8 encoded string.
 * </p>
 * @return string the ISO-8859-1 translation of <i>data</i>.
 * @deprecated 8.2 Consider to use {@link mb_convert_encoding}, {@link UConverter::transcode()} or {@link iconv()}
 */
#[Pure]
#[Deprecated(replacement: "mb_convert_encoding(%parameter0%, 'ISO-8859-1')", since: "8.2")]
function utf8_decode(string $string): string {}

/**
 * Clear the most recent error
 * @link https://php.net/manual/en/function.error-clear-last.php
 * @return void
 * @since 7.0
 */
function error_clear_last(): void {}

/**
 * Get process codepage
 * @link https://php.net/manual/en/function.sapi-windows-cp-get
 * @param string $kind The kind of operating system codepage to get, either 'ansi' or 'oem'. Any other value refers to the current codepage of the process.
 * @return int <p>
 * If <i>kind</i> is 'ansi', the current ANSI code page of the operating system is returned.
 * If <i>kind</i> is 'oem', the current OEM code page of the operating system is returned.
 * Otherwise, the current codepage of the process is returned.
 * </p>
 * @since 7.1
 */
function sapi_windows_cp_get(string $kind = ""): int {}

/**
 * Set process codepage
 * @link https://php.net/manual/en/function.sapi-windows-cp-set
 * @param int $codepage A codepage identifier.
 * @return bool Returns <i>true</i> on success or <i>false</i> on failure.
 * @since 7.1
 */
function sapi_windows_cp_set(int $codepage): bool {}

/**
 * Convert string from one codepage to another
 * @link https://php.net/manual/en/function.sapi-windows-cp-conv.php
 * @param int|string $in_codepage The codepage of the <i>subject</i> string. Either the codepage name or identifier.
 * @param int|string $out_codepage The codepage to convert the <i>subject</i> string to. Either the codepage name or identifier.
 * @param string $subject The string to convert.
 * @return string|null The <i>subject</i> string converted to <i>out_codepage</i>, or <b>null</b> on failure.
 * @since 7.1
 */
function sapi_windows_cp_conv(int|string $in_codepage, int|string $out_codepage, string $subject): ?string {}

/**
 * Indicates whether the codepage is utf-8 compatible
 * @link https://www.php.net/manual/en/function.sapi-windows-cp-is-utf8.php
 * @return bool
 * @since 7.1
 */
function sapi_windows_cp_is_utf8(): bool {}

/**
 * Get or set VT100 support for the specified stream associated to an output buffer of a Windows console.
 *
 * At startup, PHP tries to enable the VT100 feature of the STDOUT/STDERR streams.
 * By the way, if those streams are redirected to a file, the VT100 features may not be enabled.
 *
 * If VT100 support is enabled, it is possible to use control sequences as they are known from the VT100 terminal.
 * They allow the modification of the terminal's output. On Windows these sequences are called Console Virtual Terminal Sequences.
 *
 * <b>Warning</b> This function uses the <b>ENABLE_VIRTUAL_TERMINAL_PROCESSING</b> flag implemented in the Windows 10 API, so the VT100 feature may not be available on older Windows versions.
 *
 * @link https://php.net/manual/en/function.sapi-windows-vt100-support.php
 * @param resource $stream The stream on which the function will operate.
 * @param bool|null $enable <p>
 * If bool, the VT100 feature will be enabled (if true) or disabled (if false).
 * </p>
 * <p>
 * If <i>enable</i> is <b>null</b>, the function returns <b>true</b> if the stream <i>stream</i> has VT100 control codes enabled, <b>false</b> otherwise.
 * </p>
 * <p>
 * If <i>enable</i> is a bool, the function will try to enable or disable the VT100 features of the stream <i>stream</i>.
 * If the feature has been successfully enabled (or disabled), the function will return <b>true</b>, or <b>false</b> otherwise.
 * </p>
 * @return bool <p>
 * If <i>enable</i> is <b>null</b>: returns <b>true</b> if the VT100 feature is enabled, <b>false</b> otherwise.
 * </p>
 * <p>
 * If <i>enable</i> is a bool: Returns <b>true</b> on success or <b>false</b> on failure.
 * </p>
 * @since 7.2
 */
function sapi_windows_vt100_support($stream, ?bool $enable = null): bool {}

/**
 * Set or remove a CTRL event handler, which allows Windows CLI processes to intercept or ignore CTRL+C and CTRL+BREAK events.
 * Note that in multithreaded environments, this is only possible when called from the main thread.
 *
 * @link https://www.php.net/manual/en/function.sapi-windows-set-ctrl-handler.php
 * @param callable|null $handler <p>
 * A callback function to set or remove. If set, this function will be called whenever a CTRL+C or CTRL+BREAK event occurs.
 * </p>
 * <p>
 * The function is supposed to have the following signature:
 * <code>
 * handler(int $event): void
 * </code>
 * <code>event</code> The CTRL event which has been received; either <b>PHP_WINDOWS_EVENT_CTRL_C</b> or <b>PHP_WINDOWS_EVENT_CTRL_BREAK</b>.
 * </p>
 * <p>
 * Setting a <b>null</b> handler causes the process to ignore CTRL+C events, but not CTRL+BREAK events.
 * </p>
 * @param bool $add If <b>true</b>, the handler is set. If <b>false</b>, the handler is removed.
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 * @since 7.4
 */
function sapi_windows_set_ctrl_handler(?callable $handler, bool $add = true): bool {}

/**
 * Send a CTRL event to another process.
 *
 * @link https://www.php.net/manual/en/function.sapi-windows-generate-ctrl-event.php
 * @param int $event The CTRL even to send; <b>either PHP_WINDOWS_EVENT_CTRL_C</b> or <b>PHP_WINDOWS_EVENT_CTRL_BREAK</b>.
 * @param int $pid [optional] The ID of the process to which to send the event to. If 0 is given, the event is sent to all processes of the process group.
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 * @since 7.4
 */
function sapi_windows_generate_ctrl_event(int $event, int $pid = 0): bool {}

/**
 * The full path and filename of the file. If used inside an include,
 * the name of the included file is returned.
 * Since PHP 4.0.2, <b>__FILE__</b> always contains an
 * absolute path with symlinks resolved whereas in older versions it contained relative path
 * under some circumstances.
 * @link https://php.net/manual/en/language.constants.predefined.php
 */
define('__FILE__', '', true);

/**
 * The current line number of the file.
 * @link https://php.net/manual/en/language.constants.predefined.php
 */
define('__LINE__', 0, true);

/**
 * The class name. (Added in PHP 4.3.0) As of PHP 5 this constant
 * returns the class name as it was declared (case-sensitive). In PHP
 * 4 its value is always lowercased. The class name includes the namespace
 * it was declared in (e.g. Foo\Bar).
 * Note that as of PHP 5.4 __CLASS__ works also in traits. When used
 * in a trait method, __CLASS__ is the name of the class the trait
 * is used in.
 * @link https://php.net/manual/en/language.constants.predefined.php
 */
define('__CLASS__', '', true);

/**
 * The function name. (Added in PHP 4.3.0) As of PHP 5 this constant
 * returns the function name as it was declared (case-sensitive). In
 * PHP 4 its value is always lowercased.
 * @link https://php.net/manual/en/language.constants.predefined.php
 */
define('__FUNCTION__', '', true);

/**
 * The class method name. (Added in PHP 5.0.0) The method name is
 * returned as it was declared (case-sensitive).
 * @link https://php.net/manual/en/language.constants.predefined.php
 */
define('__METHOD__', '', true);

/**
 * The trait name. (Added in PHP 5.4.0) As of PHP 5.4 this constant
 * returns the trait as it was declared (case-sensitive). The trait name includes the namespace
 * it was declared in (e.g. Foo\Bar).
 * @since 5.4
 * @link https://php.net/manual/en/language.constants.predefined.php
 */
define('__TRAIT__', '', true);

/**
 * The directory of the file. If used inside an include,
 * the directory of the included file is returned. This is equivalent
 * to `dirname(__FILE__)`. This directory name
 * does not have a trailing slash unless it is the root directory.
 * @link https://php.net/manual/en/language.constants.predefined.php
 */
define('__DIR__', '', true);

/**
 * The name of the current namespace (case-sensitive). This constant
 * is defined in compile-time (Added in PHP 5.3.0).
 * @link https://php.net/manual/en/language.constants.predefined.php
 */
define('__NAMESPACE__', '', true);
