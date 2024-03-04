<?php

// Start of mbstring v.

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Deprecated;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;
use JetBrains\PhpStorm\Pure;

/**
 * Perform case folding on a string
 * @link https://php.net/manual/en/function.mb-convert-case.php
 * @param string $string <p>
 * The string being converted.
 * </p>
 * @param int $mode <p>
 * The mode of the conversion. It can be one of
 * MB_CASE_UPPER,
 * MB_CASE_LOWER, or
 * MB_CASE_TITLE.
 * </p>
 * @param string|null $encoding [optional]
 * @return string A case folded version of string converted in the
 * way specified by mode.
 */
#[Pure]
function mb_convert_case(string $string, int $mode, ?string $encoding): string {}

/**
 * Make a string uppercase
 * @link https://php.net/manual/en/function.mb-strtoupper.php
 * @param string $string <p>
 * The string being uppercased.
 * </p>
 * @param string|null $encoding [optional]
 * @return string str with all alphabetic characters converted to uppercase.
 */
#[Pure]
function mb_strtoupper(string $string, ?string $encoding): string {}

/**
 * Make a string lowercase
 * @link https://php.net/manual/en/function.mb-strtolower.php
 * @param string $string <p>
 * The string being lowercased.
 * </p>
 * @param string|null $encoding [optional]
 * @return string str with all alphabetic characters converted to lowercase.
 */
#[Pure]
function mb_strtolower(string $string, ?string $encoding): string {}

/**
 * Set/Get current language
 * @link https://php.net/manual/en/function.mb-language.php
 * @param string|null $language [optional] <p>
 * Used for encoding
 * e-mail messages. Valid languages are "Japanese",
 * "ja","English","en" and "uni"
 * (UTF-8). mb_send_mail uses this setting to
 * encode e-mail.
 * </p>
 * <p>
 * Language and its setting is ISO-2022-JP/Base64 for
 * Japanese, UTF-8/Base64 for uni, ISO-8859-1/quoted printable for
 * English.
 * </p>
 * @return bool|string If language is set and
 * language is valid, it returns
 * true. Otherwise, it returns false.
 * When language is omitted, it returns the language
 * name as a string. If no language is set previously, it then returns
 * false.
 */
function mb_language(?string $language): string|bool {}

/**
 * Set/Get internal character encoding
 * @link https://php.net/manual/en/function.mb-internal-encoding.php
 * @param string|null $encoding [optional] <p>
 * encoding is the character encoding name
 * used for the HTTP input character encoding conversion, HTTP output
 * character encoding conversion, and the default character encoding
 * for string functions defined by the mbstring module.
 * </p>
 * @return bool|string If encoding is set, then
 * true on success or false on failure.
 * If encoding is omitted, then
 * the current character encoding name is returned.
 */
function mb_internal_encoding(?string $encoding): string|bool {}

/**
 * Detect HTTP input character encoding
 * @link https://php.net/manual/en/function.mb-http-input.php
 * @param string|null $type [optional] <p>
 * Input string specifies the input type.
 * "G" for GET, "P" for POST, "C" for COOKIE, "S" for string, "L" for list, and
 * "I" for the whole list (will return array).
 * If type is omitted, it returns the last input type processed.
 * </p>
 * @return array|false|string The character encoding name, as per the type.
 * If mb_http_input does not process specified
 * HTTP input, it returns false.
 */
#[Pure]
function mb_http_input(?string $type): array|string|false {}

/**
 * Set/Get HTTP output character encoding
 * @link https://php.net/manual/en/function.mb-http-output.php
 * @param string|null $encoding [optional] <p>
 * If encoding is set,
 * mb_http_output sets the HTTP output character
 * encoding to encoding.
 * </p>
 * <p>
 * If encoding is omitted,
 * mb_http_output returns the current HTTP output
 * character encoding.
 * </p>
 * @return bool|string If encoding is omitted,
 * mb_http_output returns the current HTTP output
 * character encoding. Otherwise,
 * true on success or false on failure.
 */
function mb_http_output(?string $encoding): string|bool {}

/**
 * Set/Get character encoding detection order
 * @link https://php.net/manual/en/function.mb-detect-order.php
 * @param array|string|null $encoding [optional] <p>
 * encoding_list is an array or
 * comma separated list of character encoding. ("auto" is expanded to
 * "ASCII, JIS, UTF-8, EUC-JP, SJIS")
 * </p>
 * <p>
 * If encoding_list is omitted, it returns
 * the current character encoding detection order as array.
 * </p>
 * <p>
 * This setting affects mb_detect_encoding and
 * mb_send_mail.
 * </p>
 * <p>
 * mbstring currently implements the following
 * encoding detection filters. If there is an invalid byte sequence
 * for the following encodings, encoding detection will fail.
 * </p>
 * UTF-8, UTF-7,
 * ASCII,
 * EUC-JP,SJIS,
 * eucJP-win, SJIS-win,
 * JIS, ISO-2022-JP
 * <p>
 * For ISO-8859-*, mbstring
 * always detects as ISO-8859-*.
 * </p>
 * <p>
 * For UTF-16, UTF-32,
 * UCS2 and UCS4, encoding
 * detection will fail always.
 * </p>
 * <p>
 * Useless detect order example
 * </p>
 * @return bool|string[] When setting the encoding detection order,
 * true is returned on success or FALSE on failure.
 * When getting the encoding detection order, an ordered array
 * of the encodings is returned.
 */
#[LanguageLevelTypeAware(['8.2' => 'array|true'], default: 'array|bool')]
function mb_detect_order(array|string|null $encoding = null): array|bool {}

