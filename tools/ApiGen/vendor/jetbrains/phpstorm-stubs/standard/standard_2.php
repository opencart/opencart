<?php

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;
use JetBrains\PhpStorm\Internal\ReturnTypeContract as TypeContract;
use JetBrains\PhpStorm\Pure;

/**
 * Query language and locale information
 * @link https://php.net/manual/en/function.nl-langinfo.php
 * @param int $item <p>
 * item may be an integer value of the element or the
 * constant name of the element. The following is a list of constant names
 * for item that may be used and their description.
 * Some of these constants may not be defined or hold no value for certain
 * locales.</p>
 * nl_langinfo Constants
 * <table>
 * <tr valign="top">
 * <td>Constant</td>
 * <td>Description</td>
 * </tr>
 * <tr colspan="2" valign="top" bgcolor="silver">
 * <td >LC_TIME Category Constants</td>
 * </tr>
 * <tr valign="top">
 * <td>ABDAY_(1-7)</td>
 * <td>Abbreviated name of n-th day of the week.</td>
 * </tr>
 * <tr valign="top">
 * <td>DAY_(1-7)</td>
 * <td>Name of the n-th day of the week (DAY_1 = Sunday).</td>
 * </tr>
 * <tr valign="top">
 * <td>ABMON_(1-12)</td>
 * <td>Abbreviated name of the n-th month of the year.</td>
 * </tr>
 * <tr valign="top">
 * <td>MON_(1-12)</td>
 * <td>Name of the n-th month of the year.</td>
 * </tr>
 * <tr valign="top">
 * <td>AM_STR</td>
 * <td>String for Ante meridian.</td>
 * </tr>
 * <tr valign="top">
 * <td>PM_STR</td>
 * <td>String for Post meridian.</td>
 * </tr>
 * <tr valign="top">
 * <td>D_T_FMT</td>
 * <td>String that can be used as the format string for strftime to represent time and date.</td>
 * </tr>
 * <tr valign="top">
 * <td>D_FMT</td>
 * <td>String that can be used as the format string for strftime to represent date.</td>
 * </tr>
 * <tr valign="top">
 * <td>T_FMT</td>
 * <td>String that can be used as the format string for strftime to represent time.</td>
 * </tr>
 * <tr valign="top">
 * <td>T_FMT_AMPM</td>
 * <td>String that can be used as the format string for strftime to represent time in 12-hour format with ante/post meridian.</td>
 * </tr>
 * <tr valign="top">
 * <td>ERA</td>
 * <td>Alternate era.</td>
 * </tr>
 * <tr valign="top">
 * <td>ERA_YEAR</td>
 * <td>Year in alternate era format.</td>
 * </tr>
 * <tr valign="top">
 * <td>ERA_D_T_FMT</td>
 * <td>Date and time in alternate era format (string can be used in strftime).</td>
 * </tr>
 * <tr valign="top">
 * <td>ERA_D_FMT</td>
 * <td>Date in alternate era format (string can be used in strftime).</td>
 * </tr>
 * <tr valign="top">
 * <td>ERA_T_FMT</td>
 * <td>Time in alternate era format (string can be used in strftime).</td>
 * </tr>
 * <tr colspan="2" valign="top" bgcolor="silver">
 * <td>LC_MONETARY Category Constants</td>
 * </tr>
 * <tr valign="top">
 * <td>INT_CURR_SYMBOL</td>
 * <td>International currency symbol.</td>
 * </tr>
 * <tr valign="top">
 * <td>CURRENCY_SYMBOL</td>
 * <td>Local currency symbol.</td>
 * </tr>
 * <tr valign="top">
 * <td>CRNCYSTR</td>
 * <td>Same value as CURRENCY_SYMBOL.</td>
 * </tr>
 * <tr valign="top">
 * <td>MON_DECIMAL_POINT</td>
 * <td>Decimal point character.</td>
 * </tr>
 * <tr valign="top">
 * <td>MON_THOUSANDS_SEP</td>
 * <td>Thousands separator (groups of three digits).</td>
 * </tr>
 * <tr valign="top">
 * <td>MON_GROUPING</td>
 * <td>Like "grouping" element.</td>
 * </tr>
 * <tr valign="top">
 * <td>POSITIVE_SIGN</td>
 * <td>Sign for positive values.</td>
 * </tr>
 * <tr valign="top">
 * <td>NEGATIVE_SIGN</td>
 * <td>Sign for negative values.</td>
 * </tr>
 * <tr valign="top">
 * <td>INT_FRAC_DIGITS</td>
 * <td>International fractional digits.</td>
 * </tr>
 * <tr valign="top">
 * <td>FRAC_DIGITS</td>
 * <td>Local fractional digits.</td>
 * </tr>
 * <tr valign="top">
 * <td>P_CS_PRECEDES</td>
 * <td>Returns 1 if CURRENCY_SYMBOL precedes a positive value.</td>
 * </tr>
 * <tr valign="top">
 * <td>P_SEP_BY_SPACE</td>
 * <td>Returns 1 if a space separates CURRENCY_SYMBOL from a positive value.</td>
 * </tr>
 * <tr valign="top">
 * <td>N_CS_PRECEDES</td>
 * <td>Returns 1 if CURRENCY_SYMBOL precedes a negative value.</td>
 * </tr>
 * <tr valign="top">
 * <td>N_SEP_BY_SPACE</td>
 * <td>Returns 1 if a space separates CURRENCY_SYMBOL from a negative value.</td>
 * </tr>
 * <tr valign="top">
 * <td>P_SIGN_POSN</td>
 * <td>Returns 0 if parentheses surround the quantity and CURRENCY_SYMBOL.</td>
 * </tr>
 * </table>
 * @return string|false the element as a string, or false if item
 * is not valid.
 */
