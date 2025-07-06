<?php

/**
 * SCSSPHP
 *
 * @copyright 2012-2020 Leaf Corcoran
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 * @link http://scssphp.github.io/scssphp
 */

namespace ScssPhp\ScssPhp\Function;

use ScssPhp\ScssPhp\Deprecation;
use ScssPhp\ScssPhp\Exception\SassScriptException;
use ScssPhp\ScssPhp\Value\SassBoolean;
use ScssPhp\ScssPhp\Value\SassNull;
use ScssPhp\ScssPhp\Value\SassNumber;
use ScssPhp\ScssPhp\Value\SassString;
use ScssPhp\ScssPhp\Value\Value;
use ScssPhp\ScssPhp\Warn;

/**
 * @internal
 */
final class MathFunctions
{
    /**
     * @param list<Value> $arguments
     */
    public static function abs(array $arguments): Value
    {
        $number = $arguments[0]->assertNumber('number');
        // TODO implement the deprecation for the % unit once modules are implemented to provided the replacement

        return SassNumber::withUnits(abs($number->getValue()), $number->getNumeratorUnits(), $number->getDenominatorUnits());
    }

    /**
     * @param list<Value> $arguments
     */
    public static function ceil(array $arguments): Value
    {
        return self::numberFunction($arguments, ceil(...));
    }

    /**
     * @param list<Value> $arguments
     */
    public static function floor(array $arguments): Value
    {
        return self::numberFunction($arguments, floor(...));
    }

    /**
     * @param list<Value> $arguments
     */
    public static function max(array $arguments): Value
    {
        $max = null;

        foreach ($arguments[0]->asList() as $value) {
            $number = $value->assertNumber();

            if ($max === null || $max->lessThan($number)->isTruthy()) {
                $max = $number;
            }
        }

        if ($max !== null) {
            return $max;
        }

        throw new SassScriptException('At least one argument must be passed.');
    }

    /**
     * @param list<Value> $arguments
     */
    public static function min(array $arguments): Value
    {
        $min = null;

        foreach ($arguments[0]->asList() as $value) {
            $number = $value->assertNumber();

            if ($min === null || $min->greaterThan($number)->isTruthy()) {
                $min = $number;
            }
        }

        if ($min !== null) {
            return $min;
        }

        throw new SassScriptException('At least one argument must be passed.');
    }

    /**
     * @param list<Value> $arguments
     */
    public static function round(array $arguments): Value
    {
        return self::numberFunction($arguments, round(...));
    }

    /**
     * @param list<Value> $arguments
     */
    public static function compatible(array $arguments): Value
    {
        $number1 = $arguments[0]->assertNumber('number1');
        $number2 = $arguments[1]->assertNumber('number2');

        return SassBoolean::create($number1->isComparableTo($number2));
    }

    /**
     * @param list<Value> $arguments
     */
    public static function isUnitless(array $arguments): Value
    {
        $number = $arguments[0]->assertNumber('number');

        return SassBoolean::create(!$number->hasUnits());
    }

    /**
     * @param list<Value> $arguments
     */
    public static function unit(array $arguments): Value
    {
        $number = $arguments[0]->assertNumber('number');

        return new SassString($number->getUnitString(), true);
    }

    /**
     * @param list<Value> $arguments
     */
    public static function percentage(array $arguments): Value
    {
        $number = $arguments[0]->assertNumber('number');
        $number->assertNoUnits('number');

        return SassNumber::create($number->getValue() * 100, '%');
    }

    /**
     * @param list<Value> $arguments
     */
    public static function random(array $arguments): Value
    {
        if ($arguments[0] instanceof SassNull) {
            // TODO use a better algorithm to generate a random float.
            $max = mt_getrandmax();

            return SassNumber::create(mt_rand(0, $max - 1) / $max);
        }

        $limit = $arguments[0]->assertNumber('limit');

        if ($limit->hasUnits()) {
            $unitString = $limit->getUnitString();

            // TODO update the message when implementing modules and deprecating division.
            Warn::forDeprecation(
                <<<TXT
                random() will no longer ignore \$limit units ($limit) in a future release.

                Recommendation: random(\$limit / 1$unitString) * 1$unitString

                To preserve current behavior: random(\$limit / 1$unitString)

                More info: https://sass-lang.com/d/function-units
                TXT,
                Deprecation::functionUnits
            );
        }

        $limitScalar = $limit->assertInt('limit');
        if ($limitScalar < 1) {
            throw new SassScriptException("\$limit: Must be greater than 0, was $limit.");
        }

        return SassNumber::create(mt_rand(1, $limitScalar));
    }

    /**
     * Implements a callable that transforms a number's value
     * using $transform and preserves its units.
     *
     * @param list<Value> $arguments
     * @param callable(float): float $transform
     *
     * @param-immediately-invoked-callable $transform
     */
    private static function numberFunction(array $arguments, callable $transform): Value
    {
        $number = $arguments[0]->assertNumber('number');

        return SassNumber::withUnits($transform($number->getValue()), $number->getNumeratorUnits(), $number->getDenominatorUnits());
    }
}
