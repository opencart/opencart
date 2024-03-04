<?php

// Start of json v.1.3.1
use JetBrains\PhpStorm\Internal\TentativeType;
use JetBrains\PhpStorm\Pure;

/**
 * Objects implementing JsonSerializable
 * can customize their JSON representation when encoded with
 * <b>json_encode</b>.
 * @link https://php.net/manual/en/class.jsonserializable.php
 * @since 5.4
 */
interface JsonSerializable
{
    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4
     */
    #[TentativeType]
    public function jsonSerialize(): mixed;
}

class JsonIncrementalParser
{
    public const JSON_PARSER_SUCCESS = 0;
    public const JSON_PARSER_CONTINUE = 1;

    /**
     * @param int $depth [optional]
     * @param int $options [optional]
     */
    #[Pure]
    public function __construct($depth, $options) {}

    #[Pure]
    public function getError() {}

    public function reset() {}

    /**
     * @param string $json
     */
    public function parse($json) {}

    /**
     * @param string $filename
     */
    public function parseFile($filename) {}

    /**
     * @param int $options [optional]
     */
    #[Pure]
    public function get($options) {}
}

/**
 * (PHP 5 &gt;= 5.2.0, PECL json &gt;= 1.2.0)<br/>
 * Returns the JSON representation of a value
 * @link https://php.net/manual/en/function.json-encode.php
 * @param mixed $value <p>
 * The <i>value</i> being encoded. Can be any type except
 * a resource.
 * </p>
 * <p>
 * All string data must be UTF-8 encoded.
 * </p>
 * <p>PHP implements a superset of
 * JSON - it will also encode and decode scalar types and <b>NULL</b>. The JSON standard
 * only supports these values when they are nested inside an array or an object.
 * </p>
 * @param int $flags [optional] <p>
 * Bitmask consisting of <b>JSON_HEX_QUOT</b>,
 * <b>JSON_HEX_TAG</b>,
 * <b>JSON_HEX_AMP</b>,
 * <b>JSON_HEX_APOS</b>,
 * <b>JSON_NUMERIC_CHECK</b>,
 * <b>JSON_PRETTY_PRINT</b>,
 * <b>JSON_UNESCAPED_SLASHES</b>,
 * <b>JSON_FORCE_OBJECT</b>,
 * <b>JSON_UNESCAPED_UNICODE</b>.
 * <b>JSON_THROW_ON_ERROR</b> The behaviour of these
 * constants is described on
 * the JSON constants page.
 * </p>
 * @param int $depth [optional] <p>
 * Set the maximum depth. Must be greater than zero.
 * </p>
 * @return string|false a JSON encoded string on success or <b>FALSE</b> on failure.
 */
function json_encode(mixed $value, int $flags = 0, int $depth = 512): string|false {}

/**
 * (PHP 5 &gt;= 5.2.0, PECL json &gt;= 1.2.0)<br/>
 * Decodes a JSON string
 * @link https://php.net/manual/en/function.json-decode.php
 * @param string $json <p>
 * The <i>json</i> string being decoded.
 * </p>
 * <p>
 * This function only works with UTF-8 encoded strings.
 * </p>
 * <p>PHP implements a superset of
 * JSON - it will also encode and decode scalar types and <b>NULL</b>. The JSON standard
 * only supports these values when they are nested inside an array or an object.
 * </p>
 * @param bool|null $associative <p>
 * When <b>TRUE</b>, returned objects will be converted into
 * associative arrays.
 * </p>
 * @param int $depth [optional] <p>
 * User specified recursion depth.
 * </p>
 * @param int $flags [optional] <p>
 * Bitmask of JSON decode options:<br/>
 * {@see JSON_BIGINT_AS_STRING} decodes large integers as their original string value.<br/>
 * {@see JSON_INVALID_UTF8_IGNORE} ignores invalid UTF-8 characters,<br/>
 * {@see JSON_INVALID_UTF8_SUBSTITUTE} converts invalid UTF-8 characters to \0xfffd,<br/>
 * {@see JSON_OBJECT_AS_ARRAY} decodes JSON objects as PHP array, since 7.2.0 used by default if $assoc parameter is null,<br/>
 * {@see JSON_THROW_ON_ERROR} when passed this flag, the error behaviour of these functions is changed. The global error state is left untouched, and if an error occurs that would otherwise set it, these functions instead throw a JsonException<br/>
 * </p>
 * @return mixed the value encoded in <i>json</i> in appropriate
 * PHP type. Values true, false and
 * null (case-insensitive) are returned as <b>TRUE</b>, <b>FALSE</b>
 * and <b>NULL</b> respectively. <b>NULL</b> is returned if the
 * <i>json</i> cannot be decoded or if the encoded
 * data is deeper than the recursion limit.
 */
