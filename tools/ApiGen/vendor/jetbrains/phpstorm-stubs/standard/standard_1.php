<?php

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Deprecated;
use JetBrains\PhpStorm\ExpectedValues;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;
use JetBrains\PhpStorm\Pure;

/**
 * Make a string uppercase
 * @link https://php.net/manual/en/function.strtoupper.php
 * @param string $string <p>
 * The input string.
 * </p>
 * @return string the uppercased string.
 */
#[Pure]
function strtoupper(string $string): string {}

/**
 * Make a string lowercase
 * @link https://php.net/manual/en/function.strtolower.php
 * @param string $string <p>
 * The input string.
 * </p>
 * @return string the lowercased string.
 */
#[Pure]
function strtolower(string $string): string {}

/**
 * Find the position of the first occurrence of a substring in a string
 * @link https://php.net/manual/en/function.strpos.php
 * @param string $haystack <p>
 * The string to search in
 * </p>
 * @param string $needle <p>
 * If <b>needle</b> is not a string, it is converted
 * to an integer and applied as the ordinal value of a character.
 * </p>
 * @param int<0,max> $offset [optional] <p>
 * If specified, search will start this number of characters counted from
 * the beginning of the string. Unlike {@see strrpos()} and {@see strripos()}, the offset cannot be negative.
 * </p>
 * @return int<0,max>|false <p>
 * Returns the position where the needle exists relative to the beginnning of
 * the <b>haystack</b> string (independent of search direction
 * or offset).
 * Also note that string positions start at 0, and not 1.
 * </p>
 * <p>
 * Returns <b>FALSE</b> if the needle was not found.
 * </p>
 */
#[Pure]
function strpos(string $haystack, string $needle, int $offset = 0): int|false {}

/**
 * Find position of first occurrence of a case-insensitive string
 * @link https://php.net/manual/en/function.stripos.php
 * @param string $haystack <p>
 * The string to search in
 * </p>
 * @param string $needle <p>
 * Note that the needle may be a string of one or
 * more characters.
 * </p>
 * <p>
 * If needle is not a string, it is converted to
 * an integer and applied as the ordinal value of a character.
 * </p>
 * @param int $offset <p>
 * The optional offset parameter allows you
 * to specify which character in haystack to
 * start searching. The position returned is still relative to the
 * beginning of haystack.
 * </p>
 * @return int|false If needle is not found,
 * stripos will return boolean false.
 */
#[Pure]
function stripos(string $haystack, string $needle, int $offset = 0): int|false {}

/**
 * Find the position of the last occurrence of a substring in a string
 * @link https://php.net/manual/en/function.strrpos.php
 * @param string $haystack <p>
 * The string to search in.
 * </p>
 * @param string $needle <p>
 * If <b>needle</b> is not a string, it is converted to an integer and applied as the ordinal value of a character.
 * </p>
 * @param int $offset [optional] <p>
 * If specified, search will start this number of characters counted from the beginning of the string. If the value is negative, search will instead start from that many characters from the end of the string, searching backwards.
 * </p>
 * @return int|false <p>
 * Returns the position where the needle exists relative to the beginning of
 * the <b>haystack</b> string (independent of search direction
 * or offset).
 * Also note that string positions start at 0, and not 1.
 * </p>
 * <p>
 * Returns <b>FALSE</b> if the needle was not found.
 * </p>
 */
#[Pure]
function strrpos(string $haystack, string $needle, int $offset = 0): int|false {}

/**
 * Find position of last occurrence of a case-insensitive string in a string
 * @link https://php.net/manual/en/function.strripos.php
 * @param string $haystack <p>
 * The string to search in
 * </p>
 * @param string $needle <p>
 * Note that the needle may be a string of one or
 * more characters.
 * </p>
 * @param int $offset <p>
 * The offset parameter may be specified to begin
 * searching an arbitrary number of characters into the string.
 * </p>
 * <p>
 * Negative offset values will start the search at
 * offset characters from the
 * start of the string.
 * </p>
 * @return int|false the numerical position of the last occurrence of
 * needle. Also note that string positions start at 0,
 * and not 1.
 * </p>
 * <p>
 * If needle is not found, false is returned.
 */
#[Pure]
function strripos(string $haystack, string $needle, int $offset = 0): int|false {}

/**
 * Reverse a string
 * @link https://php.net/manual/en/function.strrev.php
 * @param string $string <p>
 * The string to be reversed.
 * </p>
 * @return string the reversed string.
 */
#[Pure]
function strrev(string $string): string {}

/**
 * Convert logical Hebrew text to visual text
 * @link https://php.net/manual/en/function.hebrev.php
 * @param string $string <p>
 * A Hebrew input string.
 * </p>
 * @param int $max_chars_per_line <p>
 * This optional parameter indicates maximum number of characters per
 * line that will be returned.
 * </p>
 * @return string the visual string.
 */