#[Pure(true)]
function nl_langinfo(int $item): string|false {}

/**
 * Calculate the soundex key of a string
 * @link https://php.net/manual/en/function.soundex.php
 * @param string $string <p>
 * The input string.
 * </p>
 * @return string the soundex key as a string.
 */
#[Pure]
function soundex(string $string): string {}

/**
 * Calculate Levenshtein distance between two strings
 * @link https://php.net/manual/en/function.levenshtein.php
 * Note: In its simplest form the function will take only the two strings
 * as parameter and will calculate just the number of insert, replace and
 * delete operations needed to transform str1 into str2.
 * Note: A second variant will take three additional parameters that define
 * the cost of insert, replace and delete operations. This is more general
 * and adaptive than variant one, but not as efficient.
 * @param string $string1 <p>
 * One of the strings being evaluated for Levenshtein distance.
 * </p>
 * @param string $string2 <p>
 * One of the strings being evaluated for Levenshtein distance.
 * </p>
 * @param int $insertion_cost [optional] <p>
 * Defines the cost of insertion.
 * </p>
 * @param int $replacement_cost [optional] <p>
 * Defines the cost of replacement.
 * </p>
 * @param int $deletion_cost [optional] <p>
 * Defines the cost of deletion.
 * </p>
 * @return int This function returns the Levenshtein-Distance between the
 * two argument strings or -1, if one of the argument strings
 * is longer than the limit of 255 characters.
 */
function levenshtein(string $string1, string $string2, int $insertion_cost = 1, int $replacement_cost = 1, int $deletion_cost = 1): int {}

/**
 * Generate a single-byte string from a number
 * @link https://php.net/manual/en/function.chr.php
 * @param int $codepoint <p>
 * The ascii code.
 * </p>
 * @return string the specified character.
 */
#[Pure]
function chr(int $codepoint): string {}

/**
 * Convert the first byte of a string to a value between 0 and 255
 * @link https://php.net/manual/en/function.ord.php
 * @param string $character <p>
 * A character.
 * </p>
 * @return int<0, 255> the ASCII value as an integer.
 */
#[Pure]
function ord(string $character): int {}

/**
 * Parses the string into variables
 * @link https://php.net/manual/en/function.parse-str.php
 * @param string $string <p>
 * The input string.
 * </p>
 * @param array &$result <p>
 * If the second parameter arr is present,
 * variables are stored in this variable as array elements instead.<br/>
 * Since 7.2.0 this parameter is not optional.
 * </p>
 * @return void
 */