/**
 * Set/Get substitution character
 * @link https://php.net/manual/en/function.mb-substitute-character.php
 * @param string|int|null $substitute_character [optional] <p>
 * Specify the Unicode value as an integer,
 * or as one of the following strings:</p><ul>
 * <li>"none" : no output</li>
 * <li>"long": Output character code value (Example: U+3000, JIS+7E7E)</li>
 * <li>"entity": Output character entity (Example: Ȁ)</li>
 * </ul>
 * @return bool|int|string If substchar is set, it returns true for success,
 * otherwise returns false.
 * If substchar is not set, it returns the Unicode value,
 * or "none" or "long".
 */
function mb_substitute_character(string|int|null $substitute_character = null): string|int|bool {}

/**
 * Parse GET/POST/COOKIE data and set global variable
 * @link https://php.net/manual/en/function.mb-parse-str.php
 * @param string $string <p>
 * The URL encoded data.
 * </p>
 * @param array &$result [optional] <p>
 * An array containing decoded and character encoded converted values.
 * </p>
 * @return bool true on success or false on failure.
 */
#[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')]
function mb_parse_str(string $string, &$result): bool {}

/**
 * Parse GET/POST/COOKIE data and set global variable
 * @link https://php.net/manual/en/function.mb-parse-str.php
 * @param string $string <p>
 * The URL encoded data.
 * </p>
 * @param array &$result <p>
 * An array containing decoded and character encoded converted values.
 * </p>
 * @return bool true on success or false on failure.
 */
#[PhpStormStubsElementAvailable(from: '8.0')]
function mb_parse_str(string $string, &$result): bool {}

/**
 * Callback function converts character encoding in output buffer
 * @link https://php.net/manual/en/function.mb-output-handler.php
 * @param string $string <p>
 * The contents of the output buffer.
 * </p>
 * @param int $status <p>
 * The status of the output buffer.
 * </p>
 * @return string The converted string.
 */
#[Pure]
function mb_output_handler(string $string, int $status): string {}

/**
 * Get MIME charset string
 * @link https://php.net/manual/en/function.mb-preferred-mime-name.php
 * @param string $encoding <p>
 * The encoding being checked.
 * </p>
 * @return string|false The MIME charset string for character encoding
 * encoding.
 */
#[Pure]
function mb_preferred_mime_name(string $encoding): string|false {}

/**
 * Get string length
 * @link https://php.net/manual/en/function.mb-strlen.php
 * @param string $string <p>
 * The string being checked for length.
 * </p>
 * @param string|null $encoding [optional]
 * @return int|false the number of characters in
 * string str having character encoding
 * encoding. A multi-byte character is
 * counted as 1.
 */
