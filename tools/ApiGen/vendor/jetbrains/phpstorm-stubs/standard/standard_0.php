<?php

// Start of standard v.5.3.2-0.dotdeb.1

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Deprecated;
use JetBrains\PhpStorm\ExpectedValues;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;
use JetBrains\PhpStorm\Internal\TentativeType;
use JetBrains\PhpStorm\Pure;

class __PHP_Incomplete_Class
{
    /**
     * @var string
     */
    public $__PHP_Incomplete_Class_Name;
}

class php_user_filter
{
    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    public $filtername;

    #[LanguageLevelTypeAware(['8.1' => 'mixed'], default: '')]
    public $params;
    public $stream;

    /**
     * @link https://php.net/manual/en/php-user-filter.filter.php
     * @param resource $in <p> is a resource pointing to a <i>bucket brigade</i< which contains one or more <i>bucket</i> objects containing data to be filtered.</p>
     * @param resource $out <p>is a resource pointing to a second bucket brigade into which your modified buckets should be placed.</p>
     * @param int &$consumed <p>which must <i>always</i> be declared by reference, should be incremented by the length of the data which your filter reads in and alters. In most cases this means you will increment consumed by <i>$bucket->datalen</i> for each <i>$bucket</i>.</p>
     * @param bool $closing <p>If the stream is in the process of closing (and therefore this is the last pass through the filterchain), the closing parameter will be set to <b>TRUE</b>
     * @return int <p>
     * The <b>filter()</b> method must return one of
     * three values upon completion.
     * </p><table>
     *
     * <thead>
     * <tr>
     * <th>Return Value</th>
     * <th>Meaning</th>
     * </tr>
     *
     * </thead>
     *
     * <tbody class="tbody">
     * <tr>
     * <td><b>PSFS_PASS_ON</b></td>
     * <td>
     * Filter processed successfully with data available in the
     * <code class="parameter">out</code> <em>bucket brigade</em>.
     * </td>
     * </tr>
     *
     * <tr>
     * <td><b>PSFS_FEED_ME</b></td>
     * <td>
     * Filter processed successfully, however no data was available to
     * return. More data is required from the stream or prior filter.
     * </td>
     * </tr>
     *
     * <tr>
     * <td><b>PSFS_ERR_FATAL</b> (default)</td>
     * <td>
     * The filter experienced an unrecoverable error and cannot continue.
     * </td>
     * </tr>
     */
    #[TentativeType]
    public function filter(
        $in,
        $out,
        &$consumed,
        #[LanguageLevelTypeAware(['8.0' => 'bool'], default: '')] $closing
    ): int {}

    /**
     * @link https://php.net/manual/en/php-user-filter.oncreate.php
     * @return bool
     */
    #[TentativeType]
    public function onCreate(): bool {}

    /**
     * @link https://php.net/manual/en/php-user-filter.onclose.php
     */
    #[TentativeType]
    public function onClose(): void {}
}

/**
 * Instances of Directory are created by calling the dir() function, not by the new operator.
 */
class Directory
{
    /**
     * @var string The directory that was opened.
     */
    #[PhpStormStubsElementAvailable(to: '8.0')]
    public $path;

    /**
     * @var string The directory that was opened.
     */
    #[PhpStormStubsElementAvailable(from: '8.1')]
    public readonly string $path;

    /**
     * @var resource Can be used with other directory functions such as {@see readdir()}, {@see rewinddir()} and {@see closedir()}.
     */
    #[PhpStormStubsElementAvailable(to: '8.0')]
    public $handle;

    /**
     * @var resource Can be used with other directory functions such as {@see readdir()}, {@see rewinddir()} and {@see closedir()}.
     */
    #[PhpStormStubsElementAvailable(from: '8.1')]
    public readonly mixed $handle;