function parse_str(
    string $string,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] &$result = [],
    #[PhpStormStubsElementAvailable(from: '8.0')] &$result
): void {}

/**
 * Parse a CSV string into an array
 * @link https://php.net/manual/en/function.str-getcsv.php
 * @param string $string <p>
 * The string to parse.
 * </p>
 * @param string $separator [optional] <p>
 * Set the field delimiter (one character only).
 * </p>
 * @param string $enclosure [optional] <p>
 * Set the field enclosure character (one character only).
 * </p>
 * @param string $escape [optional] <p>
 * Set the escape character (one character only).
 * Defaults as a backslash (\)
 * </p>
 * @return array an indexed array containing the fields read.
 */
#[Pure]
function str_getcsv(string $string, string $separator = ",", string $enclosure = '"', string $escape = "\\"): array {}

/**
 * Pad a string to a certain length with another string
 * @link https://php.net/manual/en/function.str-pad.php
 * @param string $string <p>
 * The input string.
 * </p>
 * @param int $length <p>
 * If the value of pad_length is negative,
 * less than, or equal to the length of the input string, no padding
 * takes place.
 * </p>
 * @param string $pad_string [optional] <p>
 * The pad_string may be truncated if the
 * required number of padding characters can't be evenly divided by the
 * pad_string's length.
 * </p>
 * @param int $pad_type [optional] <p>
 * Optional argument pad_type can be
 * STR_PAD_RIGHT, STR_PAD_LEFT,
 * or STR_PAD_BOTH. If
 * pad_type is not specified it is assumed to be
 * STR_PAD_RIGHT.
 * </p>
 * @return string the padded string.
 */
#[Pure]
function str_pad(string $string, int $length, string $pad_string = " ", int $pad_type = STR_PAD_RIGHT): string {}

/**
 * Alias:
 * {@see rtrim}
 * @param string $string The input string.
 * @param string $characters [optional]
 * @return string the modified string.
 * @link https://php.net/manual/en/function.chop.php
 * @see rtrim()
 */
#[Pure]
function chop(string $string, string $characters): string {}

/**
 * Alias:
 * {@see strstr}
 * @link https://php.net/manual/en/function.strchr.php
 * Note: This function is case-sensitive. For case-insensitive searches, use stristr().
 * Note: If you only want to determine if a particular needle occurs within haystack,
 * use the faster and less memory intensive function strpos() instead.
 *
 * @param string $haystack The input string.
 * @param string $needle If needle is not a string, it is converted to an integer and applied as the ordinal value of a character.
 * @param bool $before_needle [optional] If TRUE, strstr() returns the part of the haystack before the first occurrence of the needle (excluding the needle).
 * @return string|false Returns the portion of string, or FALSE if needle is not found.
 */
#[Pure]
function strchr(string $haystack, string $needle, bool $before_needle = false): string|false {}

/**
 * Return a formatted string
 * @link https://php.net/manual/en/function.sprintf.php
 * @param string $format <p>
 * The format string is composed of zero or more directives:
 * ordinary characters (excluding %) that are
 * copied directly to the result, and conversion
 * specifications, each of which results in fetching its
 * own parameter. This applies to both sprintf
 * and printf.
 * </p>
 * <p>
 * Each conversion specification consists of a percent sign
 * (%), followed by one or more of these
 * elements, in order:
 * An optional sign specifier that forces a sign
 * (- or +) to be used on a number. By default, only the - sign is used
 * on a number if it's negative. This specifier forces positive numbers
 * to have the + sign attached as well, and was added in PHP 4.3.0.</p>
 * @param string|int|float ...$values <p>
 * </p>
 * @return string a string produced according to the formatting string
 * format.
 */
#[Pure]
function sprintf(
    string $format,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '5.6')] $values,
    mixed ...$values
): string {}

/**
 * Output a formatted string
 * @link https://php.net/manual/en/function.printf.php
 * @param string $format <p>
 * See sprintf for a description of
 * format.
 * </p>
 * @param string|int|float ...$values [optional] <p>
 * </p>
 * @return int the length of the outputted string.
 */
function printf(string $format, mixed ...$values): int {}