#[Pure]
function hebrev(string $string, int $max_chars_per_line = 0): string {}

/**
 * Convert logical Hebrew text to visual text with newline conversion
 * @link https://php.net/manual/en/function.hebrevc.php
 * @param string $hebrew_text <p>
 * A Hebrew input string.
 * </p>
 * @param int $max_chars_per_line [optional] <p>
 * This optional parameter indicates maximum number of characters per
 * line that will be returned.
 * </p>
 * @return string the visual string.
 * @removed 8.0
 */
#[Deprecated(replacement: 'nl2br(hebrev(%parameter0%))', since: '7.4')]
function hebrevc(string $hebrew_text, $max_chars_per_line): string {}

/**
 * Inserts HTML line breaks before all newlines in a string
 * @link https://php.net/manual/en/function.nl2br.php
 * @param string $string <p>
 * The input string.
 * </p>
 * @param bool $use_xhtml [optional] <p>
 * Whenever to use XHTML compatible line breaks or not.
 * </p>
 * @return string the altered string.
 */
#[Pure]
function nl2br(string $string, bool $use_xhtml = true): string {}

/**
 * Returns trailing name component of path
 * @link https://php.net/manual/en/function.basename.php
 * @param string $path <p>
 * A path.
 * </p>
 * <p>
 * On Windows, both slash (/) and backslash
 * (\) are used as directory separator character. In
 * other environments, it is the forward slash (/).
 * </p>
 * @param string $suffix <p>
 * If the filename ends in suffix this will also
 * be cut off.
 * </p>
 * @return string the base name of the given path.
 */
#[Pure]
function basename(string $path, string $suffix = ''): string {}

/**
 * Returns a parent directory's path
 * @link https://php.net/manual/en/function.dirname.php
 * @param string $path <p>
 * A path.
 * </p>
 * <p>
 * On Windows, both slash (/) and backslash
 * (\) are used as directory separator character. In
 * other environments, it is the forward slash (/).
 * </p>
 * @param int $levels <p>
 * The number of parent directories to go up.
 * This must be an integer greater than 0.
 * </p>
 * @return string the name of the directory. If there are no slashes in
 * path, a dot ('.') is returned,
 * indicating the current directory. Otherwise, the returned string is
 * path with any trailing
 * /component removed.
 */
#[Pure]
function dirname(string $path, #[PhpStormStubsElementAvailable(from: '7.0')] int $levels = 1): string {}

/**
 * Returns information about a file path
 * @link https://php.net/manual/en/function.pathinfo.php
 * @param string $path <p>
 * The path being checked.
 * </p>
 * @param int $flags [optional] <p>
 * You can specify which elements are returned with optional parameter
 * options. It composes from
 * PATHINFO_DIRNAME,
 * PATHINFO_BASENAME,
 * PATHINFO_EXTENSION and
 * PATHINFO_FILENAME. It
 * defaults to return all elements.
 * </p>
 * @return string|array{dirname: string, basename: string, extension: string, filename: string} The following associative array elements are returned:
 * dirname, basename,
 * extension (if any), and filename.
 * </p>
 * <p>
 * If options is used, this function will return a
 * string if not all elements are requested.
 */
#[Pure(true)]
#[ArrayShape(['dirname' => 'string', 'basename' => 'string', 'extension' => 'string', 'filename' => 'string'])]
function pathinfo(string $path, #[ExpectedValues(flags: [
    PATHINFO_DIRNAME,
    PATHINFO_BASENAME,
    PATHINFO_EXTENSION,
    PATHINFO_FILENAME
])] int $flags = PATHINFO_ALL): array|string {}

/**
 * Un-quotes a quoted string
 * @link https://php.net/manual/en/function.stripslashes.php
 * @param string $string <p>
 * The input string.
 * </p>
 * @return string a string with backslashes stripped off.
 * (\' becomes ' and so on.)
 * Double backslashes (\\) are made into a single
 * backslash (\).
 */
#[Pure]
function stripslashes(string $string): string {}

/**
 * Un-quote string quoted with <function>addcslashes</function>
 * @link https://php.net/manual/en/function.stripcslashes.php
 * @param string $string <p>
 * The string to be unescaped.
 * </p>
 * @return string the unescaped string.
 */
#[Pure]
function stripcslashes(string $string): string {}

/**
 * Find the first occurrence of a string
 * @link https://php.net/manual/en/function.strstr.php
 * @param string $haystack <p>
 * The input string.
 * </p>
 * @param string $needle <p>
 * If needle is not a string, it is converted to
 * an integer and applied as the ordinal value of a character.
 * </p>
 * @param bool $before_needle [optional] <p>
 * If true, strstr returns
 * the part of the haystack before the first
 * occurrence of the needle.
 * </p>
 * @return string|false the portion of string, or false if needle
 * is not found.
 */
#[Pure]
function strstr(string $haystack, string $needle, bool $before_needle = false): string|false {}