    /**
     * Close directory handle.
     * Same as closedir(), only dir_handle defaults to $this.
     * @param resource $dir_handle [optional]
     * @link https://secure.php.net/manual/en/directory.close.php
     */
    #[TentativeType]
    public function close(#[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $dir_handle = null): void {}

    /**
     * Rewind directory handle.
     * Same as rewinddir(), only dir_handle defaults to $this.
     * @param resource $dir_handle [optional]
     * @link https://secure.php.net/manual/en/directory.rewind.php
     */
    #[TentativeType]
    public function rewind(#[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $dir_handle = null): void {}

    /**
     * Read entry from directory handle.
     * Same as readdir(), only dir_handle defaults to $this.
     * @param resource $dir_handle [optional]
     * @return string|false
     * @link https://secure.php.net/manual/en/directory.read.php
     */
    #[TentativeType]
    public function read(#[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $dir_handle = null): string|false {}
}

/**
 * Returns the value of a constant
 * @link https://php.net/manual/en/function.constant.php
 * @param string $name <p>
 * The constant name.
 * </p>
 * @return mixed the value of the constant, or null if the constant is not
 * defined.
 */
#[Pure(true)]
function constant(string $name): mixed {}

/**
 * Convert binary data into hexadecimal representation
 * @link https://php.net/manual/en/function.bin2hex.php
 * @param string $string <p>
 * A character.
 * </p>
 * @return string the hexadecimal representation of the given string.
 */
#[Pure]
function bin2hex(string $string): string {}

/**
 * Delay execution
 * @link https://php.net/manual/en/function.sleep.php
 * @param int $seconds <p>
 * Halt time in seconds.
 * </p>
 * @return int|false zero on success, or false on errors. If the call was interrupted
 * by a signal, sleep returns the number of seconds left
 * to sleep.
 */
#[LanguageLevelTypeAware(["8.0" => "int"], default: "int|false")]
function sleep(int $seconds) {}

/**
 * Delay execution in microseconds
 * @link https://php.net/manual/en/function.usleep.php
 * @param int $microseconds <p>
 * Halt time in micro seconds. A micro second is one millionth of a
 * second.
 * </p>
 * @return void
 */
function usleep(int $microseconds): void {}

/**
 * Delay for a number of seconds and nanoseconds
 * @link https://php.net/manual/en/function.time-nanosleep.php
 * @param positive-int $seconds <p>
 * Must be a positive integer.
 * </p>
 * @param positive-int $nanoseconds <p>
 * Must be a positive integer less than 1 billion.
 * </p>
 * @return bool|array true on success or false on failure.
 * <p>
 * If the delay was interrupted by a signal, an associative array will be
 * returned with the components:
 * seconds - number of seconds remaining in
 * the delay
 * nanoseconds - number of nanoseconds
 * remaining in the delay
 * </p>
 */
#[ArrayShape(["seconds" => "int", "nanoseconds" => "int"])]
function time_nanosleep(int $seconds, int $nanoseconds): array|bool {}

/**
 * Make the script sleep until the specified time
 * @link https://php.net/manual/en/function.time-sleep-until.php
 * @param float $timestamp <p>
 * The timestamp when the script should wake.
 * </p>
 * @return bool true on success or false on failure.
 */
function time_sleep_until(float $timestamp): bool {}

/**
 * Parse a time/date generated with <function>strftime</function>
 * @link https://php.net/manual/en/function.strptime.php
 * @param string $timestamp <p>
 * The string to parse (e.g. returned from strftime)
 * </p>
 * @param string $format <p>
 * The format used in date (e.g. the same as
 * used in strftime).
 * </p>
 * <p>
 * For more information about the format options, read the
 * strftime page.
 * </p>
 * @return array|false an array or false on failure.
 * <p>
 * <table>
 * The following parameters are returned in the array
 * <tr valign="top">
 * <td>parameters</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td>"tm_sec"</td>
 * <td>Seconds after the minute (0-61)</td>
 * </tr>
 * <tr valign="top">
 * <td>"tm_min"</td>
 * <td>Minutes after the hour (0-59)</td>
 * </tr>
 * <tr valign="top">
 * <td>"tm_hour"</td>
 * <td>Hour since midnight (0-23)</td>
 * </tr>
 * <tr valign="top">
 * <td>"tm_mday"</td>
 * <td>Day of the month (1-31)</td>
 * </tr>
 * <tr valign="top">
 * <td>"tm_mon"</td>
 * <td>Months since January (0-11)</td>
 * </tr>
 * <tr valign="top">
 * <td>"tm_year"</td>
 * <td>Years since 1900</td>
 * </tr>
 * <tr valign="top">
 * <td>"tm_wday"</td>
 * <td>Days since Sunday (0-6)</td>
 * </tr>
 * <tr valign="top">
 * <td>"tm_yday"</td>
 * <td>Days since January 1 (0-365)</td>
 * </tr>
 * <tr valign="top">
 * <td>"unparsed"</td>
 * <td>the date part which was not
 * recognized using the specified format</td>
 * </tr>
 * </table>
 * </p>
 * @deprecated 8.1
 */
#[Pure(true)]
#[Deprecated(since: '8.1')]
#[ArrayShape([
    'tm_sec' => 'int',
    'tm_min' => 'int',
    'tm_hour' => 'int',
    'tm_mday' => 'int',
    'tm_mon' => 'int',
    'tm_year' => 'int',
    'tm_wday' => 'int',
    'tm_yday' => 'int',
    'unparsed' => 'string'
])]
function strptime(string $timestamp, string $format): array|false {}

/**
 * Flush the output buffer
 * @link https://php.net/manual/en/function.flush.php
 * @return void
 */
function flush(): void {}

/**
 * Wraps a string to a given number of characters
 * @link https://php.net/manual/en/function.wordwrap.php
 * @param string $string <p>
 * The input string.
 * </p>
 * @param int $width [optional] <p>
 * The column width.
 * </p>
 * @param string $break [optional] <p>
 * The line is broken using the optional
 * break parameter.
 * </p>
 * @param bool $cut_long_words [optional] <p>
 * If the cut is set to true, the string is
 * always wrapped at or before the specified width. So if you have
 * a word that is larger than the given width, it is broken apart.
 * (See second example).
 * </p>
 * @return string the given string wrapped at the specified column.
 */
#[Pure]
function wordwrap(string $string, int $width = 75, string $break = "\n", bool $cut_long_words = false): string {}