#[Pure]
#[LanguageLevelTypeAware(['8.0' => 'int'], default: 'int|false')]
function mb_strlen(string $string, #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: 'string')] $encoding) {}

/**
 * Find position of first occurrence of string in a string
 * @link https://php.net/manual/en/function.mb-strpos.php
 * @param string $haystack <p>
 * The string being checked.
 * </p>
 * @param string $needle <p>
 * The position counted from the beginning of haystack.
 * </p>
 * @param int<0,max> $offset [optional] <p>
 * The search offset. If it is not specified, 0 is used.
 * </p>
 * @param string|null $encoding [optional]
 * @return int<0,max>|false the numeric position of
 * the first occurrence of needle in the
 * haystack string. If
 * needle is not found, it returns false.
 */
#[Pure]
function mb_strpos(string $haystack, string $needle, int $offset = 0, ?string $encoding): int|false {}

/**
 * Find position of last occurrence of a string in a string
 * @link https://php.net/manual/en/function.mb-strrpos.php
 * @param string $haystack <p>
 * The string being checked, for the last occurrence
 * of needle
 * </p>
 * @param string $needle <p>
 * The string to find in haystack.
 * </p>
 * @param int $offset [optional] May be specified to begin searching an arbitrary number of characters into
 * the string. Negative values will stop searching at an arbitrary point
 * prior to the end of the string.
 * @param string|null $encoding [optional]
 * @return int|false the numeric position of
 * the last occurrence of needle in the
 * haystack string. If
 * needle is not found, it returns false.
 */
#[Pure]
function mb_strrpos(string $haystack, string $needle, int $offset = 0, ?string $encoding): int|false {}

/**
 * Finds position of first occurrence of a string within another, case insensitive
 * @link https://php.net/manual/en/function.mb-stripos.php
 * @param string $haystack <p>
 * The string from which to get the position of the first occurrence
 * of needle
 * </p>
 * @param string $needle <p>
 * The string to find in haystack
 * </p>
 * @param int $offset [optional] <p>
 * The position in haystack
 * to start searching
 * </p>
 * @param string|null $encoding [optional] <p>
 * Character encoding name to use.
 * If it is omitted, internal character encoding is used.
 * </p>
 * @return int|false Return the numeric position of the first occurrence of
 * needle in the haystack
 * string, or false if needle is not found.
 */
#[Pure]
function mb_stripos(string $haystack, string $needle, int $offset = 0, ?string $encoding): int|false {}

/**
 * Finds position of last occurrence of a string within another, case insensitive
 * @link https://php.net/manual/en/function.mb-strripos.php
 * @param string $haystack <p>
 * The string from which to get the position of the last occurrence
 * of needle
 * </p>
 * @param string $needle <p>
 * The string to find in haystack
 * </p>
 * @param int $offset [optional] <p>
 * The position in haystack
 * to start searching
 * </p>
 * @param string|null $encoding [optional] <p>
 * Character encoding name to use.
 * If it is omitted, internal character encoding is used.
 * </p>
 * @return int|false Return the numeric position of
 * the last occurrence of needle in the
 * haystack string, or false
 * if needle is not found.
 */
#[Pure]
function mb_strripos(string $haystack, string $needle, int $offset = 0, ?string $encoding): int|false {}

/**
 * Finds first occurrence of a string within another
 * @link https://php.net/manual/en/function.mb-strstr.php
 * @param string $haystack <p>
 * The string from which to get the first occurrence
 * of needle
 * </p>
 * @param string $needle <p>
 * The string to find in haystack
 * </p>
 * @param bool $before_needle [optional] <p>
 * Determines which portion of haystack
 * this function returns.
 * If set to true, it returns all of haystack
 * from the beginning to the first occurrence of needle.
 * If set to false, it returns all of haystack
 * from the first occurrence of needle to the end,
 * </p>
 * @param string|null $encoding [optional] <p>
 * Character encoding name to use.
 * If it is omitted, internal character encoding is used.
 * </p>
 * @return string|false the portion of haystack,
 * or false if needle is not found.
 */
#[Pure]
function mb_strstr(string $haystack, string $needle, bool $before_needle = false, ?string $encoding): string|false {}

/**
 * Finds the last occurrence of a character in a string within another
 * @link https://php.net/manual/en/function.mb-strrchr.php
 * @param string $haystack <p>
 * The string from which to get the last occurrence
 * of needle
 * </p>
 * @param string $needle <p>
 * The string to find in haystack
 * </p>
 * @param bool $before_needle [optional] <p>
 * Determines which portion of haystack
 * this function returns.
 * If set to true, it returns all of haystack
 * from the beginning to the last occurrence of needle.
 * If set to false, it returns all of haystack
 * from the last occurrence of needle to the end,
 * </p>
 * @param string|null $encoding [optional] <p>
 * Character encoding name to use.
 * If it is omitted, internal character encoding is used.
 * </p>
 * @return string|false the portion of haystack.
 * or false if needle is not found.
 */
#[Pure]
function mb_strrchr(string $haystack, string $needle, bool $before_needle = false, ?string $encoding): string|false {}

/**
 * Finds first occurrence of a string within another, case insensitive
 * @link https://php.net/manual/en/function.mb-stristr.php
 * @param string $haystack <p>
 * The string from which to get the first occurrence
 * of needle
 * </p>
 * @param string $needle <p>
 * The string to find in haystack
 * </p>
 * @param bool $before_needle [optional] <p>
 * Determines which portion of haystack
 * this function returns.
 * If set to true, it returns all of haystack
 * from the beginning to the first occurrence of needle.
 * If set to false, it returns all of haystack
 * from the first occurrence of needle to the end,
 * </p>
 * @param string|null $encoding [optional] <p>
 * Character encoding name to use.
 * If it is omitted, internal character encoding is used.
 * </p>
 * @return string|false the portion of haystack,
 * or false if needle is not found.
 */
#[Pure]
function mb_stristr(string $haystack, string $needle, bool $before_needle = false, ?string $encoding): string|false {}

/**
 * Finds the last occurrence of a character in a string within another, case insensitive
 * @link https://php.net/manual/en/function.mb-strrichr.php
 * @param string $haystack <p>
 * The string from which to get the last occurrence
 * of needle
 * </p>
 * @param string $needle <p>
 * The string to find in haystack
 * </p>
 * @param bool $before_needle [optional] <p>
 * Determines which portion of haystack
 * this function returns.
 * If set to true, it returns all of haystack
 * from the beginning to the last occurrence of needle.
 * If set to false, it returns all of haystack
 * from the last occurrence of needle to the end,
 * </p>
 * @param string|null $encoding [optional] <p>
 * Character encoding name to use.
 * If it is omitted, internal character encoding is used.
 * </p>
 * @return string|false the portion of haystack.
 * or false if needle is not found.
 */
#[Pure]
function mb_strrichr(string $haystack, string $needle, bool $before_needle = false, ?string $encoding): string|false {}

/**
 * Count the number of substring occurrences
 * @link https://php.net/manual/en/function.mb-substr-count.php
 * @param string $haystack <p>
 * The string being checked.
 * </p>
 * @param string $needle <p>
 * The string being found.
 * </p>
 * @param string|null $encoding [optional]
 * @return int The number of times the
 * needle substring occurs in the
 * haystack string.
 */
#[Pure]
function mb_substr_count(string $haystack, string $needle, ?string $encoding): int {}

/**
 * Get part of string
 * @link https://php.net/manual/en/function.mb-substr.php
 * @param string $string <p>
 * The string being checked.
 * </p>
 * @param int $start <p>
 * The first position used in str.
 * </p>
 * @param int|null $length [optional] <p>
 * The maximum length of the returned string.
 * </p>
 * @param string|null $encoding [optional]
 * @return string mb_substr returns the portion of
 * str specified by the
 * start and
 * length parameters.
 */
#[Pure]
function mb_substr(string $string, int $start, ?int $length, ?string $encoding): string {}

/**
 * Get part of string
 * @link https://php.net/manual/en/function.mb-strcut.php
 * @param string $string <p>
 * The string being cut.
 * </p>
 * @param int $start <p>
 * The position that begins the cut.
 * </p>
 * @param int|null $length [optional] <p>
 * The string being decoded.
 * </p>
 * @param string|null $encoding [optional]
 * @return string mb_strcut returns the portion of
 * str specified by the
 * start and
 * length parameters.
 */
#[Pure]
function mb_strcut(string $string, int $start, ?int $length, ?string $encoding): string {}

/**
 * Return width of string
 * @link https://php.net/manual/en/function.mb-strwidth.php
 * @param string $string <p>
 * The string being decoded.
 * </p>
 * @param string|null $encoding [optional]
 * @return int The width of string str.
 */
#[Pure]
function mb_strwidth(string $string, ?string $encoding): int {}

/**
 * Get truncated string with specified width
 * @link https://php.net/manual/en/function.mb-strimwidth.php
 * @param string $string <p>
 * The string being decoded.
 * </p>
 * @param int $start <p>
 * The start position offset. Number of
 * characters from the beginning of string. (First character is 0)
 * </p>
 * @param int $width <p>
 * The width of the desired trim.
 * </p>
 * @param string $trim_marker <p>
 * A string that is added to the end of string
 * when string is truncated.
 * </p>
 * @param string|null $encoding [optional]
 * @return string The truncated string. If trimmarker is set,
 * trimmarker is appended to the return value.
 */
#[Pure]
function mb_strimwidth(string $string, int $start, int $width, string $trim_marker = '', ?string $encoding): string {}

/**
 * Convert character encoding
 * @link https://php.net/manual/en/function.mb-convert-encoding.php
 * @param string|array $string <p>
 * The string being encoded.
 * </p>
 * @param string $to_encoding <p>
 * The type of encoding that str is being converted to.
 * </p>
 * @param string|string[]|null $from_encoding [optional] <p>
 * Is specified by character code names before conversion. It is either
 * an array, or a comma separated enumerated list.
 * If from_encoding is not specified, the internal
 * encoding will be used.
 * </p>
 * <p>
 * "auto" may be used, which expands to
 * "ASCII,JIS,UTF-8,EUC-JP,SJIS".
 * </p>
 * @return array|string|false The encoded string.
 */
#[Pure]
function mb_convert_encoding(array|string $string, string $to_encoding, array|string|null $from_encoding = null): array|string|false {}

/**
 * Detect character encoding
 * @link https://php.net/manual/en/function.mb-detect-encoding.php
 * @param string $string <p>
 * The string being detected.
 * </p>
 * @param string|string[]|null $encodings [optional] <p>
 * encoding_list is list of character
 * encoding. Encoding order may be specified by array or comma
 * separated list string.
 * </p>
 * <p>
 * If encoding_list is omitted,
 * detect_order is used.
 * </p>
 * @param bool $strict [optional] <p>
 * strict specifies whether to use
 * the strict encoding detection or not.
 * Default is false.
 * </p>
 * @return string|false The detected character encoding or false if the encoding cannot be
 * detected from the given string.
 */
#[Pure]
function mb_detect_encoding(string $string, array|string|null $encodings = null, bool $strict = false): string|false {}

/**
 * Returns an array of all supported encodings
 * @link https://php.net/manual/en/function.mb-list-encodings.php
 * @return string[] a numerically indexed array.
 */
#[Pure]
function mb_list_encodings(): array {}

/**
 * Get aliases of a known encoding type
 * @param string $encoding The encoding type being checked, for aliases.
 * @return string[]|false a numerically indexed array of encoding aliases on success, or FALSE on failure
 * @link https://php.net/manual/en/function.mb-encoding-aliases.php
 */
#[Pure]
#[LanguageLevelTypeAware(["8.0" => "array"], default: "array|false")]
function mb_encoding_aliases(string $encoding) {}

/**
 * Convert "kana" one from another ("zen-kaku", "han-kaku" and more)
 * @link https://php.net/manual/en/function.mb-convert-kana.php
 * @param string $string <p>
 * The string being converted.
 * </p>
 * @param string $mode [optional] <p>
 * The conversion option.
 * </p>
 * <p>
 * Specify with a combination of following options.
 * <table>
 * Applicable Conversion Options
 * <tr valign="top">
 * <td>Option</td>
 * <td>Meaning</td>
 * </tr>
 * <tr valign="top">
 * <td>r</td>
 * <td>
 * Convert "zen-kaku" alphabets to "han-kaku"
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>R</td>
 * <td>
 * Convert "han-kaku" alphabets to "zen-kaku"
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>n</td>
 * <td>
 * Convert "zen-kaku" numbers to "han-kaku"
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>N</td>
 * <td>
 * Convert "han-kaku" numbers to "zen-kaku"
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>a</td>
 * <td>
 * Convert "zen-kaku" alphabets and numbers to "han-kaku"
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>A</td>
 * <td>
 * Convert "han-kaku" alphabets and numbers to "zen-kaku"
 * (Characters included in "a", "A" options are
 * U+0021 - U+007E excluding U+0022, U+0027, U+005C, U+007E)
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>s</td>
 * <td>
 * Convert "zen-kaku" space to "han-kaku" (U+3000 -> U+0020)
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>S</td>
 * <td>
 * Convert "han-kaku" space to "zen-kaku" (U+0020 -> U+3000)
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>k</td>
 * <td>
 * Convert "zen-kaku kata-kana" to "han-kaku kata-kana"
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>K</td>
 * <td>
 * Convert "han-kaku kata-kana" to "zen-kaku kata-kana"
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>h</td>
 * <td>
 * Convert "zen-kaku hira-gana" to "han-kaku kata-kana"
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>H</td>
 * <td>
 * Convert "han-kaku kata-kana" to "zen-kaku hira-gana"
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>c</td>
 * <td>
 * Convert "zen-kaku kata-kana" to "zen-kaku hira-gana"
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>C</td>
 * <td>
 * Convert "zen-kaku hira-gana" to "zen-kaku kata-kana"
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>V</td>
 * <td>
 * Collapse voiced sound notation and convert them into a character. Use with "K","H"
 * </td>
 * </tr>
 * </table>
 * </p>
 * @param string|null $encoding [optional]
 * @return string The converted string.
 */
#[Pure]
function mb_convert_kana(string $string, string $mode = 'KV', ?string $encoding): string {}

/**
 * Encode string for MIME header
 * @link https://php.net/manual/en/function.mb-encode-mimeheader.php
 * @param string $string <p>
 * The string being encoded.
 * </p>
 * @param string|null $charset [optional] <p>
 * charset specifies the name of the character set
 * in which str is represented in. The default value
 * is determined by the current NLS setting (mbstring.language).
 * mb_internal_encoding should be set to same encoding.
 * </p>
 * @param string|null $transfer_encoding [optional] <p>
 * transfer_encoding specifies the scheme of MIME
 * encoding. It should be either "B" (Base64) or
 * "Q" (Quoted-Printable). Falls back to
 * "B" if not given.
 * </p>
 * @param string $newline [optional] <p>
 * linefeed specifies the EOL (end-of-line) marker
 * with which mb_encode_mimeheader performs
 * line-folding (a RFC term,
 * the act of breaking a line longer than a certain length into multiple
 * lines. The length is currently hard-coded to 74 characters).
 * Falls back to "\r\n" (CRLF) if not given.
 * </p>
 * @param int $indent <p>
 * Indentation of the first line (number of characters in the header
 * before str).
 * </p>
 * @return string A converted version of the string represented in ASCII.
 */
#[Pure]
function mb_encode_mimeheader(string $string, ?string $charset, ?string $transfer_encoding, string $newline = "\n", int $indent = 0): string {}

/**
 * Decode string in MIME header field
 * @link https://php.net/manual/en/function.mb-decode-mimeheader.php
 * @param string $string <p>
 * The string being decoded.
 * </p>
 * @return string The decoded string in internal character encoding.
 */
#[Pure]
function mb_decode_mimeheader(string $string): string {}

/**
 * Convert character code in variable(s)
 * @link https://php.net/manual/en/function.mb-convert-variables.php
 * @param string $to_encoding <p>
 * The encoding that the string is being converted to.
 * </p>
 * @param string|string[] $from_encoding <p>
 * from_encoding is specified as an array
 * or comma separated string, it tries to detect encoding from
 * from-coding. When from_encoding
 * is omitted, detect_order is used.
 * </p>
 * @param string|array|object &$var var is the reference to the variable being converted.
 * @param string|array|object &...$vars <p>
 * vars is the other references to the
 * variables being converted. String, Array and Object are accepted.
 * mb_convert_variables assumes all parameters
 * have the same encoding.
 * </p>
 * @return string|false The character encoding before conversion for success,
 * or false for failure.
 */
function mb_convert_variables(
    string $to_encoding,
    array|string $from_encoding,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] &$vars,
    #[PhpStormStubsElementAvailable(from: '8.0')] mixed &$var,
    mixed &...$vars
): string|false {}

