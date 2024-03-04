<?php

use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;
use JetBrains\PhpStorm\Pure;

/**
 * Add two arbitrary precision numbers
 * @link https://php.net/manual/en/function.bcadd.php
 * @param string $num1 <p>
 * The left operand, as a string.
 * </p>
 * @param string $num2 <p>
 * The right operand, as a string.
 * </p>
 * @param int|null $scale <p>
 * This optional parameter is used to set the number of digits after the
 * decimal place in the result. If omitted, it will default to the scale
 * set globally with the {@link bcscale()} function, or fallback to 0 if
 * this has not been set.
 * </p>
 * @return string The sum of the two operands, as a string.
 */
#[Pure]
function bcadd(string $num1, string $num2, ?int $scale = null): string {}

/**
 * Subtract one arbitrary precision number from another
 * @link https://php.net/manual/en/function.bcsub.php
 * @param string $num1 <p>
 * The left operand, as a string.
 * </p>
 * @param string $num2 <p>
 * The right operand, as a string.
 * </p>
 * @param int|null $scale <p>
 * This optional parameter is used to set the number of digits after the
 * decimal place in the result. If omitted, it will default to the scale
 * set globally with the {@link bcscale()} function, or fallback to 0 if
 * this has not been set.
 * </p>
 * @return string The result of the subtraction, as a string.
 */
#[Pure]
function bcsub(string $num1, string $num2, ?int $scale = null): string {}

/**
 * Multiply two arbitrary precision numbers
 * @link https://php.net/manual/en/function.bcmul.php
 * @param string $num1 <p>
 * The left operand, as a string.
 * </p>
 * @param string $num2 <p>
 * The right operand, as a string.
 * </p>
 * @param int|null $scale <p>
 * This optional parameter is used to set the number of digits after the
 * decimal place in the result. If omitted, it will default to the scale
 * set globally with the {@link bcscale()} function, or fallback to 0 if
 * this has not been set.
 * </p>
 * @return string the result as a string.
 */
#[Pure]
function bcmul(string $num1, string $num2, ?int $scale = null): string {}

/**
 * Divide two arbitrary precision numbers
 * @link https://php.net/manual/en/function.bcdiv.php
 * @param string $num1 <p>
 * The dividend, as a string.
 * </p>
 * @param string $num2 <p>
 * The divisor, as a string.
 * </p>
 * @param int|null $scale [optional] <p>
 * This optional parameter is used to set the number of digits after the
 * decimal place in the result. If omitted, it will default to the scale
 * set globally with the {@link bcscale()} function, or fallback to 0 if
 * this has not been set.
 * </p>
 * @return string|null the result of the division as a string, or <b>NULL</b> if
 * <i>divisor</i> is 0.
 */
#[Pure]
#[PhpStormStubsElementAvailable(to: '7.4')]
function bcdiv(string $num1, string $num2, ?int $scale = 0): ?string {}

/**
 * Divide two arbitrary precision numbers
 * @link https://php.net/manual/en/function.bcdiv.php
 * @param string $num1 <p>
 * The dividend, as a string.
 * </p>
 * @param string $num2 <p>
 * The divisor, as a string.
 * </p>
 * @param int|null $scale [optional] <p>
 * This optional parameter is used to set the number of digits after the
 * decimal place in the result. If omitted, it will default to the scale
 * set globally with the {@link bcscale()} function, or fallback to 0 if
 * this has not been set.
 * </p>
 * @return string the result of the division as a string.
 * @throws \DivisionByZeroError if <i>divisor</i> is 0. Available since PHP 8.0.
 */
#[Pure]
#[PhpStormStubsElementAvailable('8.0')]
function bcdiv(string $num1, string $num2, ?int $scale = 0): string {}

/**
 * Get modulus of an arbitrary precision number
 * @link https://php.net/manual/en/function.bcmod.php
 * @param string $num1 <p>
 * The dividend, as a string. Since PHP 7.2, the divided is no longer truncated to an integer.
 * </p>
 * @param string $num2 <p>
 * The divisor, as a string. Since PHP 7.2, the divisor is no longer truncated to an integer.
 * </p>
 * @param int|null $scale [optional] <p>
 * This optional parameter is used to set the number of digits after the
 * decimal place in the result. If omitted, it will default to the scale
 * set globally with the {@link bcscale()} function, or fallback to 0 if
 * this has not been set. Available since PHP 7.2.
 * </p>
 * @return string|null the modulus as a string, or <b>NULL</b> if
 * <i>divisor</i> is 0.
 */