/**
 * Convert special characters to HTML entities
 * @link https://php.net/manual/en/function.htmlspecialchars.php
 * @param string $string <p>
 * The {@link https://secure.php.net/manual/en/language.types.string.php string} being converted.
 * </p>
 * @param int $flags [optional] <p>
 * A bitmask of one or more of the following flags, which specify how to handle quotes,
 * invalid code unit sequences and the used document type. The default is
 * <em><b>ENT_COMPAT | ENT_HTML401</b></em>.
 * </p><table>
 * <caption><b>Available <em>flags</em> constants</b></caption>
 * <thead>
 * <tr>
 * <th>Constant Name</th>
 * <th>Description</th>
 * </tr>
 *
 * </thead>
 *
 * <tbody>
 * <tr>
 * <td><b>ENT_COMPAT</b></td>
 * <td>Will convert double-quotes and leave single-quotes alone.</td>
 * </tr>
 *
 * <tr>
 * <td><b>ENT_QUOTES</b></td>
 * <td>Will convert both double and single quotes.</td>
 * </tr>
 *
 * <tr>
 * <td><b>ENT_NOQUOTES</b></td>
 * <td>Will leave both double and single quotes unconverted.</td>
 * </tr>
 *
 * <tr>
 * <td><b>ENT_IGNORE</b></td>
 * <td>
 * Silently discard invalid code unit sequences instead of returning
 * an empty string. Using this flag is discouraged as it
 * {@link https://unicode.org/reports/tr36/#Deletion_of_Noncharacters »&nbsp;may have security implications}.
 * </td>
 * </tr>
 *
 * <tr>
 * <td><b>ENT_SUBSTITUTE</b></td>
 * <td>
 * Replace invalid code unit sequences with a Unicode Replacement Character
 * U+FFFD (UTF-8) or &amp;#FFFD; (otherwise) instead of returning an empty string.
 * </td>
 * </tr>
 *
 * <tr>
 * <td><b>ENT_DISALLOWED</b></td>
 * <td>
 * Replace invalid code points for the given document type with a
 * Unicode Replacement Character U+FFFD (UTF-8) or &amp;#FFFD;
 * (otherwise) instead of leaving them as is. This may be useful, for
 * instance, to ensure the well-formedness of XML documents with
 * embedded external content.
 * </td>
 * </tr>
 *
 * <tr>
 * <td><b>ENT_HTML401</b></td>
 * <td>
 * Handle code as HTML 4.01.
 * </td>
 * </tr>
 *
 * <tr>
 * <td><b>ENT_XML1</b></td>
 * <td>
 * Handle code as XML 1.
 * </td>
 * </tr>
 *
 * <tr>
 * <td><b>ENT_XHTML</b></td>
 * <td>
 * Handle code as XHTML.
 * </td>
 * </tr>
 *
 * <tr>
 * <td><b>ENT_HTML5</b></td>
 * <td>
 * Handle code as HTML 5.
 * </td>
 * </tr>
 *
 * </tbody>
 *
 * </table>
 * @param string|null $encoding <p>
 * Defines encoding used in conversion.
 * If omitted, the default value for this argument is ISO-8859-1 in
 * versions of PHP prior to 5.4.0, and UTF-8 from PHP 5.4.0 onwards.
 * </p>
 * <p>
 * For the purposes of this function, the encodings
 * <em>ISO-8859-1</em>, <em>ISO-8859-15</em>,
 * <em>UTF-8</em>, <em>cp866</em>,
 * <em>cp1251</em>, <em>cp1252</em>, and
 * <em>KOI8-R</em> are effectively equivalent, provided the
 * <em><b>string</b></em> itself is valid for the encoding, as
 * the characters affected by  <b>htmlspecialchars()</b> occupy
 * the same positions in all of these encodings.
 * </p>
 * @param bool $double_encode [optional] <p>
 * When <em><b>double_encode</b></em> is turned off PHP will not
 * encode existing html entities, the default is to convert everything.
 * </p>
 * @return string The converted string.
 */
#[Pure]
function htmlspecialchars(string $string, int $flags = ENT_QUOTES|ENT_SUBSTITUTE, ?string $encoding = null, bool $double_encode = true): string {}

/**
 * Convert all applicable characters to HTML entities
 * @link https://php.net/manual/en/function.htmlentities.php
 * @param string $string <p>
 * The input string.
 * </p>
 * @param int $flags [optional] <p>
 * Like htmlspecialchars, the optional second
 * quote_style parameter lets you define what will
 * be done with 'single' and "double" quotes. It takes on one of three
 * constants with the default being ENT_COMPAT:
 * <table>
 * Available quote_style constants
 * <tr valign="top">
 * <td>Constant Name</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td>ENT_COMPAT</td>
 * <td>Will convert double-quotes and leave single-quotes alone.</td>
 * </tr>
 * <tr valign="top">
 * <td>ENT_QUOTES</td>
 * <td>Will convert both double and single quotes.</td>
 * </tr>
 * <tr valign="top">
 * <td>ENT_NOQUOTES</td>
 * <td>Will leave both double and single quotes unconverted.</td>
 * </tr>
 * </table>
 * </p>
 * @param string|null $encoding [optional] <p>
 * Like htmlspecialchars, it takes an optional
 * third argument charset which defines character
 * set used in conversion.
 * Presently, the ISO-8859-1 character set is used as the default.
 * </p>
 * @param bool $double_encode [optional] <p>
 * When double_encode is turned off PHP will not
 * encode existing html entities. The default is to convert everything.
 * </p>
 * @return string the encoded string.
 */
#[Pure]
function htmlentities(string $string, int $flags = ENT_QUOTES|ENT_SUBSTITUTE, ?string $encoding, bool $double_encode = true): string {}

