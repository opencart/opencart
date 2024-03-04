<?php

// Start of gmp v.
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Deprecated;
use JetBrains\PhpStorm\Pure;

/**
 * Create GMP number
 * @link https://php.net/manual/en/function.gmp-init.php
 * @param mixed $num <p>
 * An integer or a string. The string representation can be decimal,
 * hexadecimal or octal.
 * </p>
 * @param int $base [optional] <p>
 * The base.
 * </p>
 * <p>
 * The base may vary from 2 to 36. If base is 0 (default value), the
 * actual base is determined from the leading characters: if the first
 * two characters are 0x or 0X,
 * hexadecimal is assumed, otherwise if the first character is "0",
 * octal is assumed, otherwise decimal is assumed.
 * </p>
 * @return resource|GMP A GMP number resource.
 */
#[Pure]
function gmp_init(string|int $num, int $base = 0): GMP {}

/**
 * Convert GMP number to integer
 * @link https://php.net/manual/en/function.gmp-intval.php
 * @param resource|int|string|GMP $num <p>
 * A GMP number.
 * </p>
 * @return int An integer value of <i>gmpnumber</i>.
 */
#[Pure]
function gmp_intval(GMP|string|int $num): int {}

/**
 * Sets the RNG seed
 * @param resource|string|int|GMP $seed <p>
 * The seed to be set for the {@see gmp_random()}, {@see gmp_random_bits()}, and {@see gmp_random_range()} functions.
 * </p>
 * Either a GMP number resource in PHP 5.5 and earlier, a GMP object in PHP 5.6 and later, or a numeric string provided that it is possible to convert the latter to a number.
 * @return void|null|false Returns NULL on success.
 * @since 7.0
 */
function gmp_random_seed(GMP|string|int $seed): void {}
/**
 * Convert GMP number to string
 * @link https://php.net/manual/en/function.gmp-strval.php
 * @param resource|int|string|GMP $num <p>
 * The GMP number that will be converted to a string.
 * </p>
 * <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @param int $base [optional] <p>
 * The base of the returned number. The default base is 10.
 * Allowed values for the base are from 2 to 62 and -2 to -36.
 * </p>
 * @return string The number, as a string.
 */
#[Pure]
function gmp_strval(GMP|string|int $num, int $base = 10): string {}

/**
 * Add numbers
 * @link https://php.net/manual/en/function.gmp-add.php
 * @param resource|int|string|GMP $num1 <p>
 * A number that will be added.
 * </p>
 * <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @param resource|string|GMP $num2 <p>
 * A number that will be added.
 * </p>
 * <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @return resource|GMP A GMP number representing the sum of the arguments.
 */
#[Pure]
function gmp_add(GMP|string|int $num1, GMP|string|int $num2): GMP {}

/**
 * Subtract numbers
 * @link https://php.net/manual/en/function.gmp-sub.php
 * @param resource|string|GMP $num1 <p>
 * The number being subtracted from.
 * </p>
 * <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @param resource|string|GMP $num2 <p>
 * The number subtracted from <i>a</i>.
 * </p>
 * <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @return resource|GMP A GMP number resource.
 */
#[Pure]
function gmp_sub(GMP|string|int $num1, GMP|string|int $num2): GMP {}

/**
 * Multiply numbers
 * @link https://php.net/manual/en/function.gmp-mul.php
 * @param resource|string|GMP $num1 <p>
 * A number that will be multiplied by <i>b</i>.
 * </p>
 * <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @param resource|string|GMP $num2 <p>
 * A number that will be multiplied by <i>a</i>.
 * </p>
 * <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @return resource|GMP A GMP number resource.
 */
#[Pure]
function gmp_mul(GMP|string|int $num1, GMP|string|int $num2): GMP {}

/**
 * Divide numbers and get quotient and remainder
 * @link https://php.net/manual/en/function.gmp-div-qr.php
 * @param resource|string|GMP $num1 <p>
 * The number being divided.
 * </p>
 * <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @param resource|string|GMP $num2 <p>
 * The number that <i>n</i> is being divided by.
 * </p>
 * <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @param int $rounding_mode [optional] <p>
 * See the <b>gmp_div_q</b> function for description
 * of the <i>round</i> argument.
 * </p>
 * @return array an array, with the first
 * element being [n/d] (the integer result of the
 * division) and the second being (n - [n/d] * d)
 * (the remainder of the division).
 */
