<?php

/**
 * SCSSPHP
 *
 * @copyright 2018-2020 Anthon Pang
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 * @link http://scssphp.github.io/scssphp
 */

namespace ScssPhp\ScssPhp\Util;

use ScssPhp\ScssPhp\Value\SassNumber;

/**
 * Utilities to deal with numbers with fuzziness for the Sass precision
 *
 * @internal
 */
final class NumberUtil
{
    /**
     * The power of ten to which to round Sass numbers to determine if they're
     * fuzzy equal to one another
     *
     * This is also the minimum distance such that `a - b > EPSILON` implies that
     * `a` isn't fuzzy-equal to `b`. Note that the inverse implication is not
     * necessarily true! For example, if `a = 5.1e-11` and `b = 4.4e-11`, then
     * `a - b < 1e-11` but `a` fuzzy-equals 5e-11 and b fuzzy-equals 4e-11.
     *
     * @see https://github.com/sass/sass/blob/main/spec/types/number.md#fuzzy-equality
     */
    private const EPSILON = 10 ** (-SassNumber::PRECISION - 1);
    private const INVERSE_EPSILON = 10 ** (SassNumber::PRECISION + 1);

    public static function clamp(float $value, float $lowerLimit, float $upperLimit): float
    {
        if ($value < $lowerLimit) {
            return $lowerLimit;
        }

        if ($value > $upperLimit) {
            return $upperLimit;
        }

        return $value;
    }

    public static function fuzzyEquals(float $number1, float $number2): bool
    {
        if ($number1 == $number2) {
            return true;
        }
        return abs($number1 - $number2) <= self::EPSILON && round($number1 * self::INVERSE_EPSILON) === round($number2 * self::INVERSE_EPSILON);
    }

    public static function fuzzyLessThan(float $number1, float $number2): bool
    {
        return $number1 < $number2 && !self::fuzzyEquals($number1, $number2);
    }

    public static function fuzzyLessThanOrEquals(float $number1, float $number2): bool
    {
        return $number1 <= $number2 || self::fuzzyEquals($number1, $number2);
    }

    public static function fuzzyGreaterThan(float $number1, float $number2): bool
    {
        return $number1 > $number2 && !self::fuzzyEquals($number1, $number2);
    }

    public static function fuzzyGreaterThanOrEquals(float $number1, float $number2): bool
    {
        return $number1 >= $number2 || self::fuzzyEquals($number1, $number2);
    }

    public static function fuzzyIsInt(float $number): bool
    {
        if (is_infinite($number) || is_nan($number)) {
            return false;
        }

        return self::fuzzyEquals($number, round($number));
    }

    public static function fuzzyAsInt(float $number): ?int
    {
        if (is_infinite($number) || is_nan($number)) {
            return null;
        }

        if ($number > \PHP_INT_MAX || $number < \PHP_INT_MIN) {
            return null;
        }

        $rounded = (int) round($number);

        return self::fuzzyEquals($number, $rounded) ? $rounded : null;
    }

    public static function fuzzyRound(float $number): int
    {
        if ($number > 0) {
            return intval(self::fuzzyLessThan(fmod($number, 1), 0.5) ? floor($number) : ceil($number));
        }

        return intval(self::fuzzyLessThanOrEquals(fmod($number, 1), 0.5) ? floor($number) : ceil($number));
    }

    public static function fuzzyCheckRange(float $number, float $min, float $max): ?float
    {
        if (self::fuzzyEquals($number, $min)) {
            return $min;
        }

        if (self::fuzzyEquals($number, $max)) {
            return $max;
        }

        if ($number > $min && $number < $max) {
            return $number;
        }

        return null;
    }

    /**
     * @throws \OutOfRangeException
     */
    public static function fuzzyAssertRange(float $number, float $min, float $max, ?string $name = null): float
    {
        $result = self::fuzzyCheckRange($number, $min, $max);

        if (!\is_null($result)) {
            return $result;
        }

        $nameDisplay = $name ? " $name" : '';

        throw new \OutOfRangeException("Invalid value:$nameDisplay must be between $min and $max: $number.");
    }