/**
 * Convert HTML entities  to their corresponding characters
 * @link https://php.net/manual/en/function.html-entity-decode.php
 * @param string $string <p>
 * The input string.
 * </p>
 * @param int $flags [optional] <p>
 * The optional second quote_style parameter lets
 * you define what will be done with 'single' and "double" quotes. It takes
 * on one of three constants with the default being
 * ENT_COMPAT:
 * <table>
 * Available quote_style constants
 * <tr valign="top">
 * <td>Constant Name</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td>ENT_COMPAT</td>
 * <td>Will convert double-quotes and leave single-quotes alone.</td>
 * </tr>
 * <tr valign="top">
 * <td>ENT_QUOTES</td>
 * <td>Will convert both double and single quotes.</td>
 * </tr>
 * <tr valign="top">
 * <td>ENT_NOQUOTES</td>
 * <td>Will leave both double and single quotes unconverted.</td>
 * </tr>
 * </table>
 * </p>
 * @param string|null $encoding [optional] <p>
 * The ISO-8859-1 character set is used as default for the optional third
 * charset. This defines the character set used in
 * conversion.
 * </p>
 * @return string the decoded string.
 */
#[Pure]
function html_entity_decode(string $string, int $flags = ENT_QUOTES|ENT_SUBSTITUTE, ?string $encoding): string {}

/**
 * Convert special HTML entities back to characters
 * @link https://php.net/manual/en/function.htmlspecialchars-decode.php
 * @param string $string <p>
 * The string to decode
 * </p>
 * @param int $flags [optional] <p>
 * The quote style. One of the following constants:
 * <table>
 * quote_style constants
 * <tr valign="top">
 * <td>Constant Name</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td>ENT_COMPAT</td>
 * <td>Will convert double-quotes and leave single-quotes alone
 * (default)</td>
 * </tr>
 * <tr valign="top">
 * <td>ENT_QUOTES</td>
 * <td>Will convert both double and single quotes</td>
 * </tr>
 * <tr valign="top">
 * <td>ENT_NOQUOTES</td>
 * <td>Will leave both double and single quotes unconverted</td>
 * </tr>
 * </table>
 * </p>
 * @return string the decoded string.
 */
#[Pure]
function htmlspecialchars_decode(string $string, int $flags = ENT_QUOTES|ENT_SUBSTITUTE): string {}

/**
 * Returns the translation table used by <function>htmlspecialchars</function> and <function>htmlentities</function>
 * @link https://php.net/manual/en/function.get-html-translation-table.php
 * @param int $table <p>
 * There are two new constants (HTML_ENTITIES,
 * HTML_SPECIALCHARS) that allow you to specify the
 * table you want.
 * </p>
 * @param int $flags [optional] <p>
 * Like the htmlspecialchars and
 * htmlentities functions you can optionally specify
 * the quote_style you are working with.
 * See the description
 * of these modes in htmlspecialchars.
 * </p>
 * @param string $encoding [optional] <p>
 * Encoding to use.
 * If omitted, the default value for this argument is ISO-8859-1 in
 * versions of PHP prior to 5.4.0, and UTF-8 from PHP 5.4.0 onwards.
 * </p>
 *
 *
 * <p>
 * The following character sets are supported:
 * </p><table class="doctable table">
 * <caption><strong>Supported charsets</strong></caption>
 *
 * <thead>
 * <tr>
 * <th>Charset</th>
 * <th>Aliases</th>
 * <th>Description</th>
 * </tr>
 *
 * </thead>
 *
 * <tbody class="tbody">
 * <tr>
 * <td>ISO-8859-1</td>
 * <td>ISO8859-1</td>
 * <td>
 * Western European, Latin-1.
 * </td>
 * </tr>
 *
 * <tr>
 * <td>ISO-8859-5</td>
 * <td>ISO8859-5</td>
 * <td>
 * Little used cyrillic charset (Latin/Cyrillic).
 * </td>
 * </tr>
 *
 * <tr>
 * <td>ISO-8859-15</td>
 * <td>ISO8859-15</td>
 * <td>
 * Western European, Latin-9. Adds the Euro sign, French and Finnish
 * letters missing in Latin-1 (ISO-8859-1).
 * </td>
 * </tr>
 *
 * <tr>
 * <td>UTF-8</td>
 * <td class="empty">&nbsp;</td>
 * <td>
 * ASCII compatible multi-byte 8-bit Unicode.
 * </td>
 * </tr>
 *
 * <tr>
 * <td>cp866</td>
 * <td>ibm866, 866</td>
 * <td>
 * DOS-specific Cyrillic charset.
 * </td>
 * </tr>
 *
 * <tr>
 * <td>cp1251</td>
 * <td>Windows-1251, win-1251, 1251</td>
 * <td>
 * Windows-specific Cyrillic charset.
 * </td>
 * </tr>
 *
 * <tr>
 * <td>cp1252</td>
 * <td>Windows-1252, 1252</td>
 * <td>
 * Windows specific charset for Western European.
 * </td>
 * </tr>
 *
 * <tr>
 * <td>KOI8-R</td>
 * <td>koi8-ru, koi8r</td>
 * <td>
 * Russian.
 * </td>
 * </tr>
 *
 * <tr>
 * <td>BIG5</td>
 * <td>950</td>
 * <td>
 * Traditional Chinese, mainly used in Taiwan.
 * </td>
 * </tr>
 *
 * <tr>
 * <td>GB2312</td>
 * <td>936</td>
 * <td>
 * Simplified Chinese, national standard character set.
 * </td>
 * </tr>
 *
 * <tr>
 * <td>BIG5-HKSCS</td>
 * <td class="empty">&nbsp;</td>
 * <td>
 * Big5 with Hong Kong extensions, Traditional Chinese.
 * </td>
 * </tr>
 *
 * <tr>
 * <td>Shift_JIS</td>
 * <td>SJIS, SJIS-win, cp932, 932</td>
 * <td>
 * Japanese
 * </td>
 * </tr>
 *
 * <tr>
 * <td>EUC-JP</td>
 * <td>EUCJP, eucJP-win</td>
 * <td>
 * Japanese
 * </td>
 * </tr>
 *
 * <tr>
 * <td>MacRoman</td>
 * <td class="empty">&nbsp;</td>
 * <td>
 * Charset that was used by Mac OS.
 * </td>
 * </tr>
 *
 * <tr>
 * <td><em>''</em></td>
 * <td class="empty">&nbsp;</td>
 * <td>
 * An empty string activates detection from script encoding (Zend multibyte),
 * {@link https://php.net/manual/en/ini.core.php#ini.default-charset default_charset} and current
 * locale {@link https://php.net/manual/en/function.nl-langinfo.php nl_langinfo()} and
 * {@link https://php.net/manual/en/function.setlocale.php setlocale()}), in this order. Not recommended.
 * </td>
 * </tr>
 *
 * </tbody>
 *
 * </table>
 *
 * <blockquote><p><strong>Note</strong>:
 *
 * Any other character sets are not recognized. The default encoding will be
 * used instead and a warning will be emitted.
 *
 * </p></blockquote>
 * @return array the translation table as an array.
 */