/**
 * Encode character to HTML numeric string reference
 * @link https://php.net/manual/en/function.mb-encode-numericentity.php
 * @param string $string <p>
 * The string being encoded.
 * </p>
 * @param int[] $map <p>
 * convmap is array specifies code area to
 * convert.
 * </p>
 * @param null|string $encoding
 * @param bool $hex [optional]
 * @return string The converted string.
 */
#[Pure]
function mb_encode_numericentity(string $string, array $map, ?string $encoding = null, bool $hex = false): string {}

/**
 * Decode HTML numeric string reference to character
 * @link https://php.net/manual/en/function.mb-decode-numericentity.php
 * @param string $string <p>
 * The string being decoded.
 * </p>
 * @param int[] $map <p>
 * convmap is an array that specifies
 * the code area to convert.
 * </p>
 * @param null|string $encoding
 * @param bool $is_hex [optional] <p>
 * this parameter is not used.
 * </p>
 * @return string|false|null The converted string.
 */
#[Pure]
#[LanguageLevelTypeAware(['8.0' => 'string'], default: 'string|false|null')]
function mb_decode_numericentity(string $string, array $map, ?string $encoding = null, #[PhpStormStubsElementAvailable(from: '7.2', to: '7.4')] $is_hex = false) {}

/**
 * Send encoded mail
 * @link https://php.net/manual/en/function.mb-send-mail.php
 * @param string $to <p>
 * The mail addresses being sent to. Multiple
 * recipients may be specified by putting a comma between each
 * address in to.
 * This parameter is not automatically encoded.
 * </p>
 * @param string $subject <p>
 * The subject of the mail.
 * </p>
 * @param string $message <p>
 * The message of the mail.
 * </p>
 * @param string|array $additional_headers <p>
 * String or array to be inserted at the end of the email header. <br/>
 * Since 7.2.0 accepts an array. Its keys are the header names and its values are the respective header values.<br/>
 * This is typically used to add extra
 * headers. Multiple extra headers are separated with a
 * newline ("\n").
 * </p>
 * @param string|null $additional_params [optional] <p>
 * additional_parameter is a MTA command line
 * parameter. It is useful when setting the correct Return-Path
 * header when using sendmail.
 * </p>
 * @return bool true on success or false on failure.
 */
function mb_send_mail(string $to, string $subject, string $message, array|string $additional_headers = [], ?string $additional_params): bool {}

/**
 * Get internal settings of mbstring
 * @link https://php.net/manual/en/function.mb-get-info.php
 * @param string $type [optional] <p>
 * If type isn't specified or is specified to
 * "all", an array having the elements "internal_encoding",
 * "http_output", "http_input", "func_overload", "mail_charset",
 * "mail_header_encoding", "mail_body_encoding" will be returned.
 * </p>
 * <p>
 * If type is specified as "http_output",
 * "http_input", "internal_encoding", "func_overload",
 * the specified setting parameter will be returned.
 * </p>
 * @return array|string|int|false An array of type information if type
 * is not specified, otherwise a specific type.
 */
#[Pure]
#[ArrayShape([
    'internal_encoding' => 'string',
    'http_input' => 'string',
    'http_output' => 'string',
    'http_output_conv_mimetypes' => 'string',
    'mail_charset' => 'string',
    'mail_header_encoding' => 'string',
    'mail_body_encoding' => 'string',
    'illegal_chars' => 'string',
    'encoding_translation' => 'string',
    'language' => 'string',
    'detect_order' => 'string',
    'substitute_character' => 'string',
    'strict_detection' => 'string',
])]
function mb_get_info(string $type = 'all'): array|string|int|false {}