#[Pure]
function gmp_div_qr(GMP|string|int $num1, GMP|string|int $num2, int $rounding_mode = GMP_ROUND_ZERO): array {}

/**
 * Divide numbers
 * @link https://php.net/manual/en/function.gmp-div-q.php
 * @param resource|string|GMP $num1 <p>
 * The number being divided.
 * </p>
 * <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @param resource|string|GMP $num2 <p>
 * The number that <i>a</i> is being divided by.
 * </p>
 * <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @param int $rounding_mode [optional] <p>
 * The result rounding is defined by the
 * <i>round</i>, which can have the following
 * values:
 * <b>GMP_ROUND_ZERO</b>: The result is truncated
 * towards 0.</p>
 * @return resource|GMP A GMP number resource.
 */
#[Pure]
function gmp_div_q(GMP|string|int $num1, GMP|string|int $num2, int $rounding_mode = GMP_ROUND_ZERO): GMP {}

/**
 * Remainder of the division of numbers
 * @link https://php.net/manual/en/function.gmp-div-r.php
 * @param resource|string|GMP $num1 <p>
 * The number being divided.
 * </p>
 * <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @param resource|string|GMP $num2 <p>
 * The number that <i>n</i> is being divided by.
 * </p>
 * <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @param int $rounding_mode [optional] <p>
 * See the <b>gmp_div_q</b> function for description
 * of the <i>round</i> argument.
 * </p>
 * @return resource|GMP The remainder, as a GMP number.
 */
#[Pure]
function gmp_div_r(GMP|string|int $num1, GMP|string|int $num2, int $rounding_mode = GMP_ROUND_ZERO): GMP {}

/**
 * Divide numbers
 * @link https://php.net/manual/en/function.gmp-div-q.php
 * @param resource|string|GMP $num1 <p>
 * The number being divided.
 * </p>
 * <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @param resource|string|GMP $num2 <p>
 * The number that <i>a</i> is being divided by.
 * </p>
 * <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @param int $rounding_mode [optional] <p>
 * The result rounding is defined by the
 * <i>round</i>, which can have the following
 * values:
 * <b>GMP_ROUND_ZERO</b>: The result is truncated
 * towards 0.</p>
 * @return resource|GMP A GMP number resource.
 */
#[Pure]
function gmp_div(GMP|string|int $num1, GMP|string|int $num2, int $rounding_mode = GMP_ROUND_ZERO): GMP {}

/**
 * Modulo operation
 * @link https://php.net/manual/en/function.gmp-mod.php
 * @param resource|string|GMP $num1 <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @param resource|string|GMP $num2 <p>
 * The modulo that is being evaluated.
 * </p>
 * <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @return resource|GMP A GMP number resource.
 */
#[Pure]
function gmp_mod(GMP|string|int $num1, GMP|string|int $num2): GMP {}

/**
 * Exact division of numbers
 * @link https://php.net/manual/en/function.gmp-divexact.php
 * @param resource|string|GMP $num1 <p>
 * The number being divided.
 * </p>
 * <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @param resource|string|GMP $num2 <p>
 * The number that <i>a</i> is being divided by.
 * </p>
 * <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @return resource|GMP A GMP number resource.
 */
#[Pure]
function gmp_divexact(GMP|string|int $num1, GMP|string|int $num2): GMP {}

/**
 * Negate number
 * @link https://php.net/manual/en/function.gmp-neg.php
 * @param resource|string|GMP $num <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @return resource|GMP -<i>a</i>, as a GMP number.
 */
#[Pure]
function gmp_neg(GMP|string|int $num): GMP {}

/**
 * Absolute value
 * @link https://php.net/manual/en/function.gmp-abs.php
 * @param resource|string|GMP $num <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @return resource|GMP the absolute value of <i>a</i>, as a GMP number.
 */
#[Pure]
function gmp_abs(GMP|string|int $num): GMP {}

/**
 * Factorial
 * @link https://php.net/manual/en/function.gmp-fact.php
 * @param resource|string|GMP $num <p>
 * The factorial number.
 * </p>
 * <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @return resource|GMP A GMP number resource.
 */