#[Pure]
function get_html_translation_table(
    int $table = 0,
    int $flags = ENT_QUOTES|ENT_SUBSTITUTE,
    #[PhpStormStubsElementAvailable(from: '7.0')] string $encoding = "UTF-8"
): array {}

/**
 * Calculate the sha1 hash of a string
 * @link https://php.net/manual/en/function.sha1.php
 * @param string $string <p>
 * The input string.
 * </p>
 * @param bool $binary [optional] <p>
 * If the optional raw_output is set to true,
 * then the sha1 digest is instead returned in raw binary format with a
 * length of 20, otherwise the returned value is a 40-character
 * hexadecimal number.
 * </p>
 * @return string the sha1 hash as a string.
 */
#[Pure]
function sha1(string $string, bool $binary = false): string {}

/**
 * Calculate the sha1 hash of a file
 * @link https://php.net/manual/en/function.sha1-file.php
 * @param string $filename <p>
 * The filename
 * </p>
 * @param bool $binary [optional] <p>
 * When true, returns the digest in raw binary format with a length of
 * 20.
 * </p>
 * @return string|false a string on success, false otherwise.
 */
#[Pure(true)]
function sha1_file(string $filename, bool $binary = false): string|false {}

/**
 * Calculate the md5 hash of a string
 * @link https://php.net/manual/en/function.md5.php
 * @param string $string <p>
 * The string.
 * </p>
 * @param bool $binary [optional] <p>
 * If the optional raw_output is set to true,
 * then the md5 digest is instead returned in raw binary format with a
 * length of 16.
 * </p>
 * @return string the hash as a 32-character hexadecimal number.
 */
#[Pure]
function md5(string $string, bool $binary = false): string {}

/**
 * Calculates the md5 hash of a given file
 * @link https://php.net/manual/en/function.md5-file.php
 * @param string $filename <p>
 * The filename
 * </p>
 * @param bool $binary [optional] <p>
 * When true, returns the digest in raw binary format with a length of
 * 16.
 * </p>
 * @return string|false a string on success, false otherwise.
 */
#[Pure(true)]
function md5_file(string $filename, bool $binary = false): string|false {}

/**
 * Calculates the crc32 polynomial of a string
 * @link https://php.net/manual/en/function.crc32.php
 * @param string $string <p>
 * The data.
 * </p>
 * @return int the crc32 checksum of str as an integer..1
 */
#[Pure]
function crc32(string $string): int {}

/**
 * Parse a binary IPTC block into single tags.
 * Note: This function does not require the GD image library.
 * @link https://php.net/manual/en/function.iptcparse.php
 * @param string $iptc_block <p>
 * A binary IPTC block.
 * </p>
 * @return array|false an array using the tagmarker as an index and the value as the
 * value. It returns false on error or if no IPTC data was found.
 */
#[Pure]
function iptcparse(string $iptc_block): array|false {}

/**
 * Embeds binary IPTC data into a JPEG image.
 * Note: This function does not require the GD image library.
 * @link https://php.net/manual/en/function.iptcembed.php
 * @param string $iptc_data <p>
 * The data to be written.
 * </p>
 * @param string $filename <p>
 * Path to the JPEG image.
 * </p>
 * @param int $spool <p>
 * Spool flag. If the spool flag is over 2 then the JPEG will be
 * returned as a string.
 * </p>
 * @return string|bool If success and spool flag is lower than 2 then the JPEG will not be
 * returned as a string, false on errors.
 */
function iptcembed(string $iptc_data, string $filename, int $spool = 0): string|bool {}