/**
 * Check if the string is valid for the specified encoding
 * @link https://php.net/manual/en/function.mb-check-encoding.php
 * @param string|string[]|null $value [optional] <p>
 * The byte stream to check. If it is omitted, this function checks
 * all the input from the beginning of the request.
 * </p>
 * @param string|null $encoding [optional] <p>
 * The expected encoding.
 * </p>
 * @return bool true on success or false on failure.
 * @since 5.1.3
 */
#[Pure]
function mb_check_encoding(array|string|null $value = null, ?string $encoding): bool {}

/**
 * Returns current encoding for multibyte regex as string
 * @link https://php.net/manual/en/function.mb-regex-encoding.php
 * @param string|null $encoding [optional]
 * @return bool|string If encoding is set, then Returns TRUE on success
 * or FALSE on failure. In this case, the internal character encoding
 * is NOT changed. If encoding is omitted, then the current character
 * encoding name for a multibyte regex is returned.
 */
function mb_regex_encoding(?string $encoding): string|bool {}

/**
 * Set/Get the default options for mbregex functions
 * @link https://php.net/manual/en/function.mb-regex-set-options.php
 * @param string|null $options [optional] <p>
 * The options to set.
 * </p>
 * @return string The previous options. If options is omitted,
 * it returns the string that describes the current options.
 */