    /**
     * Returns $num1 / $num2, using Sass's division semantic.
     *
     * Sass allows dividing by 0.
     */
    public static function divideLikeSass(float $num1, float $num2): float
    {
        if ($num2 == 0) {
            if ($num1 == 0) {
                return NAN;
            }

            if ($num1 > 0) {
                return INF;
            }

            return -INF;
        }

        return $num1 / $num2;
    }

    /**
     * Return $num1 modulo $num2, using Sass's [floored division] modulo
     * semantics, which it inherited from Ruby and which differ from Dart's.
     *
     * [floored division]: https://en.wikipedia.org/wiki/Modulo_operation#Variants_of_the_definition
     */
    public static function moduloLikeSass(float $num1, float $num2): float
    {
        if (is_infinite($num1)) {
            return NAN;
        }

        if (is_infinite($num2)) {
            return self::signIncludingZero($num1) === self::sign($num2) ? $num1 : NAN;
        }

        if ($num2 == 0) {
            return NAN;
        }

        $result = fmod($num1, $num2);

        if ($result == 0) {
            return 0;
        }

        // PHP's fdiv has a different semantic when the 2 numbers have a different sign.
        if ($num2 < 0 xor $num1 < 0) {
            $result += $num2;
        }

        return $result;
    }

    public static function sqrt(SassNumber $number): SassNumber
    {
        $number->assertNoUnits('number');

        return SassNumber::create(sqrt($number->getValue()));
    }

    public static function sin(SassNumber $number): SassNumber
    {
        return SassNumber::create(sin($number->coerceValueToUnit('rad', 'number')));
    }

    public static function cos(SassNumber $number): SassNumber
    {
        return SassNumber::create(cos($number->coerceValueToUnit('rad', 'number')));
    }

    public static function tan(SassNumber $number): SassNumber
    {
        return SassNumber::create(tan($number->coerceValueToUnit('rad', 'number')));
    }

    public static function atan(SassNumber $number): SassNumber
    {
        $number->assertNoUnits('number');
        return self::radiansToDegrees(atan($number->getValue()));
    }

    public static function asin(SassNumber $number): SassNumber
    {
        $number->assertNoUnits('number');
        return self::radiansToDegrees(asin($number->getValue()));
    }

    public static function acos(SassNumber $number): SassNumber
    {
        $number->assertNoUnits('number');
        return self::radiansToDegrees(acos($number->getValue()));
    }

    public static function abs(SassNumber $number): SassNumber
    {
        return SassNumber::create(abs($number->getValue()))->coerceToMatch($number);
    }

    public static function log(SassNumber $number, ?SassNumber $base): SassNumber
    {
        if ($base !== null) {
            return SassNumber::create(self::divideLikeSass(log($number->getValue()), log($base->getValue())));
        }

        return SassNumber::create(log($number->getValue()));
    }

    public static function pow(SassNumber $base, SassNumber $exponent): SassNumber
    {
        $base->assertNoUnits('base');
        $exponent->assertNoUnits('exponent');

        if (\PHP_VERSION_ID >= 80400) {
            $value = fpow($base->getValue(), $exponent->getValue());
        } else {
            $value = $base->getValue() ** $exponent->getValue();
        }

        return SassNumber::create($value);
    }

    public static function atan2(SassNumber $y, SassNumber $x): SassNumber
    {
        return self::radiansToDegrees(atan2($y->getValue(), $x->convertValueToMatch($y, 'x', 'y')));
    }

    private static function radiansToDegrees(float $radians): SassNumber
    {
        return SassNumber::withUnits($radians * (180 / \M_PI), ['deg']);
    }

    public static function sign(float $num): int
    {
        if ($num > 0) {
            return 1;
        }

        if ($num < 0) {
            return -1;
        }

        return 0;
    }

    public static function signIncludingZero(float $num): int
    {
        // In PHP, negative 0 and positive 0 are equal even for strict equality, so we need a different detection
        if ($num === 0.0) {
            if ('-0' === (string) $num) {
                return -1;
            }

            return 1;
        }

        return self::sign($num);
    }
}
