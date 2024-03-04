<?php

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Deprecated;
use JetBrains\PhpStorm\ExpectedValues;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;
use JetBrains\PhpStorm\Pure;

/**
 * Generate a system log message
 * @link https://php.net/manual/en/function.syslog.php
 * @param int $priority <p>
 * priority is a combination of the facility and
 * the level. Possible values are:
 * <table>
 * syslog Priorities (in descending order)
 * <tr valign="top">
 * <td>Constant</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td>LOG_EMERG</td>
 * <td>system is unusable</td>
 * </tr>
 * <tr valign="top">
 * <td>LOG_ALERT</td>
 * <td>action must be taken immediately</td>
 * </tr>
 * <tr valign="top">
 * <td>LOG_CRIT</td>
 * <td>critical conditions</td>
 * </tr>
 * <tr valign="top">
 * <td>LOG_ERR</td>
 * <td>error conditions</td>
 * </tr>
 * <tr valign="top">
 * <td>LOG_WARNING</td>
 * <td>warning conditions</td>
 * </tr>
 * <tr valign="top">
 * <td>LOG_NOTICE</td>
 * <td>normal, but significant, condition</td>
 * </tr>
 * <tr valign="top">
 * <td>LOG_INFO</td>
 * <td>informational message</td>
 * </tr>
 * <tr valign="top">
 * <td>LOG_DEBUG</td>
 * <td>debug-level message</td>
 * </tr>
 * </table>
 * </p>
 * @param string $message <p>
 * The message to send, except that the two characters
 * %m will be replaced by the error message string
 * (strerror) corresponding to the present value of
 * errno.
 * </p>
 * @return bool true on success or false on failure.
 */
#[LanguageLevelTypeAware(['8.2' => 'true'], default: 'bool')]
function syslog(int $priority, string $message): bool {}

/**
 * Close connection to system logger
 * @link https://php.net/manual/en/function.closelog.php
 * @return bool true on success or false on failure.
 */
#[LanguageLevelTypeAware(['8.2' => 'true'], default: 'bool')]
function closelog(): bool {}

/**
 * Registers a function that will be called when PHP starts sending output.
 * The callback is executed just after PHP prepares all headers to be sent,<br>
 * and before any other output is sent, creating a window to manipulate the outgoing headers before being sent.
 * @link https://secure.php.net/manual/en/function.header-register-callback.php
 * @param callable $callback Function called just before the headers are sent.
 * @return bool true on success or false on failure.
 */
function header_register_callback(callable $callback): bool {}

/**
 * Get the size of an image from a string.
 * @param string $string The image data, as a string.
 * @param array &$image_info [optional] This optional parameter allows you to extract<br>
 * some extended information from the image file. Currently, this will <br>
 * return the different JPG APP markers as an associative array. <br>
 * Some programs use these APP markers to embed text information in images. <br>
 * A very common one is to embed » IPTC information in the APP13 marker. <br>
 * You can use the iptcparse() function to parse the binary APP13 marker into something readable.
 * @return array|false Returns an array with 7 elements.<br>
 * Index 0 and 1 contains respectively the width and the height of the image.<br>
 * Index 2 is one of the <b>IMAGETYPE_XXX</b> constants indicating the type of the image.<br>
 * Index 3 is a text string with the correct <b>height="yyy" width="xxx"</b> string<br>
 * that can be used directly in an IMG tag.<br>
 * On failure, FALSE is returned.
 * @link https://secure.php.net/manual/en/function.getimagesizefromstring.php
 * @since 5.4
 * @link https://secure.php.net/manual/en/function.getimagesizefromstring.php
 * @since 5.4
 */
#[ArrayShape([0 => 'int', 1 => 'int', 2 => 'int', 3 => 'string', 'bits' => 'int', 'channels' => 'int', 'mime' => 'string'])]
function getimagesizefromstring(string $string, &$image_info): array|false {}

/**
 * Set the stream chunk size.
 * @param resource $stream The target stream.
 * @param int $size The desired new chunk size.
 * @return int|false Returns the previous chunk size on success.<br>
 * Will return <b>FALSE</b> if chunk_size is less than 1 or greater than <b>PHP_INT_MAX</b>.
 * @link https://secure.php.net/manual/en/function.stream-set-chunk-size.php
 * @since 5.4
 */
#[LanguageLevelTypeAware(["8.0" => "int"], default: "int|false")]
function stream_set_chunk_size($stream, int $size) {}

/**
 * Initializes all syslog related variables
 * @link https://php.net/manual/en/function.define-syslog-variables.php
 * @return void
 * @removed 5.4
 */
#[Deprecated(since: '5.3')]
function define_syslog_variables() {}