#[Pure]
#[PhpStormStubsElementAvailable(to: '7.4')]
function bcmod(string $num1, string $num2, ?int $scale = 0): ?string {}

/**
 * Get modulus of an arbitrary precision number
 * @link https://php.net/manual/en/function.bcmod.php
 * @param string $num1 <p>
 * The dividend, as a string. Since PHP 7.2, the divided is no longer truncated to an integer.
 * </p>
 * @param string $num2 <p>
 * The divisor, as a string. Since PHP 7.2, the divisor is no longer truncated to an integer.
 * </p>
 * @param int|null $scale [optional] <p>
 * This optional parameter is used to set the number of digits after the
 * decimal place in the result. If omitted, it will default to the scale
 * set globally with the {@link bcscale()} function, or fallback to 0 if
 * this has not been set. Available since PHP 7.2.
 * </p>
 * @return string the modulus as a string.
 * @throws \DivisionByZeroError if <i>divisor</i> is 0. Available since PHP 8.0.
 */
#[Pure]
#[PhpStormStubsElementAvailable('8.0')]
function bcmod(string $num1, string $num2, ?int $scale = 0): string {}

/**
 * Raise an arbitrary precision number to another
 * @link https://php.net/manual/en/function.bcpow.php
 * @param string $num <p>
 * The base, as a string.
 * </p>
 * @param string $exponent <p>
 * The exponent, as a string. If the exponent is non-integral, it is truncated.
 * The valid range of the exponent is platform specific, but is at least
 * -2147483648 to 2147483647.
 * </p>
 * @param int|null $scale <p>
 * This optional parameter is used to set the number of digits after the
 * decimal place in the result. If omitted, it will default to the scale
 * set globally with the {@link bcscale()} function, or fallback to 0 if
 * this has not been set.
 * </p>
 * @return string the result as a string.
 */
#[Pure]
function bcpow(string $num, string $exponent, ?int $scale = null): string {}

/**
 * Get the square root of an arbitrary precision number
 * @link https://php.net/manual/en/function.bcsqrt.php
 * @param string $num <p>
 * The operand, as a string.
 * </p>
 * @param int|null $scale [optional]
 * @return string|null the square root as a string, or <b>NULL</b> if
 * <i>operand</i> is negative.
 */
#[Pure]
#[LanguageLevelTypeAware(["8.0" => "string"], default: "?string")]
function bcsqrt(string $num, ?int $scale) {}

/**
 * Set default scale parameter for all bc math functions
 * @link https://php.net/manual/en/function.bcscale.php
 * @param int $scale
 * @return int|bool
 */
#[LanguageLevelTypeAware(['7.3' => 'int'], default: 'bool')]
function bcscale(
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.2')] int $scale,
    #[PhpStormStubsElementAvailable(from: '7.3')] #[LanguageLevelTypeAware(['8.0' => 'int|null'], default: 'int')] $scale = null
) {}

/**
 * Compare two arbitrary precision numbers
 * @link https://php.net/manual/en/function.bccomp.php
 * @param string $num1 <p>
 * The left operand, as a string.
 * </p>
 * @param string $num2 <p>
 * The right operand, as a string.
 * </p>
 * @param int|null $scale <p>
 * The optional <i>scale</i> parameter is used to set the
 * number of digits after the decimal place which will be used in the
 * comparison.
 * </p>
 * @return int 0 if the two operands are equal, 1 if the
 * <i>left_operand</i> is larger than the
 * <i>right_operand</i>, -1 otherwise.
 */
#[Pure]
function bccomp(string $num1, string $num2, ?int $scale = null): int {}

/**
 * Raise an arbitrary precision number to another, reduced by a specified modulus
 * @link https://php.net/manual/en/function.bcpowmod.php
 * @param string $num <p>
 * The base, as an integral string (i.e. the scale has to be zero).
 * </p>
 * @param string $exponent <p>
 * The exponent, as an non-negative, integral string (i.e. the scale has to be
 * zero).
 * </p>
 * @param string $modulus <p>
 * The modulus, as an integral string (i.e. the scale has to be zero).
 * </p>
 * @param int|null $scale <p>
 * This optional parameter is used to set the number of digits after the
 * decimal place in the result. If omitted, it will default to the scale
 * set globally with the {@link bcscale()} function, or fallback to 0 if
 * this has not been set.
 * </p>
 * @return string|null the result as a string, or <b>NULL</b> if <i>modulus</i>
 * is 0 or <i>exponent</i> is negative.
 */
#[Pure]
#[LanguageLevelTypeAware(["8.0" => "string"], default: "?string")]
function bcpowmod(string $num, string $exponent, string $modulus, ?int $scale = null) {}
