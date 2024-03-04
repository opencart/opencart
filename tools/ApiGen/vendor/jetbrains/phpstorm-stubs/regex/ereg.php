<?php

// Start of ereg v.
use JetBrains\PhpStorm\Deprecated;

/**
 * Regular expression match
 * @link https://php.net/manual/en/function.ereg.php
 * @param string $pattern <p>
 * Case sensitive regular expression.
 * </p>
 * @param string $string <p>
 * The input string.
 * </p>
 * @param null|array &$regs [optional] <p>
 * If matches are found for parenthesized substrings of
 * <i>pattern</i> and the function is called with the
 * third argument <i>regs</i>, the matches will be stored
 * in the elements of the array <i>regs</i>.
 * </p>
 * <p>
 * $regs[1] will contain the substring which starts at
 * the first left parenthesis; $regs[2] will contain
 * the substring starting at the second, and so on.
 * $regs[0] will contain a copy of the complete string
 * matched.
 * </p>
 * @return int the length of the matched string if a match for
 * <i>pattern</i> was found in <i>string</i>,
 * or <b>FALSE</b> if no matches were found or an error occurred.
 * </p>
 * <p>
 * If the optional parameter <i>regs</i> was not passed or
 * the length of the matched string is 0, this function returns 1.
 * @removed 7.0
 * @see preg_match()
 */
#[Deprecated(reason: "Use preg_match() instead", since: "5.3")]
function ereg($pattern, $string, ?array &$regs = null) {}

/**
 * Replace regular expression
 * @link https://php.net/manual/en/function.ereg-replace.php
 * @param string $pattern <p>
 * A POSIX extended regular expression.
 * </p>
 * @param string $replacement <p>
 * If <i>pattern</i> contains parenthesized substrings,
 * <i>replacement</i> may contain substrings of the form
 * \digit, which will be
 * replaced by the text matching the digit'th parenthesized substring;
 * \0 will produce the entire contents of string.
 * Up to nine substrings may be used. Parentheses may be nested, in which
 * case they are counted by the opening parenthesis.
 * </p>
 * @param string $string <p>
 * The input string.
 * </p>
 * @return string The modified string is returned. If no matches are found in
 * <i>string</i>, then it will be returned unchanged.
 * @removed 7.0
 * @see preg_replace()
 */
#[Deprecated(reason: "Use preg_replace() instead", since: "5.3")]
function ereg_replace($pattern, $replacement, $string) {}

/**
 * Case insensitive regular expression match
 * @link https://php.net/manual/en/function.eregi.php
 * @param string $pattern <p>
 * Case insensitive regular expression.
 * </p>
 * @param string $string <p>
 * The input string.
 * </p>
 * @param null|array &$regs [optional] <p>
 * If matches are found for parenthesized substrings of
 * <i>pattern</i> and the function is called with the
 * third argument <i>regs</i>, the matches will be stored
 * in the elements of the array <i>regs</i>.
 * </p>
 * <p>
 * $regs[1] will contain the substring which starts at the first left
 * parenthesis; $regs[2] will contain the substring starting at the
 * second, and so on. $regs[0] will contain a copy of the complete string
 * matched.
 * </p>
 * @return int the length of the matched string if a match for
 * <i>pattern</i> was found in <i>string</i>,
 * or <b>FALSE</b> if no matches were found or an error occurred.
 * </p>
 * <p>
 * If the optional parameter <i>regs</i> was not passed or
 * the length of the matched string is 0, this function returns 1.
 * @removed 7.0
 * @see preg_match()
 */
#[Deprecated(reason: "Use preg_match() instead", since: "5.3")]
function eregi($pattern, $string, array &$regs = null) {}

/**
 * Replace regular expression case insensitive
 * @link https://php.net/manual/en/function.eregi-replace.php
 * @param string $pattern <p>
 * A POSIX extended regular expression.
 * </p>
 * @param string $replacement <p>
 * If <i>pattern</i> contains parenthesized substrings,
 * <i>replacement</i> may contain substrings of the form
 * \digit, which will be
 * replaced by the text matching the digit'th parenthesized substring;
 * \0 will produce the entire contents of string.
 * Up to nine substrings may be used. Parentheses may be nested, in which
 * case they are counted by the opening parenthesis.
 * </p>
 * @param string $string <p>
 * The input string.
 * </p>
 * @return string The modified string is returned. If no matches are found in
 * <i>string</i>, then it will be returned unchanged.
 * @removed 7.0
 * @see preg_replace()
 */