function mb_regex_set_options(?string $options): string {}

/**
 * Regular expression match with multibyte support
 * @link https://php.net/manual/en/function.mb-ereg.php
 * @param string $pattern <p>
 * The search pattern.
 * </p>
 * @param string $string <p>
 * The search string.
 * </p>
 * @param string[] &$matches [optional] <p>
 * Contains a substring of the matched string.
 * </p>
 * @return bool
 */
function mb_ereg(string $pattern, string $string, &$matches): bool {}

/**
 * Regular expression match ignoring case with multibyte support
 * @link https://php.net/manual/en/function.mb-eregi.php
 * @param string $pattern <p>
 * The regular expression pattern.
 * </p>
 * @param string $string <p>
 * The string being searched.
 * </p>
 * @param string[] &$matches [optional] <p>
 * Contains a substring of the matched string.
 * </p>
 * @return bool|int
 */
#[LanguageLevelTypeAware(["8.0" => "bool"], default: "false|int")]
function mb_eregi(string $pattern, string $string, &$matches): bool {}

/**
 * Replace regular expression with multibyte support
 * @link https://php.net/manual/en/function.mb-ereg-replace.php
 * @param string $pattern <p>
 * The regular expression pattern.
 * </p>
 * <p>
 * Multibyte characters may be used in pattern.
 * </p>
 * @param string $replacement <p>
 * The replacement text.
 * </p>
 * @param string $string <p>
 * The string being checked.
 * </p>
 * @param string|null $options Matching condition can be set by option
 * parameter. If i is specified for this
 * parameter, the case will be ignored. If x is
 * specified, white space will be ignored. If m
 * is specified, match will be executed in multiline mode and line
 * break will be included in '.'. If p is
 * specified, match will be executed in POSIX mode, line break
 * will be considered as normal character. If e
 * is specified, replacement string will be
 * evaluated as PHP expression.
 * <p>PHP 7.1: The <i>e</i> modifier has been deprecated.</p>
 * @return string|false|null The resultant string on success, or false on error.
 */
#[Pure]
function mb_ereg_replace(string $pattern, string $replacement, string $string, ?string $options = null): string|false|null {}

/**
 * Perform a regular expresssion seach and replace with multibyte support using a callback
 * @link https://secure.php.net/manual/en/function.mb-ereg-replace-callback.php
 * @param string $pattern <p>
 * The regular expression pattern.
 * </p>
 * <p>
 * Multibyte characters may be used in <b>pattern</b>.
 * </p>
 * @param callable $callback <p>
 * A callback that will be called and passed an array of matched elements
 * in the  <b>subject</b> string. The callback should
 * return the replacement string.
 * </p>
 * <p>
 * You'll often need the <b>callback</b> function
 * for a <b>mb_ereg_replace_callback()</b> in just one place.
 * In this case you can use an anonymous function to
 * declare the callback within the call to
 * <b>mb_ereg_replace_callback()</b>. By doing it this way
 * you have all information for the call in one place and do not
 * clutter the function namespace with a callback function's name
 * not used anywhere else.
 * </p>
 * @param string $string <p>
 * The string being checked.
 * </p>
 * @param string $options <p>
 * Matching condition can be set by <em><b>option</b></em>
 * parameter. If <em>i</em> is specified for this
 * parameter, the case will be ignored. If <em>x</em> is
 * specified, white space will be ignored. If <em>m</em>
 * is specified, match will be executed in multiline mode and line
 * break will be included in '.'. If <em>p</em> is
 * specified, match will be executed in POSIX mode, line break
 * will be considered as normal character. Note that <em>e</em>
 * cannot be used for <b>mb_ereg_replace_callback()</b>.
 * </p>
 * @return string|false|null <p>
 * The resultant string on success, or <b>FALSE</b> on error.
 * </p>
 * @since 5.4.1
 */
function mb_ereg_replace_callback(string $pattern, callable $callback, string $string, ?string $options = null): string|false|null {}

/**
 * Replace regular expression with multibyte support ignoring case
 * @link https://php.net/manual/en/function.mb-eregi-replace.php
 * @param string $pattern <p>
 * The regular expression pattern. Multibyte characters may be used. The case will be ignored.
 * </p>
 * @param string $replacement <p>
 * The replacement text.
 * </p>
 * @param string $string <p>
 * The searched string.
 * </p>
 * @param string|null $options option has the same meaning as in
 * mb_ereg_replace.
 * <p>PHP 7.1: The <i>e</i> modifier has been deprecated.</p>
 * @return string|false|null The resultant string or false on error.
 */