/**
 * Get the size of an image
 * @link https://php.net/manual/en/function.getimagesize.php
 * @param string $filename <p>
 * This parameter specifies the file you wish to retrieve information
 * about. It can reference a local file or (configuration permitting) a
 * remote file using one of the supported streams.
 * </p>
 * @param array &$image_info [optional] <p>
 * This optional parameter allows you to extract some extended
 * information from the image file. Currently, this will return the
 * different JPG APP markers as an associative array.
 * Some programs use these APP markers to embed text information in
 * images. A very common one is to embed
 * IPTC information in the APP13 marker.
 * You can use the iptcparse function to parse the
 * binary APP13 marker into something readable.
 * </p>
 * @return array|false an array with 7 elements.
 * <p>
 * Index 0 and 1 contains respectively the width and the height of the image.
 * </p>
 * <p>
 * Some formats may contain no image or may contain multiple images. In these
 * cases, getimagesize might not be able to properly
 * determine the image size. getimagesize will return
 * zero for width and height in these cases.
 * </p>
 * <p>
 * Index 2 is one of the IMAGETYPE_XXX constants indicating
 * the type of the image.
 * </p>
 * <p>
 * Index 3 is a text string with the correct
 * height="yyy" width="xxx" string that can be used
 * directly in an IMG tag.
 * </p>
 * <p>
 * mime is the correspondant MIME type of the image.
 * This information can be used to deliver images with correct the HTTP
 * Content-type header:
 * getimagesize and MIME types
 * </p>
 * <p>
 * channels will be 3 for RGB pictures and 4 for CMYK
 * pictures.
 * </p>
 * <p>
 * bits is the number of bits for each color.
 * </p>
 * <p>
 * For some image types, the presence of channels and
 * bits values can be a bit
 * confusing. As an example, GIF always uses 3 channels
 * per pixel, but the number of bits per pixel cannot be calculated for an
 * animated GIF with a global color table.
 * </p>
 * <p>
 * On failure, false is returned.
 * </p>
 */
#[ArrayShape([0 => "int", 1 => "int", 2 => "int", 3 => "string", "bits" => "int", "channels" => "int", "mime" => "string"])]
function getimagesize(string $filename, &$image_info): array|false {}

/**
 * Get Mime-Type for image-type returned by getimagesize, exif_read_data, exif_thumbnail, exif_imagetype
 * @link https://php.net/manual/en/function.image-type-to-mime-type.php
 * @param int $image_type <p>
 * One of the IMAGETYPE_XXX constants.
 * </p>
 * @return string The returned values are as follows
 * <table>
 * Returned values Constants
 * <tr valign="top">
 * <td>imagetype</td>
 * <td>Returned value</td>
 * </tr>
 * <tr valign="top">
 * <td>IMAGETYPE_GIF</td>
 * <td>image/gif</td>
 * </tr>
 * <tr valign="top">
 * <td>IMAGETYPE_JPEG</td>
 * <td>image/jpeg</td>
 * </tr>
 * <tr valign="top">
 * <td>IMAGETYPE_PNG</td>
 * <td>image/png</td>
 * </tr>
 * <tr valign="top">
 * <td>IMAGETYPE_SWF</td>
 * <td>application/x-shockwave-flash</td>
 * </tr>
 * <tr valign="top">
 * <td>IMAGETYPE_PSD</td>
 * <td>image/psd</td>
 * </tr>
 * <tr valign="top">
 * <td>IMAGETYPE_BMP</td>
 * <td>image/bmp</td>
 * </tr>
 * <tr valign="top">
 * <td>IMAGETYPE_TIFF_II (intel byte order)</td>
 * <td>image/tiff</td>
 * </tr>
 * <tr valign="top">
 * <td>
 * IMAGETYPE_TIFF_MM (motorola byte order)
 * </td>
 * <td>image/tiff</td>
 * </tr>
 * <tr valign="top">
 * <td>IMAGETYPE_JPC</td>
 * <td>application/octet-stream</td>
 * </tr>
 * <tr valign="top">
 * <td>IMAGETYPE_JP2</td>
 * <td>image/jp2</td>
 * </tr>
 * <tr valign="top">
 * <td>IMAGETYPE_JPX</td>
 * <td>application/octet-stream</td>
 * </tr>
 * <tr valign="top">
 * <td>IMAGETYPE_JB2</td>
 * <td>application/octet-stream</td>
 * </tr>
 * <tr valign="top">
 * <td>IMAGETYPE_SWC</td>
 * <td>application/x-shockwave-flash</td>
 * </tr>
 * <tr valign="top">
 * <td>IMAGETYPE_IFF</td>
 * <td>image/iff</td>
 * </tr>
 * <tr valign="top">
 * <td>IMAGETYPE_WBMP</td>
 * <td>image/vnd.wap.wbmp</td>
 * </tr>
 * <tr valign="top">
 * <td>IMAGETYPE_XBM</td>
 * <td>image/xbm</td>
 * </tr>
 * <tr valign="top">
 * <td>IMAGETYPE_ICO</td>
 * <td>image/vnd.microsoft.icon</td>
 * </tr>
 * </table>
 */
#[Pure]
function image_type_to_mime_type(int $image_type): string {}

/**
 * Get file extension for image type
 * @link https://php.net/manual/en/function.image-type-to-extension.php
 * @param int $image_type <p>
 * One of the IMAGETYPE_XXX constant.
 * </p>
 * @param bool $include_dot [optional] <p>
 * Removed since 8.0.
 * Whether to prepend a dot to the extension or not. Default to true.
 * </p>
 * @return string|false A string with the extension corresponding to the given image type.
 */
#[Pure]
function image_type_to_extension(int $image_type, bool $include_dot = true): string|false {}