#[Pure]
function gmp_fact(GMP|string|int $num): GMP {}

/**
 * Calculate square root
 * @link https://php.net/manual/en/function.gmp-sqrt.php
 * @param resource|string|GMP $num <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @return resource|GMP The integer portion of the square root, as a GMP number.
 */
#[Pure]
function gmp_sqrt(GMP|string|int $num): GMP {}

/**
 * Square root with remainder
 * @link https://php.net/manual/en/function.gmp-sqrtrem.php
 * @param resource|string|GMP $num <p>
 * The number being square rooted.
 * </p>
 * <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @return array array where first element is the integer square root of
 * <i>a</i> and the second is the remainder
 * (i.e., the difference between <i>a</i> and the
 * first element squared).
 */
#[Pure]
function gmp_sqrtrem(GMP|string|int $num): array {}

/**
 * Raise number into power
 * @link https://php.net/manual/en/function.gmp-pow.php
 * @param resource|string|GMP $num <p>
 * The base number.
 * </p>
 * <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @param positive-int $exponent <p>
 * The positive power to raise the <i>base</i>.
 * </p>
 * @return resource|GMP The new (raised) number, as a GMP number. The case of
 * 0^0 yields 1.
 */
#[Pure]
function gmp_pow(GMP|string|int $num, int $exponent): GMP {}

/**
 * Raise number into power with modulo
 * @link https://php.net/manual/en/function.gmp-powm.php
 * @param resource|string|GMP $num <p>
 * The base number.
 * </p>
 * <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @param resource|string|GMP $exponent <p>
 * The positive power to raise the <i>base</i>.
 * </p>
 * <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @param resource|string|GMP $modulus <p>
 * The modulo.
 * </p>
 * <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @return resource|GMP The new (raised) number, as a GMP number.
 */
#[Pure]
function gmp_powm(GMP|string|int $num, GMP|string|int $exponent, GMP|string|int $modulus): GMP {}

/**
 * Perfect square check
 * @link https://php.net/manual/en/function.gmp-perfect-square.php
 * @param resource|string|GMP $num <p>
 * The number being checked as a perfect square.
 * </p>
 * <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @return bool <b>TRUE</b> if <i>a</i> is a perfect square,
 * <b>FALSE</b> otherwise.
 */
#[Pure]
function gmp_perfect_square(GMP|string|int $num): bool {}

/**
 * Check if number is "probably prime"
 * @link https://php.net/manual/en/function.gmp-prob-prime.php
 * @param resource|string|GMP $num <p>
 * The number being checked as a prime.
 * </p>
 * <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @param int $repetitions [optional] <p>
 * Reasonable values
 * of <i>reps</i> vary from 5 to 10 (default being
 * 10); a higher value lowers the probability for a non-prime to
 * pass as a "probable" prime.
 * </p>
 * <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @return int If this function returns 0, <i>a</i> is
 * definitely not prime. If it returns 1, then
 * <i>a</i> is "probably" prime. If it returns 2,
 * then <i>a</i> is surely prime.
 */
#[Pure]
function gmp_prob_prime(GMP|string|int $num, int $repetitions = 10): int {}

/**
 * Random number
 * @link https://php.net/manual/en/function.gmp-random-bits.php
 * @param int $bits <p>The number of bits. Either a GMP number resource in PHP 5.5 and earlier,
 * a GMP object in PHP 5.6 and later,
 * or a numeric string provided that it is possible to convert the latter to a number.</p>
 * @return GMP A random GMP number.
 */
function gmp_random_bits(int $bits): GMP {}

/**
 * Random number
 * @link https://php.net/manual/en/function.gmp-random-range.php
 * @param GMP|string|int $min <p>A GMP number representing the lower bound for the random number</p>
 * @param GMP|string|int $max <p>A GMP number representing the upper bound for the random number</p>
 * @return GMP A random GMP number.
 */
function gmp_random_range(GMP|string|int $min, GMP|string|int $max): GMP {}