#[Pure]
function mb_eregi_replace(
    string $pattern,
    string $replacement,
    string $string,
    #[PhpStormStubsElementAvailable(from: '7.0')] ?string $options = null
): string|false|null {}

/**
 * Split multibyte string using regular expression
 * @link https://php.net/manual/en/function.mb-split.php
 * @param string $pattern <p>
 * The regular expression pattern.
 * </p>
 * @param string $string <p>
 * The string being split.
 * </p>
 * @param int $limit [optional] If optional parameter limit is specified,
 * it will be split in limit elements as
 * maximum.
 * @return string[]|false The result as an array.
 */
#[Pure]
function mb_split(string $pattern, string $string, int $limit = -1): array|false {}

/**
 * Regular expression match for multibyte string
 * @link https://php.net/manual/en/function.mb-ereg-match.php
 * @param string $pattern <p>
 * The regular expression pattern.
 * </p>
 * @param string $string <p>
 * The string being evaluated.
 * </p>
 * @param string|null $options [optional] <p>
 * </p>
 * @return bool
 */
#[Pure]
function mb_ereg_match(string $pattern, string $string, ?string $options): bool {}

/**
 * Multibyte regular expression match for predefined multibyte string
 * @link https://php.net/manual/en/function.mb-ereg-search.php
 * @param string|null $pattern [optional] <p>
 * The search pattern.
 * </p>
 * @param string|null $options [optional] <p>
 * The search option.
 * </p>
 * @return bool
 */
#[Pure]
function mb_ereg_search(?string $pattern, ?string $options): bool {}

/**
 * Returns position and length of a matched part of the multibyte regular expression for a predefined multibyte string
 * @link https://php.net/manual/en/function.mb-ereg-search-pos.php
 * @param string|null $pattern [optional] <p>
 * The search pattern.
 * </p>
 * @param string|null $options [optional] <p>
 * The search option.
 * </p>
 * @return int[]|false An array containing two elements. The first
 * element is the offset, in bytes, where the match begins relative
 * to the start of the search string, and the second element is the
 * length in bytes of the match. If an error occurs, FALSE is returned.
 */
#[Pure]
function mb_ereg_search_pos(?string $pattern, ?string $options): array|false {}

/**
 * Returns the matched part of a multibyte regular expression
 * @link https://php.net/manual/en/function.mb-ereg-search-regs.php
 * @param string|null $pattern [optional] <p>
 * The search pattern.
 * </p>
 * @param string|null $options [optional] <p>
 * The search option.
 * </p>
 * @return string[]|false mb_ereg_search_regs() executes the multibyte
 * regular expression match, and if there are some matched part, it
 * returns an array including substring of matched part as first element,
 * the first grouped part with brackets as second element, the second grouped
 * part as third element, and so on. It returns FALSE on error.
 */
#[Pure]
function mb_ereg_search_regs(?string $pattern, ?string $options): array|false {}

/**
 * Setup string and regular expression for a multibyte regular expression match
 * @link https://php.net/manual/en/function.mb-ereg-search-init.php
 * @param string $string <p>
 * The search string.
 * </p>
 * @param string|null $pattern [optional] <p>
 * The search pattern.
 * </p>
 * @param string|null $options [optional] <p>
 * The search option.
 * </p>
 * @return bool
 */
function mb_ereg_search_init(string $string, ?string $pattern, ?string $options): bool {}

/**
 * Retrieve the result from the last multibyte regular expression match
 * @link https://php.net/manual/en/function.mb-ereg-search-getregs.php
 * @return string[]|false An array including the sub-string of matched
 * part by last mb_ereg_search(), mb_ereg_search_pos(), mb_ereg_search_regs().
 * If there are some matches, the first element will have the matched
 * sub-string, the second element will have the first part grouped with
 * brackets, the third element will have the second part grouped with
 * brackets, and so on. It returns FALSE on error;
 */
#[Pure]
function mb_ereg_search_getregs(): array|false {}

/**
 * Returns start point for next regular expression match
 * @link https://php.net/manual/en/function.mb-ereg-search-getpos.php
 * @return int
 */
#[Pure]
#[Deprecated(since: '7.3')]
function mb_ereg_search_getpos(): int {}

/**
 * Set start point of next regular expression match
 * @link https://php.net/manual/en/function.mb-ereg-search-setpos.php
 * @param int $offset <p>
 * The position to set.
 * </p>
 * @return bool
 */
#[Pure]
function mb_ereg_search_setpos(int $offset): bool {}

/**
 * @param $encoding [optional]
 * @see mb_regex_encoding
 * @removed 8.0
 */
#[Deprecated(replacement: "mb_regex_encoding(%parametersList%)", since: "7.3")]
function mbregex_encoding($encoding) {}

/**
 * @param string $pattern
 * @param string $string
 * @param array &$registers [optional]
 * @see mb_ereg
 * @removed 8.0
 */
#[Deprecated(replacement: 'mb_ereg(%parametersList%)', since: '7.3')]
function mbereg(string $pattern, string $string, array &$registers) {}

/**
 * @param string $pattern
 * @param string $string
 * @param array &$registers [optional]
 * @see mb_eregi
 * @removed 8.0
 */
#[Deprecated(replacement: "mb_eregi(%parametersList%)", since: "7.3")]
function mberegi(string $pattern, string $string, array &$registers) {}

/**
 * @param $pattern
 * @param $replacement
 * @param $string
 * @param $option [optional]
 * @see mb_ereg_replace
 * @removed 8.0
 */