/**
 * Outputs information about PHP's configuration
 * @link https://php.net/manual/en/function.phpinfo.php
 * @param int $flags [optional] <p>
 * The output may be customized by passing one or more of the
 * following constants bitwise values summed
 * together in the optional what parameter.
 * One can also combine the respective constants or bitwise values
 * together with the or operator.
 * </p>
 * <p>
 * <table>
 * phpinfo options
 * <tr valign="top">
 * <td>Name (constant)</td>
 * <td>Value</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td>INFO_GENERAL</td>
 * <td>1</td>
 * <td>
 * The configuration line, "php.ini" location, build date, Web
 * Server, System and more.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>INFO_CREDITS</td>
 * <td>2</td>
 * <td>
 * PHP Credits. See also phpcredits.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>INFO_CONFIGURATION</td>
 * <td>4</td>
 * <td>
 * Current Local and Main values for PHP directives. See
 * also ini_get.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>INFO_MODULES</td>
 * <td>8</td>
 * <td>
 * Loaded modules and their respective settings. See also
 * get_loaded_extensions.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>INFO_ENVIRONMENT</td>
 * <td>16</td>
 * <td>
 * Environment Variable information that's also available in
 * $_ENV.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>INFO_VARIABLES</td>
 * <td>32</td>
 * <td>
 * Shows all
 * predefined variables from EGPCS (Environment, GET,
 * POST, Cookie, Server).
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>INFO_LICENSE</td>
 * <td>64</td>
 * <td>
 * PHP License information. See also the license FAQ.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>INFO_ALL</td>
 * <td>-1</td>
 * <td>
 * Shows all of the above.
 * </td>
 * </tr>
 * </table>
 * </p>
 * @return bool true on success or false on failure.
 */