/**
 * Calculate the metaphone key of a string
 * @link https://php.net/manual/en/function.metaphone.php
 * @param string $string <p>
 * The input string.
 * </p>
 * @param int $max_phonemes [optional] <p>
 * This parameter restricts the returned metaphone key to phonemes characters in length.
 * The default value of 0 means no restriction.
 * </p>
 * @return string|false the metaphone key as a string, or FALSE on failure
 */
#[Pure]
#[LanguageLevelTypeAware(["8.0" => "string"], default: "string|false")]
function metaphone(string $string, int $max_phonemes = 0): false|string {}

/**
 * Turn on output buffering
 * @link https://php.net/manual/en/function.ob-start.php
 * @param callable $callback [optional] <p>
 * An optional output_callback function may be
 * specified. This function takes a string as a parameter and should
 * return a string. The function will be called when
 * the output buffer is flushed (sent) or cleaned (with
 * ob_flush, ob_clean or similar
 * function) or when the output buffer
 * is flushed to the browser at the end of the request. When
 * output_callback is called, it will receive the
 * contents of the output buffer as its parameter and is expected to
 * return a new output buffer as a result, which will be sent to the
 * browser. If the output_callback is not a
 * callable function, this function will return false.
 * </p>
 * <p>
 * If the callback function has two parameters, the second parameter is
 * filled with a bit-field consisting of
 * PHP_OUTPUT_HANDLER_START,
 * PHP_OUTPUT_HANDLER_CONT and
 * PHP_OUTPUT_HANDLER_END.
 * </p>
 * <p>
 * If output_callback returns false original
 * input is sent to the browser.
 * </p>
 * <p>
 * The output_callback parameter may be bypassed
 * by passing a null value.
 * </p>
 * <p>
 * ob_end_clean, ob_end_flush,
 * ob_clean, ob_flush and
 * ob_start may not be called from a callback
 * function. If you call them from callback function, the behavior is
 * undefined. If you would like to delete the contents of a buffer,
 * return "" (a null string) from callback function.
 * You can't even call functions using the output buffering functions like
 * print_r($expression, true) or
 * highlight_file($filename, true) from a callback
 * function.
 * </p>
 * <p>
 * In PHP 4.0.4, ob_gzhandler was introduced to
 * facilitate sending gz-encoded data to web browsers that support
 * compressed web pages. ob_gzhandler determines
 * what type of content encoding the browser will accept and will return
 * its output accordingly.
 * </p>
 * @param int $chunk_size <p>
 * If the optional parameter chunk_size is passed, the
 * buffer will be flushed after any output call which causes the buffer's
 * length to equal or exceed chunk_size.
 * Default value 0 means that the function is called only in the end,
 * other special value 1 sets chunk_size to 4096.
 * </p>
 * @param int $flags [optional] <p>
 * The flags parameter is a bitmask that controls the operations that can be performed on the output buffer.
 * The default is to allow output buffers to be cleaned, flushed and removed, which can be set explicitly via
 * PHP_OUTPUT_HANDLER_CLEANABLE | PHP_OUTPUT_HANDLER_FLUSHABLE | PHP_OUTPUT_HANDLER_REMOVABLE, or PHP_OUTPUT_HANDLER_STDFLAGS as shorthand.
 * </p>
 * @return bool true on success or false on failure.
 */
function ob_start($callback, int $chunk_size = 0, int $flags = PHP_OUTPUT_HANDLER_STDFLAGS): bool {}

/**
 * Flush (send) the output buffer
 * @link https://php.net/manual/en/function.ob-flush.php
 * @return bool
 */
function ob_flush(): bool {}

/**
 * Clean (erase) the output buffer
 * @link https://php.net/manual/en/function.ob-clean.php
 * @return bool
 */
function ob_clean(): bool {}

/**
 * Flush (send) the output buffer and turn off output buffering
 * @link https://php.net/manual/en/function.ob-end-flush.php
 * @return bool true on success or false on failure. Reasons for failure are first that you called the
 * function without an active buffer or that for some reason a buffer could
 * not be deleted (possible for special buffer).
 */
function ob_end_flush(): bool {}

/**
 * Clean (erase) the output buffer and turn off output buffering
 * @link https://php.net/manual/en/function.ob-end-clean.php
 * @return bool true on success or false on failure. Reasons for failure are first that you called the
 * function without an active buffer or that for some reason a buffer could
 * not be deleted (possible for special buffer).
 */
function ob_end_clean(): bool {}

/**
 * Flush the output buffer, return it as a string and turn off output buffering
 * @link https://php.net/manual/en/function.ob-get-flush.php
 * @return string|false the output buffer or false if no buffering is active.
 */