/**
 * Calculate GCD
 * @link https://php.net/manual/en/function.gmp-gcd.php
 * @param resource|string|GMP $num1 <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @param resource|string|GMP $num2 <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @return resource|GMP A positive GMP number that divides into both
 * <i>a</i> and <i>b</i>.
 */
#[Pure]
function gmp_gcd(GMP|string|int $num1, GMP|string|int $num2): GMP {}

/**
 * Calculate GCD and multipliers
 * @link https://php.net/manual/en/function.gmp-gcdext.php
 * @param resource|string|GMP $num1 <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @param resource|string|GMP $num2 <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @return array An array of GMP numbers.
 */
#[Pure]
#[ArrayShape(["g" => "mixed", "s" => "mixed", "t" => "mixed"])]
function gmp_gcdext(GMP|string|int $num1, GMP|string|int $num2): array {}

/**
 * Inverse by modulo
 * @link https://php.net/manual/en/function.gmp-invert.php
 * @param resource|string|GMP $num1 <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @param resource|string|GMP $num2 <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @return resource|GMP|false A GMP number on success or <b>FALSE</b> if an inverse does not exist.
 */
#[Pure]
function gmp_invert(GMP|string|int $num1, GMP|string|int $num2): GMP|false {}

/**
 * Jacobi symbol
 * @link https://php.net/manual/en/function.gmp-jacobi.php
 * @param resource|string|GMP $num1 <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @param resource|string|GMP $num2 <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * <p>
 * Should be odd and must be positive.
 * </p>
 * @return int A GMP number resource.
 */
#[Pure]
function gmp_jacobi(GMP|string|int $num1, GMP|string|int $num2): int {}

/**
 * Legendre symbol
 * @link https://php.net/manual/en/function.gmp-legendre.php
 * @param resource|string|GMP $num1 <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @param resource|string|GMP $num2 <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * <p>
 * Should be odd and must be positive.
 * </p>
 * @return int A GMP number resource.
 */
#[Pure]
function gmp_legendre(GMP|string|int $num1, GMP|string|int $num2): int {}

/**
 * Compare numbers
 * @link https://php.net/manual/en/function.gmp-cmp.php
 * @param resource|string|GMP $num1 <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @param resource|string|GMP $num2 <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @return int a positive value if a &gt; b, zero if
 * a = b and a negative value if a &lt;
 * b.
 */
#[Pure]
function gmp_cmp(GMP|string|int $num1, GMP|string|int $num2): int {}

/**
 * Sign of number
 * @link https://php.net/manual/en/function.gmp-sign.php
 * @param resource|string|GMP $num <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @return int 1 if <i>a</i> is positive,
 * -1 if <i>a</i> is negative,
 * and 0 if <i>a</i> is zero.
 */
#[Pure]
function gmp_sign(GMP|string|int $num): int {}

/**
 * Random number
 * @link https://php.net/manual/en/function.gmp-random.php
 * @param int $limiter [optional] <p>
 * The limiter.
 * </p>
 * <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @return resource|GMP A random GMP number.
 * @see gmp_random_bits()
 * @see gmp_random_range()
 * @removed 8.0
 */
#[Deprecated(reason: "Use see gmp_random_bits() or see gmp_random_range() instead", since: "7.2")]
function gmp_random($limiter = 20) {}

/**
 * Bitwise AND
 * @link https://php.net/manual/en/function.gmp-and.php
 * @param resource|string|GMP $num1 <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @param resource|string|GMP $num2 <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @return resource|GMP A GMP number representing the bitwise AND comparison.
 */
#[Pure]
function gmp_and(GMP|string|int $num1, GMP|string|int $num2): GMP {}

/**
 * Bitwise OR
 * @link https://php.net/manual/en/function.gmp-or.php
 * @param resource|string|GMP $num1 <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @param resource|string|GMP $num2 <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @return resource|GMP A GMP number resource.
 */
#[Pure]
function gmp_or(GMP|string|int $num1, GMP|string|int $num2): GMP {}

/**
 * Calculates one's complement
 * @link https://php.net/manual/en/function.gmp-com.php
 * @param resource|string|GMP $num <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @return resource|GMP the one's complement of <i>a</i>, as a GMP number.
 */
#[Pure]
function gmp_com(GMP|string|int $num): GMP {}