/**
 * Case-insensitive <function>strstr</function>
 * @link https://php.net/manual/en/function.stristr.php
 * @param string $haystack <p>
 * The string to search in
 * </p>
 * @param string $needle <p>
 * If needle is not a string, it is converted to
 * an integer and applied as the ordinal value of a character.
 * </p>
 * @param bool $before_needle [optional] <p>
 * If true, stristr
 * returns the part of the haystack before the
 * first occurrence of the needle.
 * </p>
 * @return string|false the matched substring. If needle is not
 * found, returns false.
 */
#[Pure]
function stristr(string $haystack, string $needle, bool $before_needle = false): string|false {}

/**
 * Find the last occurrence of a character in a string
 * @link https://php.net/manual/en/function.strrchr.php
 * @param string $haystack <p>
 * The string to search in
 * </p>
 * @param string $needle <p>
 * If <b>needle</b> contains more than one character,
 * only the first is used. This behavior is different from that of {@see strstr()}.
 * </p>
 * <p>
 * If <b>needle</b> is not a string, it is converted to
 * an integer and applied as the ordinal value of a character.
 * </p>
 * @return string|false <p>
 * This function returns the portion of string, or <b>FALSE</b> if
 * <b>needle</b> is not found.
 * </p>
 */
#[Pure]
function strrchr(string $haystack, string $needle): string|false {}

/**
 * Randomly shuffles a string
 * @link https://php.net/manual/en/function.str-shuffle.php
 * @param string $string <p>
 * The input string.
 * </p>
 * @return string the shuffled string.
 */
function str_shuffle(string $string): string {}

/**
 * Return information about words used in a string
 * @link https://php.net/manual/en/function.str-word-count.php
 * @param string $string <p>
 * The string
 * </p>
 * @param int $format [optional] <p>
 * Specify the return value of this function. The current supported values
 * are:
 * 0 - returns the number of words found
 * </p>
 * @param string|null $characters [optional] <p>
 * A list of additional characters which will be considered as 'word'
 * </p>
 * @return string[]|int an array or an integer, depending on the
 * format chosen.
 */
#[Pure]
function str_word_count(string $string, int $format = 0, ?string $characters): array|int {}

/**
 * Convert a string to an array
 * @link https://php.net/manual/en/function.str-split.php
 * @param string $string <p>
 * The input string.
 * </p>
 * @param int $length [optional] <p>
 * Maximum length of the chunk.
 * </p>
 * @return string[]|false <p>If the optional split_length parameter is
 * specified, the returned array will be broken down into chunks with each
 * being split_length in length, otherwise each chunk
 * will be one character in length.
 * </p>
 * <p>
 * <b>FALSE</b> is returned if split_length is less than 1.
 * If the split_length length exceeds the length of
 * string, the entire string is returned as the first
 * (and only) array element.
 * </p>
 */
#[Pure]
#[LanguageLevelTypeAware(["8.0" => "array"], default: "array|false")]
function str_split(string $string, int $length = 1): array|false {}

/**
 * Search a string for any of a set of characters
 * @link https://php.net/manual/en/function.strpbrk.php
 * @param string $string <p>
 * The string where char_list is looked for.
 * </p>
 * @param string $characters <p>
 * This parameter is case sensitive.
 * </p>
 * @return string|false a string starting from the character found, or false if it is
 * not found.
 */
#[Pure]
function strpbrk(
    string $string,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.0')] $char_list = '',
    #[PhpStormStubsElementAvailable(from: '7.1')] string $characters
): string|false {}

/**
 * Binary safe comparison of two strings from an offset, up to length characters
 * @link https://php.net/manual/en/function.substr-compare.php
 * @param string $haystack <p>
 * The main string being compared.
 * </p>
 * @param string $needle <p>
 * The secondary string being compared.
 * </p>
 * @param int $offset <p>
 * The start position for the comparison. If negative, it starts counting
 * from the end of the string.
 * </p>
 * @param int|null $length [optional] <p>
 * The length of the comparison.
 * </p>
 * @param bool $case_insensitive [optional] <p>
 * If case_insensitivity is true, comparison is
 * case insensitive.
 * </p>
 * @return int if less than 0 if main_str from position
 * offset is less than str, &gt;
 * 0 if it is greater than str, and 0 if they are equal.
 * If offset is equal to or greater than the length of
 * main_str or length is set and
 * is less than 1, substr_compare prints a warning and returns
 * false.
 */
#[Pure]
function substr_compare(string $haystack, string $needle, int $offset, ?int $length, bool $case_insensitive = false): int {}

/**
 * Locale based string comparison
 * @link https://php.net/manual/en/function.strcoll.php
 * @param string $string1 <p>
 * The first string.
 * </p>
 * @param string $string2 <p>
 * The second string.
 * </p>
 * @return int if less than 0 if str1 is less than
 * str2; &gt; 0 if
 * str1 is greater than
 * str2, and 0 if they are equal.
 */