function ob_get_flush(): string|false {}

/**
 * Get current buffer contents and delete current output buffer
 * @link https://php.net/manual/en/function.ob-get-clean.php
 * @return string|false the contents of the output buffer and end output buffering.
 * If output buffering isn't active then false is returned.
 */
function ob_get_clean(): string|false {}

/**
 * Return the length of the output buffer
 * @link https://php.net/manual/en/function.ob-get-length.php
 * @return int|false the length of the output buffer contents or false if no
 * buffering is active.
 */
function ob_get_length(): int|false {}

/**
 * Return the nesting level of the output buffering mechanism
 * @link https://php.net/manual/en/function.ob-get-level.php
 * @return int the level of nested output buffering handlers or zero if output
 * buffering is not active.
 */
function ob_get_level(): int {}

/**
 * Get status of output buffers
 * @link https://php.net/manual/en/function.ob-get-status.php
 * @param bool $full_status [optional] <p>
 * true to return all active output buffer levels. If false or not
 * set, only the top level output buffer is returned.
 * </p>
 * @return array If called without the full_status parameter
 * or with full_status = false a simple array
 * with the following elements is returned:
 * <pre>
 * Array
 * (
 *     [level] => 2
 *     [type] => 0
 *     [status] => 0
 *     [name] => URL-Rewriter
 *     [del] => 1
 * )
 * </pre>
 * <table>
 * <tr><th>Key</th><th>Value</th></tr>
 * <tr><td>level</td><td>Output nesting level</td></tr>
 * <tr><td>type</td><td><em>PHP_OUTPUT_HANDLER_INTERNAL (0)</em> or <em>PHP_OUTPUT_HANDLER_USER (1)</em></td></tr>
 * <tr><td>status</td><td>One of <em>PHP_OUTPUT_HANDLER_START</em> (0), <em>PHP_OUTPUT_HANDLER_CONT</em> (1) or <em>PHP_OUTPUT_HANDLER_END</em> (2)</td></tr>
 * <tr><td>name</td><td>Name of active output handler or &#039; default output handler&#039; if none is set</td></tr>
 * <tr><td>del</td><td>Erase-flag as set by ob_start()</td></tr>
 * </table>
 * <p>
 * If called with full_status = TRUE an array with one element for each active output buffer
 * level is returned. The output level is used as key of the top level array and each array
 * element itself is another array holding status information on one active output level.
 * </p>
 * <pre>
 * Array
 * (
 *     [0] => Array
 *         (
 *             [chunk_size] => 0
 *             [size] => 40960
 *             [block_size] => 10240
 *             [type] => 1
 *             [status] => 0
 *             [name] => default output handler
 *             [del] => 1
 *         )
 *
 *     [1] => Array
 *         (
 *             [chunk_size] => 0
 *             [size] => 40960
 *             [block_size] => 10240
 *             [type] => 0
 *             [buffer_size] => 0
 *             [status] => 0
 *             [name] => URL-Rewriter
 *             [del] => 1
 *         )
 *
 * )
 * </pre>
 * <p> The full output contains these additional elements:</p>
 * <table>
 * <tr><th>Key</th><th>Value</th></tr>
 * <tr><td>chunk_size</td><td>Chunk size as set by ob_start()</td></tr>
 * <tr><td>size</td><td>...</td></tr>
 * <tr><td>blocksize</td><td>...</td></tr>
 * </table>
 */
#[ArrayShape([
    "level" => "int",
    "type" => "int",
    "flags" => "int",
    "name" => "string",
    "del" => "int",
    "chunk_size" => "int",
    "buffer_size" => "int",
    "buffer_used" => "int",
])]
function ob_get_status(bool $full_status = false): array {}

/**
 * Return the contents of the output buffer
 * @link https://php.net/manual/en/function.ob-get-contents.php
 * @return string|false This will return the contents of the output buffer or false, if output
 * buffering isn't active.
 */
#[Pure(true)]
function ob_get_contents(): string|false {}

/**
 * Turn implicit flush on/off
 * @link https://php.net/manual/en/function.ob-implicit-flush.php
 * @param int|bool $enable [optional] <p>
 * 1|<b>TRUE</b> to turn implicit flushing on, 0|<b>FALSE</b> turns it off.
 * <br><br>default: 1|<b>TRUE</b>
 * </p>
 * @return void
 */