#[LanguageLevelTypeAware(['8.2' => 'true'], default: 'bool')]
function phpinfo(#[ExpectedValues(flags: [INFO_GENERAL, INFO_CREDITS, INFO_CONFIGURATION, INFO_MODULES, INFO_ENVIRONMENT, INFO_VARIABLES, INFO_LICENSE, INFO_ALL])] int $flags = INFO_ALL): bool {}

/**
 * Gets the current PHP version
 * @link https://php.net/manual/en/function.phpversion.php
 * @param string|null $extension [optional] <p>
 * An optional extension name.
 * </p>
 * @return string|false If the optional extension parameter is
 * specified, phpversion returns the version of that
 * extension, or false if there is no version information associated or
 * the extension isn't enabled.
 */
#[Pure]
function phpversion(?string $extension): string|false {}

/**
 * Prints out the credits for PHP
 * @link https://php.net/manual/en/function.phpcredits.php
 * @param int $flags [optional] <p>
 * To generate a custom credits page, you may want to use the
 * flag parameter.
 * </p>
 * <p>
 * <table>
 * Pre-defined phpcredits flags
 * <tr valign="top">
 * <td>name</td>
 * <td>description</td>
 * </tr>
 * <tr valign="top">
 * <td>CREDITS_ALL</td>
 * <td>
 * All the credits, equivalent to using: CREDITS_DOCS +
 * CREDITS_GENERAL + CREDITS_GROUP +
 * CREDITS_MODULES + CREDITS_FULLPAGE.
 * It generates a complete stand-alone HTML page with the appropriate tags.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>CREDITS_DOCS</td>
 * <td>The credits for the documentation team</td>
 * </tr>
 * <tr valign="top">
 * <td>CREDITS_FULLPAGE</td>
 * <td>
 * Usually used in combination with the other flags. Indicates
 * that a complete stand-alone HTML page needs to be
 * printed including the information indicated by the other
 * flags.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>CREDITS_GENERAL</td>
 * <td>
 * General credits: Language design and concept, PHP 4.0
 * authors and SAPI module.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>CREDITS_GROUP</td>
 * <td>A list of the core developers</td>
 * </tr>
 * <tr valign="top">
 * <td>CREDITS_MODULES</td>
 * <td>
 * A list of the extension modules for PHP, and their authors
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>CREDITS_SAPI</td>
 * <td>
 * A list of the server API modules for PHP, and their authors
 * </td>
 * </tr>
 * </table>
 * </p>
 * @return bool true on success or false on failure.
 */
#[LanguageLevelTypeAware(['8.2' => 'true'], default: 'bool')]
function phpcredits(int $flags = CREDITS_ALL): bool {}

/**
 * Gets the logo guid
 * @removed 5.5
 * @link https://php.net/manual/en/function.php-logo-guid.php
 * @return string PHPE9568F34-D428-11d2-A769-00AA001ACF42.
 */
#[Pure]
function php_logo_guid(): string {}

/**
 * @removed 5.5
 */
function php_real_logo_guid() {}

/**
 * @removed 5.5
 */
function php_egg_logo_guid() {}

/**
 * Gets the Zend guid
 * @removed 5.5
 * @link https://php.net/manual/en/function.zend-logo-guid.php
 * @return string PHPE9568F35-D428-11d2-A769-00AA001ACF42.
 */
function zend_logo_guid(): string {}

/**
 * Returns the type of interface between web server and PHP
 * @link https://php.net/manual/en/function.php-sapi-name.php
 * @return string|false the interface type, as a lowercase string.
 * <p>
 * Although not exhaustive, the possible return values include
 * aolserver, apache,
 * apache2filter, apache2handler,
 * caudium, cgi (until PHP 5.3),
 * cgi-fcgi, cli,
 * continuity, embed,
 * isapi, litespeed,
 * milter, nsapi,
 * phttpd, pi3web, roxen,
 * thttpd, tux, and webjames.
 * </p>
 */
#[Pure]
function php_sapi_name(): string|false {}

/**
 * Returns information about the operating system PHP is running on
 * @link https://php.net/manual/en/function.php-uname.php
 * @param string $mode [optional] <p>
 * mode is a single character that defines what
 * information is returned:
 * 'a': This is the default. Contains all modes in
 * the sequence "s n r v m".</p>
 * @return string the description, as a string.
 */
#[Pure(true)]
function php_uname(#[PhpStormStubsElementAvailable(from: '7.0')] string $mode = 'a'): string {}

/**
 * Return a list of .ini files parsed from the additional ini dir
 * @link https://php.net/manual/en/function.php-ini-scanned-files.php
 * @return string|false a comma-separated string of .ini files on success. Each comma is
 * followed by a newline. If the directive --with-config-file-scan-dir wasn't set,
 * false is returned. If it was set and the directory was empty, an
 * empty string is returned. If a file is unrecognizable, the file will
 * still make it into the returned string but a PHP error will also result.
 * This PHP error will be seen both at compile time and while using
 * php_ini_scanned_files.
 */
#[Pure]
function php_ini_scanned_files(): string|false {}

/**
 * Retrieve a path to the loaded php.ini file
 * @link https://php.net/manual/en/function.php-ini-loaded-file.php
 * @return string|false The loaded "php.ini" path, or false if one is not loaded.
 * @since 5.2.4
 */
#[Pure]
function php_ini_loaded_file(): string|false {}

/**
 * String comparisons using a "natural order" algorithm
 * @link https://php.net/manual/en/function.strnatcmp.php
 * @param string $string1 <p>
 * The first string.
 * </p>
 * @param string $string2 <p>
 * The second string.
 * </p>
 * @return int Similar to other string comparison functions, this one returns &lt; 0 if
 * str1 is less than str2; &gt;
 * 0 if str1 is greater than
 * str2, and 0 if they are equal.
 */
#[Pure]
function strnatcmp(string $string1, string $string2): int {}

/**
 * Case insensitive string comparisons using a "natural order" algorithm
 * @link https://php.net/manual/en/function.strnatcasecmp.php
 * @param string $string1 <p>
 * The first string.
 * </p>
 * @param string $string2 <p>
 * The second string.
 * </p>
 * @return int Similar to other string comparison functions, this one returns &lt; 0 if
 * str1 is less than str2 &gt;
 * 0 if str1 is greater than
 * str2, and 0 if they are equal.
 */
#[Pure]
function strnatcasecmp(string $string1, string $string2): int {}

/**
 * Count the number of substring occurrences
 * @link https://php.net/manual/en/function.substr-count.php
 * @param string $haystack <p>
 * The string to search in
 * </p>
 * @param string $needle <p>
 * The substring to search for
 * </p>
 * @param int $offset <p>
 * The offset where to start counting
 * </p>
 * @param int|null $length [optional] <p>
 * The maximum length after the specified offset to search for the
 * substring. It outputs a warning if the offset plus the length is
 * greater than the haystack length.
 * </p>
 * @return int<0,max> This functions returns an integer.
 */
#[Pure]
function substr_count(string $haystack, string $needle, int $offset = 0, ?int $length): int {}

/**
 * Finds the length of the initial segment of a string consisting
 * entirely of characters contained within a given mask.
 * @link https://php.net/manual/en/function.strspn.php
 * @param string $string <p>
 * The string to examine.
 * </p>
 * @param string $characters <p>
 * The list of allowable characters to include in counted segments.
 * </p>
 * @param int $offset <p>
 * The position in subject to
 * start searching.
 * </p>
 * <p>
 * If start is given and is non-negative,
 * then strspn will begin
 * examining subject at
 * the start'th position. For instance, in
 * the string 'abcdef', the character at
 * position 0 is 'a', the
 * character at position 2 is
 * 'c', and so forth.
 * </p>
 * <p>
 * If start is given and is negative,
 * then strspn will begin
 * examining subject at
 * the start'th position from the end
 * of subject.
 * </p>
 * @param int|null $length [optional] <p>
 * The length of the segment from subject
 * to examine.
 * </p>
 * <p>
 * If length is given and is non-negative,
 * then subject will be examined
 * for length characters after the starting
 * position.
 * </p>
 * <p>
 * If lengthis given and is negative,
 * then subject will be examined from the
 * starting position up to length
 * characters from the end of subject.
 * </p>
 * @return int the length of the initial segment of str1
 * which consists entirely of characters in str2.
 */
#[Pure]
function strspn(string $string, string $characters, int $offset = 0, ?int $length): int {}

/**
 * Find length of initial segment not matching mask
 * @link https://php.net/manual/en/function.strcspn.php
 * @param string $string <p>
 * The first string.
 * </p>
 * @param string $characters <p>
 * The second string.
 * </p>
 * @param int $offset <p>
 * The start position of the string to examine.
 * </p>
 * @param int|null $length [optional] <p>
 * The length of the string to examine.
 * </p>
 * @return int the length of the segment as an integer.
 */
#[Pure]
function strcspn(string $string, string $characters, int $offset = 0, ?int $length): int {}

/**
 * Tokenize string
 * Note that only the first call to strtok uses the string argument.
 * Every subsequent call to strtok only needs the token to use, as it keeps track of where it is in the current string.
 * To start over, or to tokenize a new string you simply call strtok with the string argument again to initialize it.
 * Note that you may put multiple tokens in the token parameter.
 * The string will be tokenized when any one of the characters in the argument are found.
 * @link https://php.net/manual/en/function.strtok.php
 * @param string $string <p>
 * The string being split up into smaller strings (tokens).
 * </p>
 * @param string|null $token <p>
 * The delimiter used when splitting up str.
 * </p>
 * @return string|false A string token.
 */
function strtok(
    string $string,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.0')] $token,
    #[PhpStormStubsElementAvailable(from: '7.1')] ?string $token = null
): string|false {}