/**
 * Bitwise XOR
 * @link https://php.net/manual/en/function.gmp-xor.php
 * @param resource|string|GMP $num1 <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @param resource|string|GMP $num2 <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @return resource|GMP A GMP number resource.
 */
#[Pure]
function gmp_xor(GMP|string|int $num1, GMP|string|int $num2): GMP {}

/**
 * Set bit
 * @link https://php.net/manual/en/function.gmp-setbit.php
 * @param resource|string|GMP $num <p>
 * The number being set to.
 * </p>
 * <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @param int $index <p>
 * The set bit.
 * </p>
 * @param bool $value [optional] <p>
 * Defines if the bit is set to 0 or 1. By default the bit is set to
 * 1. Index starts at 0.
 * </p>
 * @return void A GMP number resource.
 */
function gmp_setbit(GMP $num, int $index, bool $value = true): void {}

/**
 * Clear bit
 * @link https://php.net/manual/en/function.gmp-clrbit.php
 * @param resource|string|GMP $num <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @param int $index <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @return void A GMP number resource.
 */
function gmp_clrbit(GMP $num, int $index): void {}

/**
 * Scan for 0
 * @link https://php.net/manual/en/function.gmp-scan0.php
 * @param resource|string|GMP $num1 <p>
 * The number to scan.
 * </p>
 * <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @param int $start <p>
 * The starting bit.
 * </p>
 * @return int the index of the found bit, as an integer. The
 * index starts from 0.
 */
#[Pure]
function gmp_scan0(GMP|string|int $num1, int $start): int {}

/**
 * Scan for 1
 * @link https://php.net/manual/en/function.gmp-scan1.php
 * @param resource|string|GMP $num1 <p>
 * The number to scan.
 * </p>
 * <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @param int $start <p>
 * The starting bit.
 * </p>
 * @return int the index of the found bit, as an integer.
 * If no set bit is found, -1 is returned.
 */
#[Pure]
function gmp_scan1(GMP|string|int $num1, int $start): int {}

/**
 * Tests if a bit is set
 * @link https://php.net/manual/en/function.gmp-testbit.php
 * @param resource|string|GMP $num <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @param int $index <p>
 * The bit to test
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
#[Pure]
function gmp_testbit(GMP|string|int $num, int $index): bool {}

/**
 * Population count
 * @link https://php.net/manual/en/function.gmp-popcount.php
 * @param resource|string|GMP $num <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @return int The population count of <i>a</i>, as an integer.
 */
#[Pure]
function gmp_popcount(GMP|string|int $num): int {}

/**
 * Hamming distance
 * @link https://php.net/manual/en/function.gmp-hamdist.php
 * @param resource|string|GMP $num1 <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * <p>
 * It should be positive.
 * </p>
 * @param resource|string|GMP $num2 <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * <p>
 * It should be positive.
 * </p>
 * @return int A GMP number resource.
 */
#[Pure]
function gmp_hamdist(GMP|string|int $num1, GMP|string|int $num2): int {}

/**
 * Import from a binary string
 * @link https://php.net/manual/en/function.gmp-import.php
 * @param string $data The binary string being imported
 * @param int $word_size <p>Default value is 1. The number of bytes in each chunk of binary
 * data. This is mainly used in conjunction with the options parameter.</p>
 * @param int $flags Default value is GMP_MSW_FIRST | GMP_NATIVE_ENDIAN.
 * @return GMP|false Returns a GMP number or FALSE on failure.
 * @since 5.6.1
 */
#[Pure]
function gmp_import(string $data, int $word_size = 1, int $flags = GMP_MSW_FIRST|GMP_NATIVE_ENDIAN): GMP {}

/**
 * Export to a binary string
 * @link https://php.net/manual/en/function.gmp-export.php
 * @param GMP|string|int $num The GMP number being exported
 * @param int $word_size <p>Default value is 1. The number of bytes in each chunk of binary
 * data. This is mainly used in conjunction with the options parameter.</p>
 * @param int $flags Default value is GMP_MSW_FIRST | GMP_NATIVE_ENDIAN.
 * @return string|false Returns a string or FALSE on failure.
 * @since 5.6.1
 */