#[Pure]
function strcoll(string $string1, string $string2): int {}

/**
 * Formats a number as a currency string
 * @link https://php.net/manual/en/function.money-format.php
 * @param string $format <p>
 * The format specification consists of the following sequence:<br>
 * a % character</p>
 * @param float $number <p>
 * The number to be formatted.
 * </p>
 * @return string|null the formatted string. Characters before and after the formatting
 * string will be returned unchanged.
 * Non-numeric number causes returning null and
 * emitting E_WARNING.
 * @removed 8.0
 * @see NumberFormatter
 */
#[Deprecated(reason: 'Use the NumberFormatter functionality', since: '7.4')]
function money_format(string $format, float $number): ?string {}

/**
 * Return part of a string
 * @link https://php.net/manual/en/function.substr.php
 * @param string $string <p>
 * The input string.
 * </p>
 * @param int $offset <p>
 * If start is non-negative, the returned string
 * will start at the start'th position in
 * string, counting from zero. For instance,
 * in the string 'abcdef', the character at
 * position 0 is 'a', the
 * character at position 2 is
 * 'c', and so forth.
 * </p>
 * <p>
 * If start is negative, the returned string
 * will start at the start'th character
 * from the end of string.
 * </p>
 * <p>
 * If string is less than or equal to
 * start characters long, false will be returned.
 * </p>
 * <p>
 * Using a negative start
 * </p>
 * <pre>
 * <?php
 * $rest = substr("abcdef", -1);    // returns "f"
 * $rest = substr("abcdef", -2);    // returns "ef"
 * $rest = substr("abcdef", -3, 1); // returns "d"
 * ?>
 * </pre>
 * @param int|null $length [optional] <p>
 * If length is given and is positive, the string
 * returned will contain at most length characters
 * beginning from start (depending on the length of
 * string).
 * </p>
 * <p>
 * If length is given and is negative, then that many
 * characters will be omitted from the end of string
 * (after the start position has been calculated when a
 * start is negative). If
 * start denotes a position beyond this truncation,
 * an empty string will be returned.
 * </p>
 * <p>
 * If length is given and is 0,
 * false or null an empty string will be returned.
 * </p>
 * Using a negative length:
 * <pre>
 * <?php
 * $rest = substr("abcdef", 0, -1);  // returns "abcde"
 * $rest = substr("abcdef", 2, -1);  // returns "cde"
 * $rest = substr("abcdef", 4, -4);  // returns false
 * $rest = substr("abcdef", -3, -1); // returns "de"
 * ?>
 * </pre>
 * @return string|false the extracted part of string or false on failure.
 */
#[Pure]
#[LanguageLevelTypeAware(["8.0" => "string"], default: "string|false")]
function substr(string $string, int $offset, ?int $length) {}

/**
 * Replace text within a portion of a string
 * @link https://php.net/manual/en/function.substr-replace.php
 * @param string[]|string $string <p>
 * The input string.
 * </p>
 * @param string[]|string $replace <p>
 * The replacement string.
 * </p>
 * @param int[]|int $offset <p>
 * If start is positive, the replacing will
 * begin at the start'th offset into
 * string.
 * </p>
 * <p>
 * If start is negative, the replacing will
 * begin at the start'th character from the
 * end of string.
 * </p>
 * @param int[]|int $length [optional] <p>
 * If given and is positive, it represents the length of the portion of
 * string which is to be replaced. If it is
 * negative, it represents the number of characters from the end of
 * string at which to stop replacing. If it
 * is not given, then it will default to strlen(
 * string ); i.e. end the replacing at the
 * end of string. Of course, if
 * length is zero then this function will have the
 * effect of inserting replacement into
 * string at the given
 * start offset.
 * </p>
 * @return string|string[] The result string is returned. If string is an
 * array then array is returned.
 */
#[Pure]
function substr_replace(array|string $string, array|string $replace, array|int $offset, array|int|null $length = null): array|string {}

/**
 * Quote meta characters
 * @link https://php.net/manual/en/function.quotemeta.php
 * @param string $string <p>
 * The input string.
 * </p>
 * @return string the string with meta characters quoted.
 */
#[Pure]
function quotemeta(string $string): string {}

/**
 * Make a string's first character uppercase
 * @link https://php.net/manual/en/function.ucfirst.php
 * @param string $string <p>
 * The input string.
 * </p>
 * @return string the resulting string.
 */
#[Pure]
function ucfirst(string $string): string {}

/**
 * Make a string's first character lowercase
 * @link https://php.net/manual/en/function.lcfirst.php
 * @param string $string <p>
 * The input string.
 * </p>
 * @return string the resulting string.
 */
#[Pure]
function lcfirst(string $string): string {}

/**
 * Uppercase the first character of each word in a string
 * @link https://php.net/manual/en/function.ucwords.php
 * @param string $string <p>
 * The input string.
 * </p>
 * @param string $separators [optional] <p>
 * The optional separators contains the word separator characters.
 * </p>
 * @return string the modified string.
 */