function json_decode(string $json, ?bool $associative = null, int $depth = 512, int $flags = 0): mixed {}

/**
 * Returns the last error occurred
 * @link https://php.net/manual/en/function.json-last-error.php
 * @return int an integer, the value can be one of the following
 * constants:
 * <table class='doctable table'>
 * <thead>
 * <tr>
 * <th>Constant</th>
 * <th>Meaning</th>
 * <th>Availability</th>
 * </tr>
 *
 * </thead>
 *
 * <tbody class='tbody'>
 * <tr>
 * <td><strong><code>JSON_ERROR_NONE</code></strong></td>
 * <td>No error has occurred</td>
 * <td class='empty'>&nbsp;</td>
 * </tr>
 *
 * <tr>
 * <td><strong><code>JSON_ERROR_DEPTH</code></strong></td>
 * <td>The maximum stack depth has been exceeded</td>
 * <td class='empty'>&nbsp;</td>
 * </tr>
 *
 * <tr>
 * <td><strong><code>JSON_ERROR_STATE_MISMATCH</code></strong></td>
 * <td>Invalid or malformed JSON</td>
 * <td class='empty'>&nbsp;</td>
 * </tr>
 *
 * <tr>
 * <td><strong><code>JSON_ERROR_CTRL_CHAR</code></strong></td>
 * <td>Control character error, possibly incorrectly encoded</td>
 * <td class='empty'>&nbsp;</td>
 * </tr>
 *
 * <tr>
 * <td><strong><code>JSON_ERROR_SYNTAX</code></strong></td>
 * <td>Syntax error</td>
 * <td class='empty'>&nbsp;</td>
 * </tr>
 *
 * <tr>
 * <td><strong><code>JSON_ERROR_UTF8</code></strong></td>
 * <td>Malformed UTF-8 characters, possibly incorrectly encoded</td>
 * <td>PHP 5.3.3</td>
 * </tr>
 *
 * <tr>
 * <td><strong><code>JSON_ERROR_RECURSION</code></strong></td>
 * <td>One or more recursive references in the value to be encoded</td>
 * <td>PHP 5.5.0</td>
 * </tr>
 *
 * <tr>
 * <td><strong><code>JSON_ERROR_INF_OR_NAN</code></strong></td>
 * <td>
 * One or more
 * <a href='language.types.float.php#language.types.float.nan' class='link'><strong><code>NAN</code></strong></a>
 * or <a href='function.is-infinite.php' class='link'><strong><code>INF</code></strong></a>
 * values in the value to be encoded
 * </td>
 * <td>PHP 5.5.0</td>
 * </tr>
 *
 * <tr>
 * <td><strong><code>JSON_ERROR_UNSUPPORTED_TYPE</code></strong></td>
 * <td>A value of a type that cannot be encoded was given</td>
 * <td>PHP 5.5.0</td>
 * </tr>
 *
 * <tr>
 * <td><strong><code>JSON_ERROR_INVALID_PROPERTY_NAME</code></strong></td>
 * <td>A property name that cannot be encoded was given</td>
 * <td>PHP 7.0.0</td>
 * </tr>
 *
 * <tr>
 * <td><strong><code>JSON_ERROR_UTF16</code></strong></td>
 * <td>Malformed UTF-16 characters, possibly incorrectly encoded</td>
 * <td>PHP 7.0.0</td>
 * </tr>
 *
 * </tbody>
 *
 * </table>
 */