#[Pure]
function gmp_export(GMP|string|int $num, int $word_size = 1, int $flags = GMP_MSW_FIRST|GMP_NATIVE_ENDIAN): string {}

/**
 * Takes the nth root of a and returns the integer component of the result.
 * @link https://php.net/manual/en/function.gmp-root.php
 * @param GMP|string|int $num <p>Either a GMP number resource in PHP 5.5 and earlier, a GMP object in PHP 5.6
 * and later, or a numeric string provided that it is possible to convert the latter to a number.</p>
 * @param positive-int $nth The positive root to take of a <b>num</b>.
 * @return GMP The integer component of the resultant root, as a GMP number.
 * @since 5.6
 */
#[Pure]
function gmp_root(GMP|string|int $num, int $nth): GMP {}

/**
 * Takes the nth root of a and returns the integer component and remainder of the result.
 * @link https://php.net/manual/en/function.gmp-rootrem.php
 * @param GMP|string|int $num <p>Either a GMP number resource in PHP 5.5 and earlier, a GMP object in PHP 5.6
 * and later, or a numeric string provided that it is possible to convert the latter to a number.</p>
 * @param positive-int $nth The positive root to take of a <b>num</b>.
 * @return array|GMP[] <p>A two element array, where the first element is the integer component of
 * the root, and the second element is the remainder, both represented as GMP numbers.</p>
 * @since 5.6
 */
#[Pure]
function gmp_rootrem(GMP|string|int $num, int $nth): array {}

/**
 * Find next prime number
 * @link https://php.net/manual/en/function.gmp-nextprime.php
 * @param int|resource|string|GMP $num <p>It can be either a GMP number resource, or a
 * numeric string given that it is possible to convert the latter to a number.</p>
 * @return resource|GMP Return the next prime number greater than <i>a</i>,
 * as a GMP number.
 */
#[Pure]
function gmp_nextprime(GMP|string|int $num): GMP {}

/**
 * Calculates binomial coefficient
 *
 * @link https://www.php.net/manual/en/function.gmp-binomial.php
 *
 * @param GMP|string|float|int $n
 * @param int $k
 * @return GMP|false
 *
 * @since 7.3
 */
#[Pure]
function gmp_binomial(GMP|string|int $n, int $k): GMP {}

/**
 * Computes the Kronecker symbol
 *
 * @link https://www.php.net/manual/en/function.gmp-kronecker.php
 *
 * @param GMP|string|float|int $num1
 * @param GMP|string|float|int $num2
 * @return int
 *
 * @since 7.3
 */
#[Pure]
function gmp_kronecker(GMP|string|int $num1, GMP|string|int $num2): int {}

/**
 * Computes the least common multiple of A and B
 *
 * @link https://www.php.net/manual/en/function.gmp-lcm.php
 *
 * @param GMP|string|float|int $num1
 * @param GMP|string|float|int $num2
 * @return GMP
 *
 * @since 7.3
 */
#[Pure]
function gmp_lcm(GMP|string|int $num1, GMP|string|int $num2): GMP {}

/**
 * Perfect power check
 *
 * @link https://www.php.net/manual/en/function.gmp-perfect-power.php
 *
 * @param GMP|string|float|int $num
 * @return bool
 *
 * @since 7.3
 */
#[Pure]
function gmp_perfect_power(GMP|string|int $num): bool {}

define('GMP_ROUND_ZERO', 0);
define('GMP_ROUND_PLUSINF', 1);
define('GMP_ROUND_MINUSINF', 2);
define('GMP_MSW_FIRST', 1);
define('GMP_LSW_FIRST', 2);
define('GMP_LITTLE_ENDIAN', 4);
define('GMP_BIG_ENDIAN', 8);
define('GMP_NATIVE_ENDIAN', 16);

/**
 * The GMP library version
 * @link https://php.net/manual/en/gmp.constants.php
 */
define('GMP_VERSION', "6.2.1");

define('GMP_MPIR_VERSION', '3.0.0');

class GMP implements Serializable
{
    /**
     * String representation of object
     * @link https://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     */
    public function serialize() {}

    public function __serialize(): array {}

    /**
     * Constructs the object
     * @link https://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     */
    public function unserialize($serialized) {}

    public function __unserialize(array $data): void {}
}
// End of gmp v.