#[Pure]
function ucwords(string $string, string $separators = " \t\r\n\f\v"): string {}

/**
 * Translate characters or replace substrings
 * @link https://php.net/manual/en/function.strtr.php
 * @param string $string <p>
 * The string being translated.
 * </p>
 * @param string $from <p>
 * The string replacing from.
 * </p>
 * @param string $to <p>
 * The string being translated to to.
 * </p>
 * @return string This function returns a copy of str,
 * translating all occurrences of each character in
 * from to the corresponding character in
 * to.
 */
#[Pure]
function strtr(string $string, string $from, string $to): string {}

/**
 * Translate certain characters
 * @link https://php.net/manual/en/function.strtr.php
 * @param string $str The string being translated.
 * @param array $replace_pairs The replace_pairs parameter may be used as a substitute for to and from in which case it's an array in the form array('from' => 'to', ...).
 * @return string A copy of str, translating all occurrences of each character in from to the corresponding character in to.
 */
#[Pure]
function strtr(string $str, array $replace_pairs): string {}

/**
 * Quote string with slashes
 * @link https://php.net/manual/en/function.addslashes.php
 * @param string $string <p>
 * The string to be escaped.
 * </p>
 * @return string the escaped string.
 */
#[Pure]
function addslashes(string $string): string {}

/**
 * Quote string with slashes in a C style
 * @link https://php.net/manual/en/function.addcslashes.php
 * @param string $string <p>
 * The string to be escaped.
 * </p>
 * @param string $characters <p>
 * A list of characters to be escaped. If
 * charlist contains characters
 * \n, \r etc., they are
 * converted in C-like style, while other non-alphanumeric characters
 * with ASCII codes lower than 32 and higher than 126 converted to
 * octal representation.
 * </p>
 * <p>
 * When you define a sequence of characters in the charlist argument
 * make sure that you know what characters come between the
 * characters that you set as the start and end of the range.
 * </p>
 * <pre>
 * <?php
 * echo addcslashes('foo[ ]', 'A..z');
 * // output:  \f\o\o\[ \]
 * // All upper and lower-case letters will be escaped
 * // ... but so will the [\]^_`
 * ?>
 * </pre>
 * <p>
 * Also, if the first character in a range has a higher ASCII value
 * than the second character in the range, no range will be
 * constructed. Only the start, end and period characters will be
 * escaped. Use the ord function to find the
 * ASCII value for a character.
 * </p>
 * <pre>
 * <?php
 * echo addcslashes("zoo['.']", 'z..A');
 * // output:  \zoo['\.']
 * ?>
 * </pre>
 * <p>
 * Be careful if you choose to escape characters 0, a, b, f, n, r,
 * t and v. They will be converted to \0, \a, \b, \f, \n, \r, \t
 * and \v.
 * In PHP \0 (NULL), \r (carriage return), \n (newline), \f (form feed),
 * \v (vertical tab) and \t (tab) are predefined escape sequences,
 * while in C all of these are predefined escape sequences.
 * </p>
 * @return string the escaped string.
 */
#[Pure]
function addcslashes(string $string, string $characters): string {}

/**
 * Strip whitespace (or other characters) from the end of a string.
 * Without the second parameter, rtrim() will strip these characters:
 * <ul>
 * <li>" " (ASCII 32 (0x20)), an ordinary space.
 * <li>"\t" (ASCII 9 (0x09)), a tab.
 * <li>"\n" (ASCII 10 (0x0A)), a new line (line feed).
 * <li>"\r" (ASCII 13 (0x0D)), a carriage return.
 * <li>"\0" (ASCII 0 (0x00)), the NUL-byte.
 * <li>"\x0B" (ASCII 11 (0x0B)), a vertical tab.
 * </ul>
 * @link https://php.net/manual/en/function.rtrim.php
 * @param string $string <p>
 * The input string.
 * </p>
 * @param string $characters [optional] <p>
 * You can also specify the characters you want to strip, by means
 * of the charlist parameter.
 * Simply list all characters that you want to be stripped. With
 * .. you can specify a range of characters.
 * </p>
 * @return string the modified string.
 */
#[Pure]
function rtrim(string $string, string $characters = " \t\n\r\0\x0B"): string {}

/**
 * Replace all occurrences of the search string with the replacement string
 * @link https://php.net/manual/en/function.str-replace.php
 * @param string|string[] $search <p>
 * The value being searched for, otherwise known as the needle.
 * An array may be used to designate multiple needles.
 * </p>
 * @param string|string[] $replace <p>
 * The replacement value that replaces found search
 * values. An array may be used to designate multiple replacements.
 * </p>
 * @param string|string[] $subject <p>
 * The string or array being searched and replaced on,
 * otherwise known as the haystack.
 * </p>
 * <p>
 * If subject is an array, then the search and
 * replace is performed with every entry of
 * subject, and the return value is an array as
 * well.
 * </p>
 * @param int &$count [optional] If passed, this will hold the number of matched and replaced needles.
 * @return string|string[] This function returns a string or an array with the replaced values.
 */