#[Deprecated(reason: "Use preg_replace() instead", since: "5.3")]
function eregi_replace($pattern, $replacement, $string) {}

/**
 * Split string into array by regular expression
 * @link https://php.net/manual/en/function.split.php
 * @param string $pattern <p>
 * Case sensitive regular expression.
 * </p>
 * <p>
 * If you want to split on any of the characters which are considered
 * special by regular expressions, you'll need to escape them first. If
 * you think <b>split</b> (or any other regex function, for
 * that matter) is doing something weird, please read the file
 * regex.7, included in the
 * regex/ subdirectory of the PHP distribution. It's
 * in manpage format, so you'll want to do something along the lines of
 * man /usr/local/src/regex/regex.7 in order to read it.
 * </p>
 * @param string $string <p>
 * The input string.
 * </p>
 * @param int $limit [optional] <p>
 * If <i>limit</i> is set, the returned array will
 * contain a maximum of <i>limit</i> elements with the
 * last element containing the whole rest of
 * <i>string</i>.
 * </p>
 * @return array an array of strings, each of which is a substring of
 * <i>string</i> formed by splitting it on boundaries formed
 * by the case-sensitive regular expression <i>pattern</i>.
 * </p>
 * <p>
 * If there are n occurrences of
 * <i>pattern</i>, the returned array will contain
 * n+1 items. For example, if
 * there is no occurrence of <i>pattern</i>, an array with
 * only one element will be returned. Of course, this is also true if
 * <i>string</i> is empty. If an error occurs,
 * <b>split</b> returns <b>FALSE</b>.
 * @removed 7.0
 * @see preg_split()
 */
#[Deprecated(reason: "Use preg_split() instead", since: "5.3")]
function split($pattern, $string, $limit = -1) {}

/**
 * Split string into array by regular expression case insensitive
 * @link https://php.net/manual/en/function.spliti.php
 * @param string $pattern <p>
 * Case insensitive regular expression.
 * </p>
 * <p>
 * If you want to split on any of the characters which are considered
 * special by regular expressions, you'll need to escape them first. If
 * you think <b>spliti</b> (or any other regex function, for
 * that matter) is doing something weird, please read the file
 * regex.7, included in the
 * regex/ subdirectory of the PHP distribution. It's
 * in manpage format, so you'll want to do something along the lines of
 * man /usr/local/src/regex/regex.7 in order to read it.
 * </p>
 * @param string $string <p>
 * The input string.
 * </p>
 * @param int $limit [optional] <p>
 * If <i>limit</i> is set, the returned array will
 * contain a maximum of <i>limit</i> elements with the
 * last element containing the whole rest of
 * <i>string</i>.
 * </p>
 * @return array an array of strings, each of which is a substring of
 * <i>string</i> formed by splitting it on boundaries formed
 * by the case insensitive regular expression <i>pattern</i>.
 * </p>
 * <p>
 * If there are n occurrences of
 * <i>pattern</i>, the returned array will contain
 * n+1 items. For example, if
 * there is no occurrence of <i>pattern</i>, an array with
 * only one element will be returned. Of course, this is also true if
 * <i>string</i> is empty. If an error occurs,
 * <b>spliti</b> returns <b>FALSE</b>.
 * @removed 7.0
 * @see preg_split()
 */
#[Deprecated(reason: "Use preg_split() instead", since: "5.3")]
function spliti($pattern, $string, $limit = -1) {}

/**
 * Make regular expression for case insensitive match
 * @link https://php.net/manual/en/function.sql-regcase.php
 * @param string $string <p>
 * The input string.
 * </p>
 * @return string a valid regular expression which will match
 * <i>string</i>, ignoring case. This expression is
 * <i>string</i> with each alphabetic character converted to
 * a bracket expression; this bracket expression contains that character's
 * uppercase and lowercase form. Other characters remain unchanged.
 * @removed 7.0
 */
#[Deprecated(since: '5.3')]
function sql_regcase($string) {}

// End of ereg v.