/**
 * Output a formatted string
 * @link https://php.net/manual/en/function.vprintf.php
 * @param string $format <p>
 * See sprintf for a description of
 * format.
 * </p>
 * @param array $values <p>
 * </p>
 * @return int the length of the outputted string.
 */
function vprintf(string $format, array $values): int {}

/**
 * Return a formatted string
 * @link https://php.net/manual/en/function.vsprintf.php
 * @param string $format <p>
 * See sprintf for a description of
 * format.
 * </p>
 * @param array $values <p>
 * </p>
 * @return string Return array values as a formatted string according to
 * format (which is described in the documentation
 * for sprintf).
 */
#[Pure]
function vsprintf(string $format, array $values): string {}

/**
 * Write a formatted string to a stream
 * @link https://php.net/manual/en/function.fprintf.php
 * @param resource $stream &fs.file.pointer;
 * @param string $format <p>
 * See sprintf for a description of
 * format.
 * </p>
 * @param mixed ...$values [optional] <p>
 * </p>
 * @return int the length of the string written.
 */
function fprintf($stream, string $format, mixed ...$values): int {}

/**
 * Write a formatted string to a stream
 * @link https://php.net/manual/en/function.vfprintf.php
 * @param resource $stream <p>
 * </p>
 * @param string $format <p>
 * See sprintf for a description of
 * format.
 * </p>
 * @param array $values <p>
 * </p>
 * @return int the length of the outputted string.
 */
function vfprintf($stream, string $format, array $values): int {}

/**
 * Parses input from a string according to a format
 * @link https://php.net/manual/en/function.sscanf.php
 * @param string $string <p>
 * The input string being parsed.
 * </p>
 * @param string $format <p>
 * The interpreted format for str, which is
 * described in the documentation for sprintf with
 * following differences:
 * Function is not locale-aware.
 * F, g, G and
 * b are not supported.
 * D stands for decimal number.
 * i stands for integer with base detection.
 * n stands for number of characters processed so far.
 * </p>
 * @param mixed &...$vars [optional]
 * @return array|int|null If only
 * two parameters were passed to this function, the values parsed
 * will be returned as an array. Otherwise, if optional parameters are passed,
 * the function will return the number of assigned values. The optional
 * parameters must be passed by reference.
 */