function ob_implicit_flush(#[LanguageLevelTypeAware(["8.0" => "bool"], default: "int")] $enable = true): void {}

/**
 * List all output handlers in use
 * @link https://php.net/manual/en/function.ob-list-handlers.php
 * @return array This will return an array with the output handlers in use (if any). If
 * output_buffering is enabled or
 * an anonymous function was used with ob_start,
 * ob_list_handlers will return "default output
 * handler".
 */
function ob_list_handlers(): array {}

/**
 * Sort an array by key
 * @link https://php.net/manual/en/function.ksort.php
 * @param array &$array <p>
 * The input array.
 * </p>
 * @param int $flags <p>
 * You may modify the behavior of the sort using the optional
 * parameter sort_flags, for details
 * see sort.
 * </p>
 * @return bool true on success or false on failure.
 */
#[LanguageLevelTypeAware(['8.2' => 'true'], default: 'bool')]
function ksort(array &$array, int $flags = SORT_REGULAR): bool {}

/**
 * Sort an array by key in reverse order
 * @link https://php.net/manual/en/function.krsort.php
 * @param array &$array <p>
 * The input array.
 * </p>
 * @param int $flags <p>
 * You may modify the behavior of the sort using the optional parameter
 * sort_flags, for details see
 * sort.
 * </p>
 * @return bool true on success or false on failure.
 */
#[LanguageLevelTypeAware(['8.2' => 'true'], default: 'bool')]
function krsort(array &$array, int $flags = SORT_REGULAR): bool {}

/**
 * Sort an array using a "natural order" algorithm
 * @link https://php.net/manual/en/function.natsort.php
 * @param array &$array <p>
 * The input array.
 * </p>
 * @return bool true on success or false on failure.
 */
function natsort(array &$array): bool {}

/**
 * Sort an array using a case insensitive "natural order" algorithm
 * @link https://php.net/manual/en/function.natcasesort.php
 * @param array &$array <p>
 * The input array.
 * </p>
 * @return bool true on success or false on failure.
 */
function natcasesort(array &$array): bool {}

/**
 * Sort an array and maintain index association
 * @link https://php.net/manual/en/function.asort.php
 * @param array &$array <p>
 * The input array.
 * </p>
 * @param int $flags <p>
 * You may modify the behavior of the sort using the optional
 * parameter sort_flags, for details
 * see sort.
 * </p>
 * @return bool true on success or false on failure.
 */
#[LanguageLevelTypeAware(['8.2' => 'true'], default: 'bool')]
function asort(array &$array, int $flags = SORT_REGULAR): bool {}

/**
 * Sort an array in reverse order and maintain index association
 * @link https://php.net/manual/en/function.arsort.php
 * @param array &$array <p>
 * The input array.
 * </p>
 * @param int $flags <p>
 * You may modify the behavior of the sort using the optional parameter
 * sort_flags, for details see
 * sort.
 * </p>
 * @return bool true on success or false on failure.
 */
#[LanguageLevelTypeAware(['8.2' => 'true'], default: 'bool')]
function arsort(array &$array, int $flags = SORT_REGULAR): bool {}

/**
 * Sort an array
 * @link https://php.net/manual/en/function.sort.php
 * @param array &$array <p>
 * The input array.
 * </p>
 * @param int $flags <p>
 * The optional second parameter sort_flags
 * may be used to modify the sorting behavior using these values.
 * </p>
 * <p>
 * Sorting type flags:<br>
 * SORT_REGULAR - compare items normally
 * (don't change types)</p>
 * @return bool true on success or false on failure.
 */
#[LanguageLevelTypeAware(['8.2' => 'true'], default: 'bool')]
function sort(array &$array, int $flags = SORT_REGULAR): bool {}

/**
 * Sort an array in reverse order
 * @link https://php.net/manual/en/function.rsort.php
 * @param array &$array <p>
 * The input array.
 * </p>
 * @param int $flags <p>
 * You may modify the behavior of the sort using the optional
 * parameter sort_flags, for details see
 * sort.
 * </p>
 * @return bool true on success or false on failure.
 */
function rsort(array &$array, int $flags = SORT_REGULAR): bool {}

/**
 * Sort an array by values using a user-defined comparison function
 * @link https://php.net/manual/en/function.usort.php
 * @param array &$array <p>
 * The input array.
 * </p>
 * @param callable $callback <p>
 * The comparison function must return an integer less than, equal to, or
 * greater than zero if the first argument is considered to be
 * respectively less than, equal to, or greater than the second.
 * </p>
 * @return bool true on success or false on failure.
 */
#[LanguageLevelTypeAware(['8.2' => 'true'], default: 'bool')]
function usort(array &$array, callable $callback): bool {}

/**
 * Sort an array with a user-defined comparison function and maintain index association
 * @link https://php.net/manual/en/function.uasort.php
 * @param array &$array <p>
 * The input array.
 * </p>
 * @param callable $callback <p>
 * See usort and uksort for
 * examples of user-defined comparison functions.
 * </p>
 * @return bool true on success or false on failure.
 */
#[LanguageLevelTypeAware(['8.2' => 'true'], default: 'bool')]
function uasort(array &$array, callable $callback): bool {}

/**
 * Sort an array by keys using a user-defined comparison function
 * @link https://php.net/manual/en/function.uksort.php
 * @param array &$array <p>
 * The input array.
 * </p>
 * @param callable $callback <p>
 * The callback comparison function.
 * </p>
 * <p>
 * Function cmp_function should accept two
 * parameters which will be filled by pairs of array keys.
 * The comparison function must return an integer less than, equal
 * to, or greater than zero if the first argument is considered to
 * be respectively less than, equal to, or greater than the
 * second.
 * </p>
 * @return bool true on success or false on failure.
 */
#[LanguageLevelTypeAware(['8.2' => 'true'], default: 'bool')]
function uksort(array &$array, callable $callback): bool {}

/**
 * Shuffle an array
 * @link https://php.net/manual/en/function.shuffle.php
 * @param array &$array <p>
 * The array.
 * </p>
 * @return bool true on success or false on failure.
 */
#[LanguageLevelTypeAware(['8.2' => 'true'], default: 'bool')]
function shuffle(array &$array): bool {}

/**
 * Apply a user function to every member of an array
 * @link https://php.net/manual/en/function.array-walk.php
 * @param array|object &$array <p>
 * The input array.
 * </p>
 * @param callable $callback <p>
 * Typically, funcname takes on two parameters.
 * The array parameter's value being the first, and
 * the key/index second.
 * </p>
 * <p>
 * If funcname needs to be working with the
 * actual values of the array, specify the first parameter of
 * funcname as a
 * reference. Then,
 * any changes made to those elements will be made in the
 * original array itself.
 * </p>
 * <p>
 * Users may not change the array itself from the
 * callback function. e.g. Add/delete elements, unset elements, etc. If
 * the array that array_walk is applied to is
 * changed, the behavior of this function is undefined, and unpredictable.
 * </p>
 * @param mixed $arg [optional] <p>
 * If the optional userdata parameter is supplied,
 * it will be passed as the third parameter to the callback
 * funcname.
 * </p>
 * @return bool true on success or false on failure.
 */
#[LanguageLevelTypeAware(['8.2' => 'true'], default: 'bool')]
function array_walk(object|array &$array, callable $callback, mixed $arg): bool {}

/**
 * Apply a user function recursively to every member of an array
 * @link https://php.net/manual/en/function.array-walk-recursive.php
 * @param array|object &$array <p>
 * The input array.
 * </p>
 * @param callable $callback <p>
 * Typically, funcname takes on two parameters.
 * The input parameter's value being the first, and
 * the key/index second.
 * </p>
 * <p>
 * If funcname needs to be working with the
 * actual values of the array, specify the first parameter of
 * funcname as a
 * reference. Then,
 * any changes made to those elements will be made in the
 * original array itself.
 * </p>
 * @param mixed $arg [optional] <p>
 * If the optional userdata parameter is supplied,
 * it will be passed as the third parameter to the callback
 * funcname.
 * </p>
 * @return bool true on success or false on failure.
 */
#[LanguageLevelTypeAware(['8.2' => 'true'], default: 'bool')]
function array_walk_recursive(object|array &$array, callable $callback, mixed $arg): bool {}

/**
 * Counts all elements in an array, or something in an object.
 * <p>For objects, if you have SPL installed, you can hook into count() by implementing interface {@see Countable}.
 * The interface has exactly one method, {@see Countable::count()}, which returns the return value for the count() function.
 * Please see the {@see Array} section of the manual for a detailed explanation of how arrays are implemented and used in PHP.</p>
 * @link https://php.net/manual/en/function.count.php
 * @param array|Countable $value The array or the object.
 * @param int $mode [optional] If the optional mode parameter is set to
 * COUNT_RECURSIVE (or 1), count
 * will recursively count the array. This is particularly useful for
 * counting all the elements of a multidimensional array. count does not detect infinite recursion.
 * @return int<0,max> the number of elements in var, which is
 * typically an array, since anything else will have one
 * element.
 * <p>
 * If var is not an array or an object with
 * implemented Countable interface,
 * 1 will be returned.
 * There is one exception, if var is null,
 * 0 will be returned.
 * </p>
 * <p>
 * Caution: count may return 0 for a variable that isn't set,
 * but it may also return 0 for a variable that has been initialized with an
 * empty array. Use isset to test if a variable is set.
 * </p>
 */
#[Pure]
function count(Countable|array $value, int $mode = COUNT_NORMAL): int {}

/**
 * Set the internal pointer of an array to its last element
 * @link https://php.net/manual/en/function.end.php
 * @param array|object &$array <p>
 * The array. This array is passed by reference because it is modified by
 * the function. This means you must pass it a real variable and not
 * a function returning an array because only actual variables may be
 * passed by reference.
 * </p>
 * @return mixed|false the value of the last element or false for empty array.
 * @meta
 */
function end(object|array &$array): mixed {}

/**
 * Rewind the internal array pointer
 * @link https://php.net/manual/en/function.prev.php
 * @param array|object &$array <p>
 * The input array.
 * </p>
 * @return mixed|false the array value in the previous place that's pointed to by
 * the internal array pointer, or false if there are no more
 * elements.
 * @meta
 */
function prev(object|array &$array): mixed {}

/**
 * Advance the internal array pointer of an array
 * @link https://php.net/manual/en/function.next.php
 * @param array|object &$array <p>
 * The array being affected.
 * </p>
 * @return mixed|false the array value in the next place that's pointed to by the
 * internal array pointer, or false if there are no more elements.
 * @meta
 */
function next(object|array &$array): mixed {}

/**
 * Set the internal pointer of an array to its first element
 * @link https://php.net/manual/en/function.reset.php
 * @param array|object &$array <p>
 * The input array.
 * </p>
 * @return mixed|false the value of the first array element, or false if the array is
 * empty.
 * @meta
 */
function reset(object|array &$array): mixed {}

/**
 * Return the current element in an array
 * @link https://php.net/manual/en/function.current.php
 * @param array|object $array <p>
 * The array.
 * </p>
 * @return mixed|false The current function simply returns the
 * value of the array element that's currently being pointed to by the
 * internal pointer. It does not move the pointer in any way. If the
 * internal pointer points beyond the end of the elements list or the array is
 * empty, current returns false.
 * @meta
 */
#[Pure]
function current(object|array $array): mixed {}

/**
 * Fetch a key from an array
 * @link https://php.net/manual/en/function.key.php
 * @param array|object $array <p>
 * The array.
 * </p>
 * @return int|string|null The key function simply returns the
 * key of the array element that's currently being pointed to by the
 * internal pointer. It does not move the pointer in any way. If the
 * internal pointer points beyond the end of the elements list or the array is
 * empty, key returns null.
 */
#[Pure]
function key(object|array $array): string|int|null {}

/**
 * Find lowest value
 * @link https://php.net/manual/en/function.min.php
 * @param array|mixed $value Array to look through or first value to compare
 * @param mixed ...$values any comparable value
 * @return mixed min returns the numerically lowest of the
 * parameter values.
 */
#[Pure]
function min(
    #[PhpStormStubsElementAvailable(from: '8.0')] mixed $value,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] mixed $values,
    mixed ...$values
): mixed {}

/**
 * Find highest value
 * @link https://php.net/manual/en/function.max.php
 * @param array|mixed $value Array to look through or first value to compare
 * @param mixed ...$values any comparable value
 * @return mixed max returns the numerically highest of the
 * parameter values, either within a arg array or two arguments.
 */
#[Pure]
function max(
    #[PhpStormStubsElementAvailable(from: '8.0')] mixed $value,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] mixed $values,
    mixed ...$values
): mixed {}

/**
 * Checks if a value exists in an array
 * @link https://php.net/manual/en/function.in-array.php
 * @param mixed $needle <p>
 * The searched value.
 * </p>
 * <p>
 * If needle is a string, the comparison is done
 * in a case-sensitive manner.
 * </p>
 * @param array $haystack <p>
 * The array.
 * </p>
 * @param bool $strict [optional] <p>
 * If the third parameter strict is set to true
 * then the in_array function will also check the
 * types of the
 * needle in the haystack.
 * </p>
 * @return bool true if needle is found in the array,
 * false otherwise.
 */
#[Pure]
function in_array(mixed $needle, array $haystack, bool $strict = false): bool {}

/**
 * Searches the array for a given value and returns the first corresponding key if successful
 * @link https://php.net/manual/en/function.array-search.php
 * @param mixed $needle <p>
 * The searched value.
 * </p>
 * <p>
 * If needle is a string, the comparison is done
 * in a case-sensitive manner.
 * </p>
 * @param array $haystack <p>
 * The array.
 * </p>
 * @param bool $strict [optional] <p>
 * If the third parameter strict is set to true
 * then the array_search function will also check the
 * types of the
 * needle in the haystack.
 * </p>
 * @return int|string|false the key for needle if it is found in the
 * array, false otherwise.
 * </p>
 * <p>
 * If needle is found in haystack
 * more than once, the first matching key is returned. To return the keys for
 * all matching values, use array_keys with the optional
 * search_value parameter instead.
 */
#[Pure]
function array_search(mixed $needle, array $haystack, bool $strict = false): string|int|false {}

/**
 * Import variables into the current symbol table from an array
 * @link https://php.net/manual/en/function.extract.php
 * @param array &$array <p>
 * Note that prefix is only required if
 * extract_type is EXTR_PREFIX_SAME,
 * EXTR_PREFIX_ALL, EXTR_PREFIX_INVALID
 * or EXTR_PREFIX_IF_EXISTS. If
 * the prefixed result is not a valid variable name, it is not
 * imported into the symbol table. Prefixes are automatically separated from
 * the array key by an underscore character.
 * </p>
 * @param int $flags <p>
 * The way invalid/numeric keys and collisions are treated is determined
 * by the extract_type. It can be one of the
 * following values:
 * EXTR_OVERWRITE
 * If there is a collision, overwrite the existing variable.</p>
 * @param string $prefix <p>Only overwrite the variable if it already exists in the
 * current symbol table, otherwise do nothing. This is useful
 * for defining a list of valid variables and then extracting
 * only those variables you have defined out of
 * $_REQUEST, for example.</p>
 * @return int the number of variables successfully imported into the symbol
 * table.
 */
function extract(
    array &$array,
    #[ExpectedValues(flags: [
               EXTR_OVERWRITE,
               EXTR_SKIP,
               EXTR_PREFIX_SAME,
               EXTR_PREFIX_ALL,
               EXTR_PREFIX_INVALID,
               EXTR_IF_EXISTS,
               EXTR_PREFIX_IF_EXISTS,
               EXTR_REFS
           ])] int $flags = EXTR_OVERWRITE,
    string $prefix = ""
): int {}