function str_replace(array|string $search, array|string $replace, array|string $subject, &$count): array|string {}

/**
 * Case-insensitive version of <function>str_replace</function>.
 * @link https://php.net/manual/en/function.str-ireplace.php
 * @param mixed $search <p>
 * Every replacement with search array is
 * performed on the result of previous replacement.
 * </p>
 * @param array|string $replace <p>
 * </p>
 * @param array|string $subject <p>
 * If subject is an array, then the search and
 * replace is performed with every entry of
 * subject, and the return value is an array as
 * well.
 * </p>
 * @param int &$count [optional] <p>
 * The number of matched and replaced needles will
 * be returned in count which is passed by
 * reference.
 * </p>
 * @return string|string[] a string or an array of replacements.
 */
function str_ireplace(array|string $search, array|string $replace, array|string $subject, &$count): array|string {}

/**
 * Repeat a string
 * @link https://php.net/manual/en/function.str-repeat.php
 * @param string $string <p>
 * The string to be repeated.
 * </p>
 * @param int $times <p>
 * Number of time the input string should be
 * repeated.
 * </p>
 * <p>
 * multiplier has to be greater than or equal to 0.
 * If the multiplier is set to 0, the function
 * will return an empty string.
 * </p>
 * @return string the repeated string.
 */
#[Pure]
function str_repeat(string $string, int $times): string {}

/**
 * Return information about characters used in a string
 * @link https://php.net/manual/en/function.count-chars.php
 * @param string $string <p>
 * The examined string.
 * </p>
 * @param int $mode <p>
 * See return values.
 * </p>
 * @return int[]|string Depending on mode
 * count_chars returns one of the following:
 * 0 - an array with the byte-value as key and the frequency of
 * every byte as value.
 * 1 - same as 0 but only byte-values with a frequency greater
 * than zero are listed.
 * 2 - same as 0 but only byte-values with a frequency equal to
 * zero are listed.
 * 3 - a string containing all unique characters is returned.
 * 4 - a string containing all not used characters is returned.
 */
#[Pure]
function count_chars(string $string, int $mode = 0): array|string {}

/**
 * Split a string into smaller chunks
 * @link https://php.net/manual/en/function.chunk-split.php
 * @param string $string <p>
 * The string to be chunked.
 * </p>
 * @param int $length [optional] <p>
 * The chunk length.
 * </p>
 * @param string $separator [optional] <p>
 * The line ending sequence.
 * </p>
 * @return string the chunked string.
 */
#[Pure]
function chunk_split(string $string, int $length = 76, string $separator = "\r\n"): string {}

/**
 * Strip whitespace (or other characters) from the beginning and end of a string
 * @link https://php.net/manual/en/function.trim.php
 * @param string $string <p>
 * The string that will be trimmed.
 * </p>
 * @param string $characters [optional] <p>
 * Optionally, the stripped characters can also be specified using
 * the charlist parameter.
 * Simply list all characters that you want to be stripped. With
 * .. you can specify a range of characters.
 * </p>
 * @return string The trimmed string.
 */
#[Pure]
function trim(string $string, string $characters = " \t\n\r\0\x0B"): string {}

/**
 * Strip whitespace (or other characters) from the beginning of a string
 * @link https://php.net/manual/en/function.ltrim.php
 * @param string $string <p>
 * The input string.
 * </p>
 * @param string $characters [optional] <p>
 * You can also specify the characters you want to strip, by means of the
 * charlist parameter.
 * Simply list all characters that you want to be stripped. With
 * .. you can specify a range of characters.
 * </p>
 * @return string This function returns a string with whitespace stripped from the
 * beginning of str.
 * Without the second parameter,
 * ltrim will strip these characters:
 * " " (ASCII 32
 * (0x20)), an ordinary space.
 * "\t" (ASCII 9
 * (0x09)), a tab.
 * "\n" (ASCII 10
 * (0x0A)), a new line (line feed).
 * "\r" (ASCII 13
 * (0x0D)), a carriage return.
 * "\0" (ASCII 0
 * (0x00)), the NUL-byte.
 * "\x0B" (ASCII 11
 * (0x0B)), a vertical tab.
 */
#[Pure]
function ltrim(string $string, string $characters = " \t\n\r\0\x0B"): string {}

/**
 * Strip HTML and PHP tags from a string
 * @link https://php.net/manual/en/function.strip-tags.php
 * @param string $string <p>
 * The input string.
 * </p>
 * @param string[]|string|null $allowed_tags [optional] <p>
 * You can use the optional second parameter to specify tags which should
 * not be stripped.
 * </p>
 * <p>
 * HTML comments and PHP tags are also stripped. This is hardcoded and
 * can not be changed with allowable_tags.
 * </p>
 * @return string the stripped string.
 */