#[Pure(true)]
function json_last_error(): int {}

/**
 * Returns the error string of the last json_encode() or json_decode() call, which did not specify <b>JSON_THROW_ON_ERROR</b>.
 * @link https://php.net/manual/en/function.json-last-error-msg.php
 * @return string Returns the error message on success, "No error" if no error has occurred.
 * @since 5.5
 */
#[Pure]
function json_last_error_msg(): string {}

/**
 * All &lt; and &gt; are converted to \u003C and \u003E.
 * @link https://php.net/manual/en/json.constants.php
 */
define('JSON_HEX_TAG', 1);

/**
 * All &s are converted to \u0026.
 * @link https://php.net/manual/en/json.constants.php
 */
define('JSON_HEX_AMP', 2);

/**
 * All ' are converted to \u0027.
 * @link https://php.net/manual/en/json.constants.php
 */
define('JSON_HEX_APOS', 4);

/**
 * All " are converted to \u0022.
 * @link https://php.net/manual/en/json.constants.php
 */
define('JSON_HEX_QUOT', 8);

/**
 * Outputs an object rather than an array when a non-associative array is
 * used. Especially useful when the recipient of the output is expecting
 * an object and the array is empty.
 * @link https://php.net/manual/en/json.constants.php
 */
define('JSON_FORCE_OBJECT', 16);

/**
 * Encodes numeric strings as numbers.
 * @since 5.3.3
 * @link https://php.net/manual/en/json.constants.php
 */
define('JSON_NUMERIC_CHECK', 32);

/**
 * Don't escape /.
 * @since 5.4
 * @link https://php.net/manual/en/json.constants.php
 */
define('JSON_UNESCAPED_SLASHES', 64);

/**
 * Use whitespace in returned data to format it.
 * @since 5.4
 * @link https://php.net/manual/en/json.constants.php
 */
define('JSON_PRETTY_PRINT', 128);

/**
 * Encode multibyte Unicode characters literally (default is to escape as \uXXXX).
 * @since 5.4
 * @link https://php.net/manual/en/json.constants.php
 */
define('JSON_UNESCAPED_UNICODE', 256);
define('JSON_PARTIAL_OUTPUT_ON_ERROR', 512);

/**
 * Occurs with underflow or with the modes mismatch.
 * @link https://php.net/manual/en/json.constants.php
 */
define('JSON_ERROR_STATE_MISMATCH', 2);

/**
 * Control character error, possibly incorrectly encoded.
 * @link https://php.net/manual/en/json.constants.php
 */
define('JSON_ERROR_CTRL_CHAR', 3);

/**
 * Malformed UTF-8 characters, possibly incorrectly encoded. This
 * constant is available as of PHP 5.3.3.
 * @link https://php.net/manual/en/json.constants.php
 */
define('JSON_ERROR_UTF8', 5);

/**
 * <p>
 * The object or array passed to <b>json_encode</b> include
 * recursive references and cannot be encoded.
 * If the <b>JSON_PARTIAL_OUTPUT_ON_ERROR</b> option was
 * given, <b>NULL</b> will be encoded in the place of the recursive reference.
 * </p>
 * <p>
 * This constant is available as of PHP 5.5.0.
 * </p>
 * @link https://php.net/manual/en/json.constants.php
 */
define('JSON_ERROR_RECURSION', 6);

/**
 * <p>
 * The value passed to <b>json_encode</b> includes either
 * <b>NAN</b>
 * or <b>INF</b>.
 * If the <b>JSON_PARTIAL_OUTPUT_ON_ERROR</b> option was
 * given, 0 will be encoded in the place of these
 * special numbers.
 * </p>
 * <p>
 * This constant is available as of PHP 5.5.0.
 * </p>
 * @link https://php.net/manual/en/json.constants.php
 */
define('JSON_ERROR_INF_OR_NAN', 7);

