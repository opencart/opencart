<?php

use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Pure;

/**
 * Check for alphanumeric character(s)
 * @link https://php.net/manual/en/function.ctype-alnum.php
 * @param string $text <p>
 * The tested string.
 * </p>
 * @return bool <b>TRUE</b> if every character in <i>text</i> is either
 * a letter or a digit, <b>FALSE</b> otherwise.
 */
#[Pure]
function ctype_alnum(#[LanguageLevelTypeAware(['8.1' => 'string'], default: 'mixed')] mixed $text): bool {}

/**
 * Check for alphabetic character(s)
 * @link https://php.net/manual/en/function.ctype-alpha.php
 * @param string $text <p>
 * The tested string.
 * </p>
 * @return bool <b>TRUE</b> if every character in <i>text</i> is
 * a letter from the current locale, <b>FALSE</b> otherwise.
 */
#[Pure]
function ctype_alpha(#[LanguageLevelTypeAware(['8.1' => 'string'], default: 'mixed')] mixed $text): bool {}

/**
 * Check for control character(s)
 * @link https://php.net/manual/en/function.ctype-cntrl.php
 * @param string $text <p>
 * The tested string.
 * </p>
 * @return bool <b>TRUE</b> if every character in <i>text</i> is
 * a control character from the current locale, <b>FALSE</b> otherwise.
 */
#[Pure]
function ctype_cntrl(#[LanguageLevelTypeAware(['8.1' => 'string'], default: 'mixed')] mixed $text): bool {}

/**
 * Check for numeric character(s)
 * @link https://php.net/manual/en/function.ctype-digit.php
 * @param string $text <p>
 * The tested string.
 * </p>
 * @return bool <b>TRUE</b> if every character in the string
 * <i>text</i> is a decimal digit, <b>FALSE</b> otherwise.
 */
#[Pure]
function ctype_digit(#[LanguageLevelTypeAware(['8.1' => 'string'], default: 'mixed')] mixed $text): bool {}

/**
 * Check for lowercase character(s)
 * @link https://php.net/manual/en/function.ctype-lower.php
 * @param string $text <p>
 * The tested string.
 * </p>
 * @return bool <b>TRUE</b> if every character in <i>text</i> is
 * a lowercase letter in the current locale.
 */
#[Pure]
function ctype_lower(#[LanguageLevelTypeAware(['8.1' => 'string'], default: 'mixed')] mixed $text): bool {}

/**
 * Check for any printable character(s) except space
 * @link https://php.net/manual/en/function.ctype-graph.php
 * @param string $text <p>
 * The tested string.
 * </p>
 * @return bool <b>TRUE</b> if every character in <i>text</i> is
 * printable and actually creates visible output (no white space), <b>FALSE</b>
 * otherwise.
 */
#[Pure]
function ctype_graph(#[LanguageLevelTypeAware(['8.1' => 'string'], default: 'mixed')] mixed $text): bool {}

/**
 * Check for printable character(s)
 * @link https://php.net/manual/en/function.ctype-print.php
 * @param string $text <p>
 * The tested string.
 * </p>
 * @return bool <b>TRUE</b> if every character in <i>text</i>
 * will actually create output (including blanks). Returns <b>FALSE</b> if
 * <i>text</i> contains control characters or characters
 * that do not have any output or control function at all.
 */
#[Pure]
function ctype_print(#[LanguageLevelTypeAware(['8.1' => 'string'], default: 'mixed')] mixed $text): bool {}

/**
 * Check for any printable character which is not whitespace or an
 * alphanumeric character
 * @link https://php.net/manual/en/function.ctype-punct.php
 * @param string $text <p>
 * The tested string.
 * </p>
 * @return bool <b>TRUE</b> if every character in <i>text</i>
 * is printable, but neither letter, digit or blank, <b>FALSE</b> otherwise.
 */
#[Pure]
function ctype_punct(#[LanguageLevelTypeAware(['8.1' => 'string'], default: 'mixed')] mixed $text): bool {}

/**
 * Check for whitespace character(s)
 * @link https://php.net/manual/en/function.ctype-space.php
 * @param string $text <p>
 * The tested string.
 * </p>
 * @return bool <b>TRUE</b> if every character in <i>text</i>
 * creates some sort of white space, <b>FALSE</b> otherwise. Besides the
 * blank character this also includes tab, vertical tab, line feed,
 * carriage return and form feed characters.
 */
#[Pure]
function ctype_space(#[LanguageLevelTypeAware(['8.1' => 'string'], default: 'mixed')] mixed $text): bool {}

/**
 * Check for uppercase character(s)
 * @link https://php.net/manual/en/function.ctype-upper.php
 * @param string $text <p>
 * The tested string.
 * </p>
 * @return bool <b>TRUE</b> if every character in <i>text</i> is
 * an uppercase letter in the current locale.
 */
#[Pure]
function ctype_upper(#[LanguageLevelTypeAware(['8.1' => 'string'], default: 'mixed')] mixed $text): bool {}

/**
 * Check for character(s) representing a hexadecimal digit
 * @link https://php.net/manual/en/function.ctype-xdigit.php
 * @param string $text <p>
 * The tested string.
 * </p>
 * @return bool <b>TRUE</b> if every character in <i>text</i> is
 * a hexadecimal 'digit', that is a decimal digit or a character from
 * [A-Fa-f] , <b>FALSE</b> otherwise.
 */
#[Pure]
function ctype_xdigit(#[LanguageLevelTypeAware(['8.1' => 'string'], default: 'mixed')] mixed $text): bool {}