#[Pure]
function strip_tags(string $string, #[LanguageLevelTypeAware(["7.4" => "string[]|string|null"], default: "string|null")] $allowed_tags = null): string {}

/**
 * Calculate the similarity between two strings
 * @link https://php.net/manual/en/function.similar-text.php
 * @param string $string1 <p>
 * The first string.
 * </p>
 * @param string $string2 <p>
 * The second string.
 * </p>
 * @param float &$percent [optional] <p>
 * By passing a reference as third argument,
 * similar_text will calculate the similarity in
 * percent for you.
 * </p>
 * @return int the number of matching chars in both strings.
 */
function similar_text(string $string1, string $string2, &$percent): int {}

/**
 * Split a string by a string
 * @link https://php.net/manual/en/function.explode.php
 * @param string $separator <p>
 * The boundary string.
 * </p>
 * @param string $string <p>
 * The input string.
 * </p>
 * @param int $limit [optional] <p>
 * If limit is set and positive, the returned array will contain
 * a maximum of limit elements with the last
 * element containing the rest of string.
 * </p>
 * <p>
 * If the limit parameter is negative, all components
 * except the last -limit are returned.
 * </p>
 * <p>
 * If the limit parameter is zero, then this is treated as 1.
 * </p>
 * @return string[]|false If delimiter is an empty string (""),
 * explode will return false.
 * If delimiter contains a value that is not
 * contained in string and a negative
 * limit is used, then an empty array will be
 * returned. For any other limit, an array containing
 * string will be returned.
 */
#[Pure]
#[LanguageLevelTypeAware(["8.0" => "string[]"], default: "string[]|false")]
function explode(string $separator, string $string, int $limit) {}

/**
 * Join array elements with a string
 * @link https://php.net/manual/en/function.implode.php
 * @param array|string  $separator [optional]<p>
 * Defaults to an empty string. This is not the preferred usage of
 * implode as glue would be
 * the second parameter and thus, the bad prototype would be used.
 * </p>
 * @param array|null $array <p>
 * The array of strings to implode.
 * </p>
 * @return string a string containing a string representation of all the array
 * elements in the same order, with the glue string between each element.
 */
#[Pure]
function implode(array|string $separator = "", ?array $array): string {}

/**
 * Alias:
 * {@see implode}
 * @link https://php.net/manual/en/function.join.php
 * @param array|string  $separator [optional] <p>
 * Defaults to an empty string. This is not the preferred usage of
 * implode as glue would be
 * the second parameter and thus, the bad prototype would be used.
 * </p>
 * @param array|null $array <p>
 * The array of strings to implode.
 * </p>
 * @return string a string containing a string representation of all the array
 * elements in the same order, with the glue string between each element.
 */
#[Pure]
function join(array|string $separator = "", ?array $array): string {}

/**
 * Set locale information
 * @link https://php.net/manual/en/function.setlocale.php
 * @param int $category <p>
 * <em>category</em> is a named constant specifying the
 * category of the functions affected by the locale setting:
 * </p><ul>
 * <li>
 * <b>LC_ALL</b> for all of the below
 * </li>
 * <li>
 * <b>LC_COLLATE</b> for string comparison, see
 * {@see strcoll()}
 * </li>
 * <li>
 * <b>LC_CTYPE</b> for character classification and conversion, for
 * example {@see strtoupper()}
 * </li>
 * <li>
 * <b>LC_MONETARY</b> for {@see localeconv()}
 * </li>
 * <li>
 * <b>LC_NUMERIC</b> for decimal separator (See also
 * {@see localeconv()})
 * </li>
 * <li>
 * <b>LC_TIME</b> for date and time formatting with
 * {@see strftime()}
 *
 * </li>
 * <li>
 * <b>LC_MESSAGES</B> for system responses (available if PHP was compiled with
 * <em>libintl</em>)
 *
 * </li>
 * </ul>
 * @param string|array|int $locales <p>
 * If locale is null or the empty string
 * "", the locale names will be set from the
 * values of environment variables with the same names as the above
 * categories, or from "LANG".
 * </p>
 * <p>
 * If locale is "0",
 * the locale setting is not affected, only the current setting is returned.
 * </p>
 * <p>
 * If locale is an array or followed by additional
 * parameters then each array element or parameter is tried to be set as
 * new locale until success. This is useful if a locale is known under
 * different names on different systems or for providing a fallback
 * for a possibly not available locale.
 * </p>
 * @param string ...$rest
 * @return string|false <p>the new current locale, or false if the locale functionality is
 * not implemented on your platform, the specified locale does not exist or
 * the category name is invalid.
 * </p>
 * <p>
 * An invalid category name also causes a warning message. Category/locale
 * names can be found in RFC 1766
 * and ISO 639.
 * Different systems have different naming schemes for locales.
 * </p>
 * <p>
 * The return value of setlocale depends
 * on the system that PHP is running. It returns exactly
 * what the system setlocale function returns.</p>
 */
function setlocale(
    #[ExpectedValues([LC_ALL,  LC_COLLATE,  LC_CTYPE,  LC_MONETARY,  LC_NUMERIC,  LC_TIME,  LC_MESSAGES])] int $category,
    #[PhpStormStubsElementAvailable(from: '8.0')] $locales,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $rest,
    ...$rest
): string|false {}

/**
 * Get numeric formatting information
 * @link https://php.net/manual/en/function.localeconv.php
 * @return array localeconv returns data based upon the current locale
 * as set by setlocale. The associative array that is
 * returned contains the following fields:
 * <tr valign="top">
 * <td>Array element</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td>decimal_point</td>
 * <td>Decimal point character</td>
 * </tr>
 * <tr valign="top">
 * <td>thousands_sep</td>
 * <td>Thousands separator</td>
 * </tr>
 * <tr valign="top">
 * <td>grouping</td>
 * <td>Array containing numeric groupings</td>
 * </tr>
 * <tr valign="top">
 * <td>int_curr_symbol</td>
 * <td>International currency symbol (i.e. USD)</td>
 * </tr>
 * <tr valign="top">
 * <td>currency_symbol</td>
 * <td>Local currency symbol (i.e. $)</td>
 * </tr>
 * <tr valign="top">
 * <td>mon_decimal_point</td>
 * <td>Monetary decimal point character</td>
 * </tr>
 * <tr valign="top">
 * <td>mon_thousands_sep</td>
 * <td>Monetary thousands separator</td>
 * </tr>
 * <tr valign="top">
 * <td>mon_grouping</td>
 * <td>Array containing monetary groupings</td>
 * </tr>
 * <tr valign="top">
 * <td>positive_sign</td>
 * <td>Sign for positive values</td>
 * </tr>
 * <tr valign="top">
 * <td>negative_sign</td>
 * <td>Sign for negative values</td>
 * </tr>
 * <tr valign="top">
 * <td>int_frac_digits</td>
 * <td>International fractional digits</td>
 * </tr>
 * <tr valign="top">
 * <td>frac_digits</td>
 * <td>Local fractional digits</td>
 * </tr>
 * <tr valign="top">
 * <td>p_cs_precedes</td>
 * <td>
 * true if currency_symbol precedes a positive value, false
 * if it succeeds one
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>p_sep_by_space</td>
 * <td>
 * true if a space separates currency_symbol from a positive
 * value, false otherwise
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>n_cs_precedes</td>
 * <td>
 * true if currency_symbol precedes a negative value, false
 * if it succeeds one
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>n_sep_by_space</td>
 * <td>
 * true if a space separates currency_symbol from a negative
 * value, false otherwise
 * </td>
 * </tr>
 * <td>p_sign_posn</td>
 * <td>
 * 0 - Parentheses surround the quantity and currency_symbol
 * 1 - The sign string precedes the quantity and currency_symbol
 * 2 - The sign string succeeds the quantity and currency_symbol
 * 3 - The sign string immediately precedes the currency_symbol
 * 4 - The sign string immediately succeeds the currency_symbol
 * </td>
 * </tr>
 * <td>n_sign_posn</td>
 * <td>
 * 0 - Parentheses surround the quantity and currency_symbol
 * 1 - The sign string precedes the quantity and currency_symbol
 * 2 - The sign string succeeds the quantity and currency_symbol
 * 3 - The sign string immediately precedes the currency_symbol
 * 4 - The sign string immediately succeeds the currency_symbol
 * </td>
 * </tr>
 * </p>
 * <p>
 * The p_sign_posn, and n_sign_posn contain a string
 * of formatting options. Each number representing one of the above listed conditions.
 * </p>
 * <p>
 * The grouping fields contain arrays that define the way numbers should be
 * grouped. For example, the monetary grouping field for the nl_NL locale (in
 * UTF-8 mode with the euro sign), would contain a 2 item array with the
 * values 3 and 3. The higher the index in the array, the farther left the
 * grouping is. If an array element is equal to CHAR_MAX,
 * no further grouping is done. If an array element is equal to 0, the previous
 * element should be used.
 */
#[ArrayShape(["decimal_point" => "string", "thousands_sep" => "string", "grouping" => "array", "int_curr_symbol" => "string", "currency_symbol" => "string", "mon_decimal_point" => "string", "mon_thousands_sep" => "string", "mon_grouping" => "string", "positive_sign" => "string", "negative_sign" => "string", "int_frac_digits" => "string", "frac_digits" => "string", "p_cs_precedes" => "bool", "p_sep_by_space" => "bool", "n_cs_precedes" => "bool", "n_sep_by_space" => "bool", "p_sign_posn" => "int", "n_sign_posn" => "int"])]
#[Pure(true)]
function localeconv(): array {}