#[Deprecated(replacement: 'mb_ereg_replace(%parametersList%)', since: '7.3')]
function mbereg_replace($pattern, $replacement, $string, $option) {}

/**
 * @param $pattern
 * @param $replacement
 * @param $string
 * @param string $option
 * @return string
 * @see mb_eregi_replace
 * @removed 8.0
 */
#[Deprecated(replacement: "mb_eregi_replace(%parametersList%)", since: "7.3")]
function mberegi_replace(
    $pattern,
    $replacement,
    $string,
    #[PhpStormStubsElementAvailable(from: '7.0')] string $option = "msri"
): string {}

/**
 * @param $pattern
 * @param $string
 * @param $limit [optional]
 * @see mb_split
 * @removed 8.0
 */
#[Deprecated(replacement: 'mb_split(%parametersList%)', since: '7.3')]
function mbsplit($pattern, $string, $limit) {}

/**
 * @param $pattern
 * @param $string
 * @param $option [optional]
 * @see mb_ereg_match
 * @removed 8.0
 */
#[Deprecated(replacement: "mb_ereg_match(%parametersList%)", since: "7.3")]
function mbereg_match($pattern, $string, $option) {}

/**
 * @param $pattern [optional]
 * @param $option [optional]
 * @see mb_ereg_search
 * @removed 8.0
 */
#[Deprecated("use mb_ereg_search instead", replacement: "mb_ereg_search(%parametersList%)", since: "7.3")]
function mbereg_search($pattern, $option) {}

/**
 * @param $pattern [optional]
 * @param $option [optional]
 * @see mb_ereg_search_pos
 * @removed 8.0
 */
#[Deprecated(replacement: "mb_ereg_search_pos(%parametersList%)", since: "7.3")]
function mbereg_search_pos($pattern, $option) {}

/**
 * @param $pattern [optional]
 * @param $option [optional]
 * @see mb_ereg_search_regs
 * @removed 8.0
 */
#[Deprecated(replacement: 'mb_ereg_search_regs(%parametersList%)', since: '7.3')]
function mbereg_search_regs($pattern, $option) {}

/**
 * @param $string
 * @param $pattern [optional]
 * @param $option [optional]
 * @see mb_ereg_search_init
 * @removed 8.0
 */
#[Deprecated(replacement: "mb_ereg_search_init(%parametersList%)", since: "7.3")]
function mbereg_search_init($string, $pattern, $option) {}

/**
 * @see mb_ereg_search_getregs
 * @removed 8.0
 */
#[Deprecated(replacement: 'mb_ereg_search_getregs(%parametersList%)', since: '7.3')]
function mbereg_search_getregs() {}

/**
 * @see mb_ereg_search_getpos
 * @removed 8.0
 */
#[Deprecated(replacement: "mb_ereg_search_getpos()", since: "7.3")]
function mbereg_search_getpos() {}

/**
 * Get a specific character.
 * @link https://www.php.net/manual/en/function.mb-chr.php
 * @param int $codepoint
 * @param string|null $encoding [optional]
 * @return string|false specific character or FALSE on failure.
 * @since 7.2
 */
#[Pure]
function mb_chr(int $codepoint, ?string $encoding): string|false {}

/**
 * Get code point of character
 * @link https://www.php.net/manual/en/function.mb-ord.php
 * @param string $string
 * @param string|null $encoding [optional]
 * @return int|false code point of character or FALSE on failure.
 * @since 7.2
 */
#[Pure]
function mb_ord(string $string, ?string $encoding): int|false {}

/**
 * Scrub broken multibyte strings.
 * @link https://www.php.net/manual/en/function.mb-scrub.php
 * @param string $string
 * @param string|null $encoding [optional]
 * @return string|false
 * @since 7.2
 */
#[Pure]
#[LanguageLevelTypeAware(["8.0" => "string"], default: "string|false")]
function mb_scrub(string $string, ?string $encoding): false|string {}

/**
 * @param $position
 * @see mb_ereg_search_setpos
 */
#[Deprecated(replacement: "mb_ereg_search_setpos(%parametersList%)", since: "7.3")]
#[Pure]
function mbereg_search_setpos($position) {}

/**
 * Function performs string splitting to an array of defined size chunks.
 * @param string $string <p>
 * The string to split into characters or chunks.
 * </p>
 * @param int $length [optional] <p>
 * If specified, each element of the returned array will be composed of multiple characters instead of a single character.
 * </p>
 * @param string|null $encoding [optional] <p>
 * Character encoding name to use.
 * If it is omitted, internal character encoding is used.
 * </p>
 * @return string[]|false
 * @since 7.4
 */
#[Pure]
#[LanguageLevelTypeAware(["8.0" => "array"], default: "array|false")]
function mb_str_split(string $string, int $length = 1, ?string $encoding) {}

/**
 * @removed 8.0
 */
define('MB_OVERLOAD_MAIL', 1);
/**
 * @removed 8.0
 */
define('MB_OVERLOAD_STRING', 2);
/**
 * @removed 8.0
 */
define('MB_OVERLOAD_REGEX', 4);
define('MB_CASE_UPPER', 0);
define('MB_CASE_LOWER', 1);
define('MB_CASE_TITLE', 2);
/**
 * @since 7.3
 */
define('MB_CASE_FOLD', 3);
/**
 * @since 7.3
 */
define('MB_CASE_UPPER_SIMPLE', 4);
/**
 * @since 7.3
 */
define('MB_CASE_LOWER_SIMPLE', 5);
/**
 * @since 7.3
 */
define('MB_CASE_TITLE_SIMPLE', 6);
/**
 * @since 7.3
 */
define('MB_CASE_FOLD_SIMPLE', 7);

/**
 * @since 7.4
 */
define('MB_ONIGURUMA_VERSION', '6.9.8');

// End of mbstring v.