/**
 * Create array containing variables and their values
 * @link https://php.net/manual/en/function.compact.php
 * @param mixed $var_name <p>
 * compact takes a variable number of parameters.
 * Each parameter can be either a string containing the name of the
 * variable, or an array of variable names. The array can contain other
 * arrays of variable names inside it; compact
 * handles it recursively.
 * </p>
 * @param mixed ...$var_names
 * @return array the output array with all the variables added to it.
 */
#[Pure]
function compact(#[PhpStormStubsElementAvailable(from: '8.0')] $var_name, #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $var_names, ...$var_names): array {}

/**
 * Fill an array with values
 * @link https://php.net/manual/en/function.array-fill.php
 * @param int $start_index <p>
 * The first index of the returned array.
 * Supports non-negative indexes only.
 * </p>
 * @param int $count <p>
 * Number of elements to insert
 * </p>
 * @param mixed $value <p>
 * Value to use for filling
 * </p>
 * @return array the filled array
 */
#[Pure]
function array_fill(int $start_index, int $count, mixed $value): array {}

/**
 * Fill an array with values, specifying keys
 * @link https://php.net/manual/en/function.array-fill-keys.php
 * @param array $keys <p>
 * Array of values that will be used as keys. Illegal values
 * for key will be converted to string.
 * </p>
 * @param mixed $value <p>
 * Value to use for filling
 * </p>
 * @return array the filled array
 */
#[Pure]
function array_fill_keys(array $keys, mixed $value): array {}

/**
 * Create an array containing a range of elements
 * @link https://php.net/manual/en/function.range.php
 * @param mixed $start <p>
 * First value of the sequence.
 * </p>
 * @param mixed $end <p>
 * The sequence is ended upon reaching the end value.
 * </p>
 * @param positive-int|float $step [optional] <p>
 * If a step value is given, it will be used as the
 * increment between elements in the sequence. step
 * should be given as a positive number. If not specified,
 * step will default to 1.
 * </p>
 * @return array an array of elements from start to
 * end, inclusive.
 */
#[Pure]
function range($start, $end, int|float $step = 1): array {}

/**
 * Sort multiple or multi-dimensional arrays
 * @link https://php.net/manual/en/function.array-multisort.php
 * @param array &$array <p>
 * An array being sorted.
 * </p>
 * @param  &...$rest [optional] <p>
 * More arrays, optionally followed by sort order and flags.
 * Only elements corresponding to equivalent elements in previous arrays are compared.
 * In other words, the sort is lexicographical.
 * </p>
 * @return bool true on success or false on failure.
 */
function array_multisort(
    &$array,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $sort_order = SORT_ASC,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $sort_flags = SORT_REGULAR,
    &...$rest
): bool {}

/**
 * Push elements onto the end of array
 * Since 7.3.0 this function can be called with only one parameter.
 * For earlier versions at least two parameters are required.
 * @link https://php.net/manual/en/function.array-push.php
 * @param array &$array <p>
 * The input array.
 * </p>
 * @param mixed ...$values <p>
 * The pushed variables.
 * </p>
 * @return int the number of elements in the array.
 */
function array_push(
    array &$array,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.2')] $values,
    mixed ...$values
): int {}

/**
 * Pop the element off the end of array
 * @link https://php.net/manual/en/function.array-pop.php
 * @param array &$array <p>
 * The array to get the value from.
 * </p>
 * @return mixed|null the last value of array.
 * If array is empty (or is not an array),
 * null will be returned.
 * @meta
 */
function array_pop(array &$array): mixed {}

/**
 * Shift an element off the beginning of array
 * @link https://php.net/manual/en/function.array-shift.php
 * @param array &$array <p>
 * The input array.
 * </p>
 * @return mixed|null the shifted value, or null if array is
 * empty or is not an array.
 * @meta
 */
function array_shift(array &$array): mixed {}

/**
 * Prepend elements to the beginning of an array
 * Since 7.3.0 this function can be called with only one parameter.
 * For earlier versions at least two parameters are required.
 * @link https://php.net/manual/en/function.array-unshift.php
 * @param array &$array <p>
 * The input array.
 * </p>
 * @param mixed ...$values <p>
 * The prepended variables.
 * </p>
 * @return int the number of elements in the array.
 */
function array_unshift(array &$array, #[PhpStormStubsElementAvailable(from: '5.3', to: '7.2')] $values, mixed ...$values): int {}

/**
 * Remove a portion of the array and replace it with something else
 * @link https://php.net/manual/en/function.array-splice.php
 * @param array &$array <p>
 * The input array.
 * </p>
 * @param int $offset <p>
 * If offset is positive then the start of removed
 * portion is at that offset from the beginning of the
 * input array. If offset
 * is negative then it starts that far from the end of the
 * input array.
 * </p>
 * @param int|null $length [optional] <p>
 * If length is omitted, removes everything
 * from offset to the end of the array. If
 * length is specified and is positive, then
 * that many elements will be removed. If
 * length is specified and is negative then
 * the end of the removed portion will be that many elements from
 * the end of the array. Tip: to remove everything from
 * offset to the end of the array when
 * replacement is also specified, use
 * count($input) for
 * length.
 * </p>
 * @param mixed $replacement <p>
 * If replacement array is specified, then the
 * removed elements are replaced with elements from this array.
 * </p>
 * <p>
 * If offset and length
 * are such that nothing is removed, then the elements from the
 * replacement array are inserted in the place
 * specified by the offset. Note that keys in
 * replacement array are not preserved.
 * </p>
 * <p>
 * If replacement is just one element it is
 * not necessary to put array()
 * around it, unless the element is an array itself.
 * </p>
 * @return array the array consisting of the extracted elements.
 */
function array_splice(array &$array, int $offset, ?int $length, mixed $replacement = []): array {}

/**
 * Extract a slice of the array
 * @link https://php.net/manual/en/function.array-slice.php
 * @param array $array <p>
 * The input array.
 * </p>
 * @param int $offset <p>
 * If offset is non-negative, the sequence will
 * start at that offset in the array. If
 * offset is negative, the sequence will
 * start that far from the end of the array.
 * </p>
 * @param int|null $length [optional] <p>
 * If length is given and is positive, then
 * the sequence will have that many elements in it. If
 * length is given and is negative then the
 * sequence will stop that many elements from the end of the
 * array. If it is omitted, then the sequence will have everything
 * from offset up until the end of the
 * array.
 * </p>
 * @param bool $preserve_keys [optional] <p>
 * Note that array_slice will reorder and reset the
 * array indices by default. You can change this behaviour by setting
 * preserve_keys to true.
 * </p>
 * @return array the slice.
 * @meta
 */
#[Pure]
function array_slice(array $array, int $offset, ?int $length, bool $preserve_keys = false): array {}

/**
 * Merges the elements of one or more arrays together (if the input arrays have the same string keys, then the later value for that key will overwrite the previous one; if the arrays contain numeric keys, the later value will be appended)
 * Since 7.4.0 this function can be called without any parameter, and it will return empty array.
 * @link https://php.net/manual/en/function.array-merge.php
 * @param array ...$arrays <p>
 * Variable list of arrays to merge.
 * </p>
 * @return array the resulting array.
 * @meta
 */
#[Pure]
function array_merge(
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.3')] $array,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.0')] $arrays,
    array ...$arrays
): array {}