/**
 * <p>
 * A value of an unsupported type was given to
 * <b>json_encode</b>, such as a resource.
 * If the <b>JSON_PARTIAL_OUTPUT_ON_ERROR</b> option was
 * given, <b>NULL</b> will be encoded in the place of the unsupported value.
 * </p>
 * <p>
 * This constant is available as of PHP 5.5.0.
 * </p>
 * @link https://php.net/manual/en/json.constants.php
 */
define('JSON_ERROR_UNSUPPORTED_TYPE', 8);

/**
 * No error has occurred.
 * @link https://php.net/manual/en/json.constants.php
 */
define('JSON_ERROR_NONE', 0);

/**
 * The maximum stack depth has been exceeded.
 * @link https://php.net/manual/en/json.constants.php
 */
define('JSON_ERROR_DEPTH', 1);

/**
 * Syntax error.
 * @link https://php.net/manual/en/json.constants.php
 */
define('JSON_ERROR_SYNTAX', 4);

/**
 * Decodes JSON objects as PHP array.
 * @since 5.4
 * @link https://php.net/manual/en/json.constants.php
 */
define('JSON_OBJECT_AS_ARRAY', 1);
define('JSON_PARSER_NOTSTRICT', 4);

/**
 * Decodes large integers as their original string value.
 * @since 5.4
 * @link https://php.net/manual/en/json.constants.php
 */
define('JSON_BIGINT_AS_STRING', 2);

/**
 * Ensures that float values are always encoded as a float value.
 * @since 5.6.6
 * @link https://php.net/manual/en/json.constants.php
 */
define('JSON_PRESERVE_ZERO_FRACTION', 1024);

/**
 * The line terminators are kept unescaped when JSON_UNESCAPED_UNICODE is supplied.
 * It uses the same behaviour as it was before PHP 7.1 without this constant. Available since PHP 7.1.0.
 * @link https://php.net/manual/en/json.constants.php
 * @since 7.1
 */
define('JSON_UNESCAPED_LINE_TERMINATORS', 2048);

/**
 * Ignore invalid UTF-8 characters.
 * @since 7.2
 */
define('JSON_INVALID_UTF8_IGNORE', 1048576);

/**
 * Convert invalid UTF-8 characters to \0xfffd (Unicode Character 'REPLACEMENT CHARACTER').
 * @since 7.2
 */
define('JSON_INVALID_UTF8_SUBSTITUTE', 2097152);

/**
 * A key starting with \u0000 character was in the string passed to json_decode() when decoding a JSON object into a PHP object.
 * Available since PHP 7.0.0.
 * @link https://php.net/manual/en/json.constants.php
 * @since 7.0
 */
define('JSON_ERROR_INVALID_PROPERTY_NAME', 9);

/**
 * Single unpaired UTF-16 surrogate in unicode escape contained in the JSON string passed to json_encode().
 * Available since PHP 7.0.0.
 * @link https://php.net/manual/en/json.constants.php
 * @since 7.0
 */
define('JSON_ERROR_UTF16', 10);

/**
 * Throws JsonException if an error occurs instead of setting the global error state
 * that is retrieved with json_last_error() and json_last_error_msg().
 *
 * {@see JSON_PARTIAL_OUTPUT_ON_ERROR} takes precedence over JSON_THROW_ON_ERROR.
 * @since 7.3
 */
define('JSON_THROW_ON_ERROR', 4194304);

/**
 * @since 8.1
 */
define('JSON_ERROR_NON_BACKED_ENUM', 11);

/**
 * Class JsonException
 *
 * <p>A new flag has been added, JSON_THROW_ON_ERROR, which can be used with
 * json_decode() or json_encode() and causes these functions to throw a
 * JsonException upon an error, instead of setting the global error state that
 * is retrieved with json_last_error(). JSON_PARTIAL_OUTPUT_ON_ERROR takes
 * precedence over <b>JSON_THROW_ON_ERROR</b>.
 * </p>
 *
 * @since 7.3
 * @link https://wiki.php.net/rfc/json_throw_on_error
 */
class JsonException extends Exception {}

// End of json v.1.3.1