function sscanf(string $string, string $format, #[TypeContract(exists: "int|null", notExists: "array|null")] mixed &...$vars): array|int|null {}

/**
 * Parses input from a file according to a format
 * @link https://php.net/manual/en/function.fscanf.php
 * @param resource $stream &fs.file.pointer;
 * @param string $format <p>
 * The specified format as described in the
 * sprintf documentation.
 * </p>
 * @param mixed &...$vars [optional]
 * @return array|int|false|null If only two parameters were passed to this function, the values parsed will be
 * returned as an array. Otherwise, if optional parameters are passed, the
 * function will return the number of assigned values. The optional
 * parameters must be passed by reference.
 */
function fscanf($stream, string $format, #[TypeContract(exists: "int|false|null", notExists: "array|false|null")] mixed &...$vars): array|int|false|null {}

/**
 * Parse a URL and return its components
 * @link https://php.net/manual/en/function.parse-url.php
 * @param string $url <p>
 * The URL to parse. Invalid characters are replaced by
 * _.
 * </p>
 * @param int $component [optional] <p>
 * Specify one of PHP_URL_SCHEME,
 * PHP_URL_HOST, PHP_URL_PORT,
 * PHP_URL_USER, PHP_URL_PASS,
 * PHP_URL_PATH, PHP_URL_QUERY
 * or PHP_URL_FRAGMENT to retrieve just a specific
 * URL component as a string.
 * </p>
 * @return array|string|int|null|false On seriously malformed URLs, parse_url() may return FALSE.
 * If the component parameter is omitted, an associative array is returned.
 * At least one element will be present within the array. Potential keys within this array are:
 * scheme - e.g. http
 * host
 * port
 * user
 * pass
 * path
 * query - after the question mark ?
 * fragment - after the hashmark #
 * </p>
 * <p>
 * If the component parameter is specified a
 * string is returned instead of an array.
 */
#[ArrayShape(["scheme" => "string", "host" => "string", "port" => "int", "user" => "string", "pass" => "string", "query" => "string", "path" => "string", "fragment" => "string"])]
#[Pure]
function parse_url(string $url, int $component = -1): array|string|int|false|null {}

/**
 * URL-encodes string
 * @link https://php.net/manual/en/function.urlencode.php
 * @param string $string <p>
 * The string to be encoded.
 * </p>
 * @return string a string in which all non-alphanumeric characters except
 * -_. have been replaced with a percent
 * (%) sign followed by two hex digits and spaces encoded
 * as plus (+) signs. It is encoded the same way that the
 * posted data from a WWW form is encoded, that is the same way as in
 * application/x-www-form-urlencoded media type. This
 * differs from the RFC 3986 encoding (see
 * rawurlencode) in that for historical reasons, spaces
 * are encoded as plus (+) signs.
 */
#[Pure]
function urlencode(string $string): string {}

/**
 * Decodes URL-encoded string
 * @link https://php.net/manual/en/function.urldecode.php
 * @param string $string <p>
 * The string to be decoded.
 * </p>
 * @return string the decoded string.
 */
#[Pure]
function urldecode(string $string): string {}

/**
 * URL-encode according to RFC 3986
 * @link https://php.net/manual/en/function.rawurlencode.php
 * @param string $string <p>
 * The URL to be encoded.
 * </p>
 * @return string a string in which all non-alphanumeric characters except
 * -_. have been replaced with a percent
 * (%) sign followed by two hex digits. This is the
 * encoding described in RFC 1738 for
 * protecting literal characters from being interpreted as special URL
 * delimiters, and for protecting URLs from being mangled by transmission
 * media with character conversions (like some email systems).
 */
#[Pure]
function rawurlencode(string $string): string {}

/**
 * Decode URL-encoded strings
 * @link https://php.net/manual/en/function.rawurldecode.php
 * @param string $string <p>
 * The URL to be decoded.
 * </p>
 * @return string the decoded URL, as a string.
 */
#[Pure]
function rawurldecode(string $string): string {}

/**
 * Generate URL-encoded query string
 * @link https://php.net/manual/en/function.http-build-query.php
 * @param object|array $data <p>
 * May be an array or object containing properties.
 * </p>
 * <p>
 * If query_data is an array, it may be a simple one-dimensional structure,
 * or an array of arrays (which in turn may contain other arrays).
 * </p>
 * <p>
 * If query_data is an object, then only public properties will be incorporated into the result.
 * </p>
 * @param string $numeric_prefix [optional] <p>
 * If numeric indices are used in the base array and this parameter is
 * provided, it will be prepended to the numeric index for elements in
 * the base array only.
 * </p>
 * <p>
 * This is meant to allow for legal variable names when the data is
 * decoded by PHP or another CGI application later on.
 * </p>
 * @param string|null $arg_separator <p>
 * arg_separator.output
 * is used to separate arguments, unless this parameter is specified,
 * and is then used.
 * </p>
 * @param int $encoding_type By default, PHP_QUERY_RFC1738.
 *  <p>If enc_type is PHP_QUERY_RFC1738, then encoding is performed per » RFC 1738 and the application/x-www-form-urlencoded media type,
 *  which implies that spaces are encoded as plus (+) signs.
 *  <p>If enc_type is PHP_QUERY_RFC3986, then encoding is performed according to » RFC 3986, and spaces will be percent encoded (%20).
 * @return string a URL-encoded string.
 */
#[Pure]
function http_build_query(object|array $data, string $numeric_prefix = "", ?string $arg_separator = null, int $encoding_type = PHP_QUERY_RFC1738): string {}

/**
 * Returns the target of a symbolic link
 * @link https://php.net/manual/en/function.readlink.php
 * @param string $path <p>
 * The symbolic link path.
 * </p>
 * @return string|false the contents of the symbolic link path or false on error.
 */
#[Pure(true)]
function readlink(string $path): string|false {}

/**
 * Gets information about a link
 * @link https://php.net/manual/en/function.linkinfo.php
 * @param string $path <p>
 * Path to the link.
 * </p>
 * @return int|false linkinfo returns the st_dev field
 * of the Unix C stat structure returned by the lstat
 * system call. Returns 0 or false in case of error.
 */
#[Pure(true)]
function linkinfo(string $path): int|false {}

/**
 * Creates a symbolic link
 * @link https://php.net/manual/en/function.symlink.php
 * @param string $target <p>
 * Target of the link.
 * </p>
 * @param string $link <p>
 * The link name.
 * </p>
 * @return bool true on success or false on failure.
 */
function symlink(string $target, string $link): bool {}

/**
 * Create a hard link
 * @link https://php.net/manual/en/function.link.php
 * @param string $target Target of the link.
 * @param string $link The link name.
 * @return bool true on success or false on failure.
 */
function link(string $target, string $link): bool {}

/**
 * Deletes a file
 * @link https://php.net/manual/en/function.unlink.php
 * @param string $filename <p>
 * Path to the file.
 * </p>
 * @param resource $context [optional]
 * @return bool true on success or false on failure.
 */
function unlink(string $filename, $context): bool {}

/**
 * Execute an external program
 * @link https://php.net/manual/en/function.exec.php
 * @param string $command <p>
 * The command that will be executed.
 * </p>
 * @param array &$output [optional] <p>
 * If the output argument is present, then the
 * specified array will be filled with every line of output from the
 * command. Trailing whitespace, such as \n, is not
 * included in this array. Note that if the array already contains some
 * elements, exec will append to the end of the array.
 * If you do not want the function to append elements, call
 * unset on the array before passing it to
 * exec.
 * </p>
 * @param int &$result_code [optional] <p>
 * If the return_var argument is present
 * along with the output argument, then the
 * return status of the executed command will be written to this
 * variable.
 * </p>
 * @return string|false The last line from the result of the command. If you need to execute a
 * command and have all the data from the command passed directly back without
 * any interference, use the passthru function.
 * </p>
 * <p>
 * To get the output of the executed command, be sure to set and use the
 * output parameter.
 */
function exec(string $command, &$output, &$result_code): string|false {}

/**
 * Execute an external program and display the output
 * @link https://php.net/manual/en/function.system.php
 * @param string $command <p>
 * The command that will be executed.
 * </p>
 * @param int &$result_code [optional] <p>
 * If the return_var argument is present, then the
 * return status of the executed command will be written to this
 * variable.
 * </p>
 * @return string|false the last line of the command output on success, and false
 * on failure.
 */
function system(string $command, &$result_code): string|false {}

/**
 * Escape shell metacharacters
 * @link https://php.net/manual/en/function.escapeshellcmd.php
 * @param string $command <p>
 * The command that will be escaped.
 * </p>
 * @return string The escaped string.
 */
#[Pure]
function escapeshellcmd(string $command): string {}

/**
 * Escape a string to be used as a shell argument
 * @link https://php.net/manual/en/function.escapeshellarg.php
 * @param string $arg <p>
 * The argument that will be escaped.
 * </p>
 * @return string The escaped string.
 */
#[Pure]
function escapeshellarg(string $arg): string {}

/**
 * Execute an external program and display raw output
 * @link https://php.net/manual/en/function.passthru.php
 * @param string $command <p>
 * The command that will be executed.
 * </p>
 * @param int &$result_code [optional] <p>
 * If the return_var argument is present, the
 * return status of the Unix command will be placed here.
 * </p>
 * @return bool|null null on success or false on failure.
 */
#[LanguageLevelTypeAware(['8.2' => 'null|false'], default: 'null|bool')]
function passthru(string $command, &$result_code): ?bool {}

/**
 * Execute command via shell and return the complete output as a string
 * @link https://php.net/manual/en/function.shell-exec.php
 * @param string $command <p>
 * The command that will be executed.
 * </p>
 * @return string|false|null The output from the executed command or NULL if an error occurred or the command produces no output.
 */
function shell_exec(string $command): string|false|null {}

/**
 * Execute a command and open file pointers for input/output
 * @link https://php.net/manual/en/function.proc-open.php
 * @param array|string $command <p>
 * Execute a command and open file pointers for input/output
 * </p>
 * <p>
 * As of PHP 7.4.0, cmd may be passed as array of command parameters.
 * In this case the process will be opened directly
 * (without going through a shell) and PHP will take care of any
 * necessary argument escaping.
 * </p>
 * @param array $descriptor_spec <p>
 * An indexed array where the key represents the descriptor number and the
 * value represents how PHP will pass that descriptor to the child
 * process. 0 is stdin, 1 is stdout, while 2 is stderr.
 * </p>
 * <p>
 * Each element can be:
 * An array describing the pipe to pass to the process. The first
 * element is the descriptor type and the second element is an option for
 * the given type. Valid types are pipe (the second
 * element is either r to pass the read end of the pipe
 * to the process, or w to pass the write end) and
 * file (the second element is a filename).
 * A stream resource representing a real file descriptor (e.g. opened file,
 * a socket, STDIN).
 * </p>
 * <p>
 * The file descriptor numbers are not limited to 0, 1 and 2 - you may
 * specify any valid file descriptor number and it will be passed to the
 * child process. This allows your script to interoperate with other
 * scripts that run as "co-processes". In particular, this is useful for
 * passing passphrases to programs like PGP, GPG and openssl in a more
 * secure manner. It is also useful for reading status information
 * provided by those programs on auxiliary file descriptors.
 * </p>
 * @param array &$pipes <p>
 * Will be set to an indexed array of file pointers that correspond to
 * PHP's end of any pipes that are created.
 * </p>
 * @param string|null $cwd [optional] <p>
 * The initial working dir for the command. This must be an
 * absolute directory path, or null
 * if you want to use the default value (the working dir of the current
 * PHP process)
 * </p>
 * @param array|null $env_vars [optional] <p>
 * An array with the environment variables for the command that will be
 * run, or null to use the same environment as the current PHP process
 * </p>
 * @param array|null $options [optional] <p>
 * Allows you to specify additional options. Currently supported options
 * include:
 * suppress_errors (windows only): suppresses errors generated by this
 * function when it's set to TRUE
 * generated by this function when it's set to true
 * bypass_shell (windows only): bypass cmd.exe shell when set to TRUE
 * context: stream context used when opening files
 * (created with stream_context_create)
 * blocking_pipes: (windows only): force blocking pipes when set to TRUE
 * create_process_group (windows only): allow the child process to handle
 * CTRL events when set to TRUE
 * create_new_console (windows only): the new process has a new console,
 * instead of inheriting its parent's console
 * </p>
 * @return resource|false a resource representing the process, which should be freed using
 * proc_close when you are finished with it. On failure
 * returns false.
 */
function proc_open(array|string $command, array $descriptor_spec, &$pipes, ?string $cwd, ?array $env_vars, ?array $options) {}

/**
 * Close a process opened by {@see proc_open} and return the exit code of that process
 * @link https://php.net/manual/en/function.proc-close.php
 * @param resource $process <p>
 * The proc_open resource that will
 * be closed.
 * </p>
 * @return int the termination status of the process that was run.
 */
function proc_close($process): int {}

/**
 * Kills a process opened by proc_open
 * @link https://php.net/manual/en/function.proc-terminate.php
 * @param resource $process <p>
 * The proc_open resource that will
 * be closed.
 * </p>
 * @param int $signal [optional] <p>
 * This optional parameter is only useful on POSIX
 * operating systems; you may specify a signal to send to the process
 * using the kill(2) system call. The default is
 * SIGTERM.
 * </p>
 * @return bool the termination status of the process that was run.
 */
function proc_terminate($process, int $signal = 15): bool {}

/**
 * Get information about a process opened by {@see proc_open}
 * @link https://php.net/manual/en/function.proc-get-status.php
 * @param resource $process <p>
 * The proc_open resource that will
 * be evaluated.
 * </p>
 * @return array|false An array of collected information on success, and false
 * on failure. The returned array contains the following elements:
 * </p>
 * <p>
 * <tr valign="top"><td>element</td><td>type</td><td>description</td></tr>
 * <tr valign="top">
 * <td>command</td>
 * <td>string</td>
 * <td>
 * The command string that was passed to proc_open.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>pid</td>
 * <td>int</td>
 * <td>process id</td>
 * </tr>
 * <tr valign="top">
 * <td>running</td>
 * <td>bool</td>
 * <td>
 * true if the process is still running, false if it has
 * terminated.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>signaled</td>
 * <td>bool</td>
 * <td>
 * true if the child process has been terminated by
 * an uncaught signal. Always set to false on Windows.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>stopped</td>
 * <td>bool</td>
 * <td>
 * true if the child process has been stopped by a
 * signal. Always set to false on Windows.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>exitcode</td>
 * <td>int</td>
 * <td>
 * The exit code returned by the process (which is only
 * meaningful if running is false).
 * Only first call of this function return real value, next calls return
 * -1.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>termsig</td>
 * <td>int</td>
 * <td>
 * The number of the signal that caused the child process to terminate
 * its execution (only meaningful if signaled is true).
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>stopsig</td>
 * <td>int</td>
 * <td>
 * The number of the signal that caused the child process to stop its
 * execution (only meaningful if stopped is true).
 * </td>
 * </tr>
 */
#[ArrayShape(["command" => "string", "pid" => "int", "running" => "bool", "signaled" => "bool", "stopped" => "bool", "exitcode" => "int", "termsig" => "int", "stopsig" => "int"])]
#[LanguageLevelTypeAware(["8.0" => "array"], default: "array|false")]
function proc_get_status($process) {}

/**
 * Change the priority of the current process. <br/>
 * Since 7.2.0 supported on Windows platforms.
 * @link https://php.net/manual/en/function.proc-nice.php
 * @param int $priority <p>
 * The increment value of the priority change.
 * </p>
 * @return bool true on success or false on failure.
 * If an error occurs, like the user lacks permission to change the priority,
 * an error of level E_WARNING is also generated.
 */
function proc_nice(int $priority): bool {}

/**
 * Get port number associated with an Internet service and protocol
 * @link https://php.net/manual/en/function.getservbyname.php
 * @param string $service <p>
 * The Internet service name, as a string.
 * </p>
 * @param string $protocol <p>
 * protocol is either "tcp"
 * or "udp" (in lowercase).
 * </p>
 * @return int|false the port number, or false if service or
 * protocol is not found.
 */
#[Pure]
function getservbyname(string $service, string $protocol): int|false {}

/**
 * Get Internet service which corresponds to port and protocol
 * @link https://php.net/manual/en/function.getservbyport.php
 * @param int $port <p>
 * The port number.
 * </p>
 * @param string $protocol <p>
 * protocol is either "tcp"
 * or "udp" (in lowercase).
 * </p>
 * @return string|false the Internet service name as a string.
 */
#[Pure]
function getservbyport(int $port, string $protocol): string|false {}

/**
 * Get protocol number associated with protocol name
 * @link https://php.net/manual/en/function.getprotobyname.php
 * @param string $protocol <p>
 * The protocol name.
 * </p>
 * @return int|false the protocol number or -1 if the protocol is not found.
 */
#[Pure]
function getprotobyname(string $protocol): int|false {}

/**
 * Get protocol name associated with protocol number
 * @link https://php.net/manual/en/function.getprotobynumber.php
 * @param int $protocol <p>
 * The protocol number.
 * </p>
 * @return string|false the protocol name as a string.
 */
#[Pure]
function getprotobynumber(int $protocol): string|false {}

/**
 * Gets PHP script owner's UID
 * @link https://php.net/manual/en/function.getmyuid.php
 * @return int|false the user ID of the current script, or false on error.
 */
#[Pure]
function getmyuid(): int|false {}

/**
 * Get PHP script owner's GID
 * @link https://php.net/manual/en/function.getmygid.php
 * @return int|false the group ID of the current script, or false on error.
 */
#[Pure]
function getmygid(): int|false {}

/**
 * Gets PHP's process ID
 * @link https://php.net/manual/en/function.getmypid.php
 * @return int|false the current PHP process ID, or false on error.
 */
#[Pure]
function getmypid(): int|false {}

/**
 * Gets the inode of the current script
 * @link https://php.net/manual/en/function.getmyinode.php
 * @return int|false the current script's inode as an integer, or false on error.
 */
#[Pure]
function getmyinode(): int|false {}
