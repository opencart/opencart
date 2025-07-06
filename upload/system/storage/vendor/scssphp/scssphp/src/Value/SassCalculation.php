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

namespace ScssPhp\ScssPhp\Value;

use ScssPhp\ScssPhp\Deprecation;
use ScssPhp\ScssPhp\Exception\SassScriptException;
use ScssPhp\ScssPhp\Util\Character;
use ScssPhp\ScssPhp\Util\Equatable;
use ScssPhp\ScssPhp\Util\NumberUtil;
use ScssPhp\ScssPhp\Util\StringUtil;
use ScssPhp\ScssPhp\Visitor\ValueVisitor;
use ScssPhp\ScssPhp\Warn;

/**
 * A SassScript calculation.
 *
 * Although calculations can in principle have any name or any number of
 * arguments, this class only exposes the specific calculations that are
 * supported by the Sass spec. This ensures that all calculations that the user
 * works with are always fully simplified.
 */
final class SassCalculation extends Value
{
    /**
     * The calculation's name, such as `"calc"`.
     */
    private readonly string $name;

    /**
     * The calculation's arguments.
     *
     * Each argument is either a {@see SassNumber}, a {@see SassCalculation}, an unquoted
     * {@see SassString}, or a {@see CalculationOperation}.
     *
     * @var list<object>
     */
    private readonly array $arguments;

    /**
     * Creates a new calculation with the given $name and $arguments
     * that will not be simplified.
     *
     * @param list<object> $arguments
     *
     * @internal
     */
    public static function unsimplified(string $name, array $arguments): SassCalculation
    {
        return new SassCalculation($name, $arguments);
    }

    /**
     * Creates a `calc()` calculation with the given $argument.
     *
     * The $argument must be either a {@see SassNumber}, a {@see SassCalculation}, an
     * unquoted {@see SassString}, or a {@see CalculationOperation}.
     *
     * This automatically simplifies the calculation, so it may return a
     * {@see SassNumber} rather than a {@see SassCalculation}. It throws an exception if it
     * can determine that the calculation will definitely produce invalid CSS.
     *
     * @throws SassScriptException
     */
    public static function calc(object $argument): Value
    {
        $argument = self::simplify($argument);

        if ($argument instanceof SassNumber) {
            return $argument;
        }

        if ($argument instanceof SassCalculation) {
            return $argument;
        }

        return new SassCalculation('calc', [$argument]);
    }

    /**
     * Creates a `min()` calculation with the given $arguments.
     *
     * Each argument must be either a {@see SassNumber}, a {@see SassCalculation}, an
     * unquoted {@see SassString}, or a {@see CalculationOperation}. It must be passed at
     * least one argument.
     *
     * This automatically simplifies the calculation, so it may return a
     * {@see SassNumber} rather than a {@see SassCalculation}. It throws an exception if it
     * can determine that the calculation will definitely produce invalid CSS.
     *
     * @param list<object> $arguments
     *
     * @throws SassScriptException
     */
    public static function min(array $arguments): Value
    {
        $args = self::simplifyArguments($arguments);

        if (!$args) {
            throw new \InvalidArgumentException('min() must have at least one argument.');
        }

        /** @var SassNumber|null $minimum */
        $minimum = null;

        foreach ($args as $arg) {
            if (!$arg instanceof SassNumber || $minimum !== null && !$minimum->isComparableTo($arg)) {
                $minimum = null;
                break;
            }

            if ($minimum === null || $minimum->greaterThan($arg)->isTruthy()) {
                $minimum = $arg;
            }
        }

        if ($minimum !== null) {
            return $minimum;
        }

        self::verifyCompatibleNumbers($args);

        return new SassCalculation('min', $args);
    }

    /**
     * Creates a `max()` calculation with the given $arguments.
     *
     * Each argument must be either a {@see SassNumber}, a {@see SassCalculation}, an
     * unquoted {@see SassString}, or a {@see CalculationOperation}. It must be passed at
     * least one argument.
     *
     * This automatically simplifies the calculation, so it may return a
     * {@see SassNumber} rather than a {@see SassCalculation}. It throws an exception if it
     * can determine that the calculation will definitely produce invalid CSS.
     *
     * @param list<object> $arguments
     *
     * @throws SassScriptException
     */
    public static function max(array $arguments): Value
    {
        $args = self::simplifyArguments($arguments);

        if (!$args) {
            throw new \InvalidArgumentException('max() must have at least one argument.');
        }

        /** @var SassNumber|null $maximum */
        $maximum = null;

        foreach ($args as $arg) {
            if (!$arg instanceof SassNumber || $maximum !== null && !$maximum->isComparableTo($arg)) {
                $maximum = null;
                break;
            }

            if ($maximum === null || $maximum->lessThan($arg)->isTruthy()) {
                $maximum = $arg;
            }
        }

        if ($maximum !== null) {
            return $maximum;
        }

        self::verifyCompatibleNumbers($args);

        return new SassCalculation('max', $args);
    }

    /**
     * Creates a `hypot()` calculation with the given $arguments.
     *
     * Each argument must be either a {@see SassNumber}, a {@see SassCalculation}, an
     * unquoted {@see SassString}, or a {@see CalculationOperation}. It must be passed at
     * least one argument.
     *
     * This automatically simplifies the calculation, so it may return a
     * {@see SassNumber} rather than a {@see SassCalculation}. It throws an exception if it
     * can determine that the calculation will definitely produce invalid CSS.
     *
     * @param list<object> $arguments
     */
    public static function hypot(array $arguments): Value
    {
        $args = self::simplifyArguments($arguments);

        if (!$args) {
            throw new \InvalidArgumentException('hypot() must have at least one argument.');
        }

        self::verifyCompatibleNumbers($args);

        $subTotal = 0.0;
        $first = $args[0];

        if (!$first instanceof SassNumber || $first->hasUnit('%')) {
            return new SassCalculation('hypot', $args);
        }

        foreach ($args as $i => $number) {
            if (!$number instanceof SassNumber || !$number->hasCompatibleUnits($first)) {
                return new SassCalculation('hypot', $args);
            }

            $sassIndex = $i + 1;
            $value = $number->convertValueToMatch($first, "number[$sassIndex]", 'numbers[1]');
            $subTotal += $value * $value;
        }

        return SassNumber::withUnits(sqrt($subTotal), $first->getNumeratorUnits(), $first->getDenominatorUnits());
    }

    /**
     * Creates a `sqrt()` calculation with the given $argument.
     *
     * The $argument must be either a {@see SassNumber}, a {@see SassCalculation}, an
     * unquoted {@see SassString}, or a {@see CalculationOperation}.
     *
     * This automatically simplifies the calculation, so it may return a
     * {@see SassNumber} rather than a {@see SassCalculation}. It throws an exception if it
     * can determine that the calculation will definitely produce invalid CSS.
     */
    public static function sqrt(object $argument): Value
    {
        return self::singleArgument('sqrt', $argument, NumberUtil::class . '::sqrt', true);
    }

    /**
     * Creates a `sin()` calculation with the given $argument.
     *
     * The $argument must be either a {@see SassNumber}, a {@see SassCalculation}, an
     * unquoted {@see SassString}, or a {@see CalculationOperation}.
     *
     * This automatically simplifies the calculation, so it may return a
     * {@see SassNumber} rather than a {@see SassCalculation}. It throws an exception if it
     * can determine that the calculation will definitely produce invalid CSS.
     */
    public static function sin(object $argument): Value
    {
        return self::singleArgument('sin', $argument, NumberUtil::class . '::sin');
    }

    /**
     * Creates a `cos()` calculation with the given $argument.
     *
     * The $argument must be either a {@see SassNumber}, a {@see SassCalculation}, an
     * unquoted {@see SassString}, or a {@see CalculationOperation}.
     *
     * This automatically simplifies the calculation, so it may return a
     * {@see SassNumber} rather than a {@see SassCalculation}. It throws an exception if it
     * can determine that the calculation will definitely produce invalid CSS.
     */
    public static function cos(object $argument): Value
    {
        return self::singleArgument('cos', $argument, NumberUtil::class . '::cos');
    }

    /**
     * Creates a `tan()` calculation with the given $argument.
     *
     * The $argument must be either a {@see SassNumber}, a {@see SassCalculation}, an
     * unquoted {@see SassString}, or a {@see CalculationOperation}.
     *
     * This automatically simplifies the calculation, so it may return a
     * {@see SassNumber} rather than a {@see SassCalculation}. It throws an exception if it
     * can determine that the calculation will definitely produce invalid CSS.
     */
    public static function tan(object $argument): Value
    {
        return self::singleArgument('tan', $argument, NumberUtil::class . '::tan');
    }

    /**
     * Creates an `atan()` calculation with the given $argument.
     *
     * The $argument must be either a {@see SassNumber}, a {@see SassCalculation}, an
     * unquoted {@see SassString}, or a {@see CalculationOperation}.
     *
     * This automatically simplifies the calculation, so it may return a
     * {@see SassNumber} rather than a {@see SassCalculation}. It throws an exception if it
     * can determine that the calculation will definitely produce invalid CSS.
     */
    public static function atan(object $argument): Value
    {
        return self::singleArgument('atan', $argument, NumberUtil::class . '::atan', true);
    }

    /**
     * Creates an `asin()` calculation with the given $argument.
     *
     * The $argument must be either a {@see SassNumber}, a {@see SassCalculation}, an
     * unquoted {@see SassString}, or a {@see CalculationOperation}.
     *
     * This automatically simplifies the calculation, so it may return a
     * {@see SassNumber} rather than a {@see SassCalculation}. It throws an exception if it
     * can determine that the calculation will definitely produce invalid CSS.
     */
    public static function asin(object $argument): Value
    {
        return self::singleArgument('asin', $argument, NumberUtil::class . '::asin', true);
    }

    /**
     * Creates an `acos()` calculation with the given $argument.
     *
     * The $argument must be either a {@see SassNumber}, a {@see SassCalculation}, an
     * unquoted {@see SassString}, or a {@see CalculationOperation}.
     *
     * This automatically simplifies the calculation, so it may return a
     * {@see SassNumber} rather than a {@see SassCalculation}. It throws an exception if it
     * can determine that the calculation will definitely produce invalid CSS.
     */
    public static function acos(object $argument): Value
    {
        return self::singleArgument('acos', $argument, NumberUtil::class . '::acos', true);
    }

    /**
     * Creates an `abs()` calculation with the given $argument.
     *
     * The $argument must be either a {@see SassNumber}, a {@see SassCalculation}, an
     * unquoted {@see SassString}, or a {@see CalculationOperation}.
     *
     * This automatically simplifies the calculation, so it may return a
     * {@see SassNumber} rather than a {@see SassCalculation}. It throws an exception if it
     * can determine that the calculation will definitely produce invalid CSS.
     */
    public static function abs(object $argument): Value
    {
        $argument = self::simplify($argument);

        if (!$argument instanceof SassNumber) {
            return new SassCalculation('abs', [$argument]);
        }

        if ($argument->hasUnit('%')) {
            $message = <<<WARNING
Passing percentage units to the global abs() function is deprecated.
In the future, this will emit a CSS abs() function to be resolved by the browser.
To preserve current behavior: math.abs($argument)

To emit a CSS abs() now: abs(#{{$argument}})
More info: https://sass-lang.com/d/abs-percent
WARNING;

            Warn::forDeprecation($message, Deprecation::absPercent);
        }

        return NumberUtil::abs($argument);
    }

    /**
     * Creates an `exp()` calculation with the given $argument.
     *
     * The $argument must be either a {@see SassNumber}, a {@see SassCalculation}, an
     * unquoted {@see SassString}, or a {@see CalculationOperation}.
     *
     * This automatically simplifies the calculation, so it may return a
     * {@see SassNumber} rather than a {@see SassCalculation}. It throws an exception if it
     * can determine that the calculation will definitely produce invalid CSS.
     */
    public static function exp(object $argument): Value
    {
        $argument = self::simplify($argument);

        if (!$argument instanceof SassNumber) {
            return new SassCalculation('exp', [$argument]);
        }

        $argument->assertNoUnits();

        return NumberUtil::pow(SassNumber::create(M_E), $argument);
    }

    /**
     * Creates a `sign()` calculation with the given $argument.
     *
     * The $argument must be either a {@see SassNumber}, a {@see SassCalculation}, an
     * unquoted {@see SassString}, or a {@see CalculationOperation}.
     *
     * This automatically simplifies the calculation, so it may return a
     * {@see SassNumber} rather than a {@see SassCalculation}. It throws an exception if it
     * can determine that the calculation will definitely produce invalid CSS.
     */
    public static function sign(object $argument): Value
    {
        $argument = self::simplify($argument);

        if (!$argument instanceof SassNumber) {
            return new SassCalculation('sign', [$argument]);
        }

        if (!$argument->hasUnits() && (is_nan($argument->getValue()) || $argument->getValue() === 0.0)) {
            return $argument;
        }

        if (!$argument->hasUnit('%')) {
            return SassNumber::create(NumberUtil::sign($argument->getValue()))->coerceToMatch($argument);
        }

        return new SassCalculation('sign', [$argument]);
    }

    /**
     * Creates a `clamp()` calculation with the given $min, $value, and $max.
     *
     * Each argument must be either a {@see SassNumber}, a {@see SassCalculation}, an
     * unquoted {@see SassString}, or a {@see CalculationOperation}.
     *
     * This automatically simplifies the calculation, so it may return a
     * {@see SassNumber} rather than a {@see SassCalculation}. It throws an exception if it
     * can determine that the calculation will definitely produce invalid CSS.
     *
     * This may be passed fewer than three arguments, but only if one of the
     * arguments is an unquoted `var()` string.
     *
     * @throws SassScriptException
     */
    public static function clamp(object $min, ?object $value = null, ?object $max = null): Value
    {
        if ($value === null && $max !== null) {
            throw new \InvalidArgumentException('If value is null, max must also be null.');
        }

        $min = self::simplify($min);

        if ($value !== null) {
            $value = self::simplify($value);
        }

        if ($max !== null) {
            $max = self::simplify($max);
        }

        if ($min instanceof SassNumber && $value instanceof SassNumber && $max instanceof SassNumber && $min->hasCompatibleUnits($value) && $min->hasCompatibleUnits($max)) {
            if ($value->lessThanOrEquals($min)->isTruthy()) {
                return $min;
            }

            if ($value->greaterThanOrEquals($max)->isTruthy()) {
                return $max;
            }

            return $value;
        }

        $args = array_values(array_filter([$min, $value, $max]));
        self::verifyCompatibleNumbers($args);
        self::verifyLength($args, 3);

        return new SassCalculation('clamp', $args);
    }

    /**
     * Creates a `pow()` calculation with the given $base and $exponent.
     *
     * Each argument must be either a {@see SassNumber}, a {@see SassCalculation}, an
     * unquoted {@see SassString}, or a {@see CalculationOperation}.
     *
     * This automatically simplifies the calculation, so it may return a
     * {@see SassNumber} rather than a {@see SassCalculation}. It throws an exception if it
     * can determine that the calculation will definitely produce invalid CSS.
     *
     * This may be passed fewer than two arguments, but only if one of the
     * arguments is an unquoted `var()` string.
     */
    public static function pow(object $base, ?object $exponent): Value
    {
        $args = [$base];
        if ($exponent !== null) {
            $args[] = $exponent;
        }
        self::verifyLength($args, 2);
        $base = self::simplify($base);
        if ($exponent !== null) {
            $exponent = self::simplify($exponent);
        }

        if (!$base instanceof SassNumber || !$exponent instanceof SassNumber) {
            return new SassCalculation('pow', $args);
        }

        $base->assertNoUnits();
        $exponent->assertNoUnits();

        return NumberUtil::pow($base, $exponent);
    }

    /**
     * Creates a `log()` calculation with the given $number and $base.
     *
     * Each argument must be either a {@see SassNumber}, a {@see SassCalculation}, an
     * unquoted {@see SassString}, or a {@see CalculationOperation}.
     *
     * This automatically simplifies the calculation, so it may return a
     * {@see SassNumber} rather than a {@see SassCalculation}. It throws an exception if it
     * can determine that the calculation will definitely produce invalid CSS.
     *
     * If arguments contains exactly a single argument, the base is set to
     * `math.e` by default.
     */
    public static function log(object $number, ?object $base): Value
    {
        $number = self::simplify($number);
        $args = [$number];
        if ($base !== null) {
            $base = self::simplify($base);
            $args[] = $base;
        }

        if (!$number instanceof SassNumber || ($base !== null && !$base instanceof SassNumber)) {
            return new SassCalculation('log', $args);
        }

        $number->assertNoUnits();

        if ($base instanceof SassNumber) {
            $base->assertNoUnits();

            return NumberUtil::log($number, $base);
        }

        return NumberUtil::log($number, null);
    }

    /**
     * Creates a `atan2()` calculation for $y and $x.
     *
     * Each argument must be either a {@see SassNumber}, a {@see SassCalculation}, an
     * unquoted {@see SassString}, or a {@see CalculationOperation}.
     *
     * This automatically simplifies the calculation, so it may return a
     * {@see SassNumber} rather than a {@see SassCalculation}. It throws an exception if it
     * can determine that the calculation will definitely produce invalid CSS.
     *
     * This may be passed fewer than two arguments, but only if one of the
     * arguments is an unquoted `var()` string.
     */
    public static function atan2(object $y, ?object $x): Value
    {
        $y = self::simplify($y);
        $args = [$y];

        if ($x !== null) {
            $x = self::simplify($x);
            $args[] = $x;
        }
        self::verifyLength($args, 2);
        self::verifyCompatibleNumbers($args);

        if (!$y instanceof SassNumber || !$x instanceof SassNumber || $y->hasUnit('%') || $x->hasUnit('%') || !$y->hasCompatibleUnits($x)) {
            return new SassCalculation('atan2', $args);
        }

        return NumberUtil::atan2($y, $x);
    }

    /**
     * Creates a `rem()` calculation with the given $dividend and $modulus.
     *
     * Each argument must be either a {@see SassNumber}, a {@see SassCalculation}, an
     * unquoted {@see SassString}, or a {@see CalculationOperation}.
     *
     * This automatically simplifies the calculation, so it may return a
     * {@see SassNumber} rather than a {@see SassCalculation}. It throws an exception if it
     * can determine that the calculation will definitely produce invalid CSS.
     *
     * This may be passed fewer than two arguments, but only if one of the
     * arguments is an unquoted `var()` string.
     */
    public static function rem(object $dividend, ?object $modulus): Value
    {
        $dividend = self::simplify($dividend);
        $args = [$dividend];

        if ($modulus !== null) {
            $modulus = self::simplify($modulus);
            $args[] = $modulus;
        }
        self::verifyLength($args, 2);
        self::verifyCompatibleNumbers($args);

        if (!$dividend instanceof SassNumber || !$modulus instanceof SassNumber || !$dividend->hasCompatibleUnits($modulus)) {
            return new SassCalculation('rem', $args);
        }

        $result = $dividend->modulo($modulus);

        if (NumberUtil::signIncludingZero($modulus->getValue()) !== NumberUtil::signIncludingZero($dividend->getValue())) {
            if (is_infinite($modulus->getValue())) {
                return $dividend;
            }

            if ($result->getValue() === 0.0) {
                return $result->unaryMinus();
            }

            return $result->minus($modulus);
        }

        return $result;
    }

    /**
     * Creates a `mod()` calculation with the given $dividend and $modulus.
     *
     * Each argument must be either a {@see SassNumber}, a {@see SassCalculation}, an
     * unquoted {@see SassString}, or a {@see CalculationOperation}.
     *
     * This automatically simplifies the calculation, so it may return a
     * {@see SassNumber} rather than a {@see SassCalculation}. It throws an exception if it
     * can determine that the calculation will definitely produce invalid CSS.
     *
     * This may be passed fewer than two arguments, but only if one of the
     * arguments is an unquoted `var()` string.
     */
    public static function mod(object $dividend, ?object $modulus): Value
    {
        $dividend = self::simplify($dividend);
        $args = [$dividend];

        if ($modulus !== null) {
            $modulus = self::simplify($modulus);
            $args[] = $modulus;
        }
        self::verifyLength($args, 2);
        self::verifyCompatibleNumbers($args);

        if (!$dividend instanceof SassNumber || !$modulus instanceof SassNumber || !$dividend->hasCompatibleUnits($modulus)) {
            return new SassCalculation('mod', $args);
        }

        return $dividend->modulo($modulus);
    }

    /**
     * Creates a `round()` calculation with the given $strategyOrNumber,
     * $numberOrStep, and $step. Strategy must be either nearest, up, down or
     * to-zero.
     *
     * Number and step must be either a {@see SassNumber}, a {@see SassCalculation}, an
     * unquoted {@see SassString}, or a {@see CalculationOperation}.
     *
     * This automatically simplifies the calculation, so it may return a
     * {@see SassNumber} rather than a {@see SassCalculation}. It throws an exception if it
     * can determine that the calculation will definitely produce invalid CSS.
     *
     * This may be passed fewer than two arguments, but only if one of the
     * arguments is an unquoted `var()` string.
     */
    public static function round(object $strategyOrNumber, ?object $numberOrStep = null, ?object $step = null): Value
    {
        $strategyOrNumber = self::simplify($strategyOrNumber);
        if ($numberOrStep !== null) {
            $numberOrStep = self::simplify($numberOrStep);
        }
        if ($step !== null) {
            $step = self::simplify($step);
        }

        switch (true) {
            case $strategyOrNumber instanceof SassNumber && $numberOrStep === null && $step === null:
                return self::matchUnits(round($strategyOrNumber->getValue()), $strategyOrNumber);

            case $strategyOrNumber instanceof SassNumber && $numberOrStep instanceof SassNumber && $step === null:
                self::verifyCompatibleNumbers([$strategyOrNumber, $numberOrStep]);

                if (!$strategyOrNumber->hasCompatibleUnits($numberOrStep)) {
                    return new SassCalculation('round', [$strategyOrNumber, $numberOrStep]);
                }

                return self::roundWithStep('nearest', $strategyOrNumber, $numberOrStep);

            case $strategyOrNumber instanceof SassString && \in_array($strategyOrNumber->getText(), ['nearest', 'up', 'down', 'to-zero'], true) && $numberOrStep instanceof SassNumber && $step instanceof SassNumber:
                self::verifyCompatibleNumbers([$numberOrStep, $step]);

                if (!$numberOrStep->hasCompatibleUnits($step)) {
                    return new SassCalculation('round', [$strategyOrNumber, $numberOrStep, $step]);
                }

                return self::roundWithStep($strategyOrNumber->getText(), $numberOrStep, $step);

            case $strategyOrNumber instanceof SassString && \in_array($strategyOrNumber->getText(), ['nearest', 'up', 'down', 'to-zero'], true) && $numberOrStep instanceof SassString && $step === null:
                return new SassCalculation('round', [$strategyOrNumber, $numberOrStep]);

            case $strategyOrNumber instanceof SassString && \in_array($strategyOrNumber->getText(), ['nearest', 'up', 'down', 'to-zero'], true) && $numberOrStep !== null && $step === null:
                throw new SassScriptException('If strategy is not null, step is required.');

            case $strategyOrNumber instanceof SassString && \in_array($strategyOrNumber->getText(), ['nearest', 'up', 'down', 'to-zero'], true) && $numberOrStep === null && $step === null:
                throw new SassScriptException('Number to round and step arguments are required.');

            case $strategyOrNumber instanceof SassString && $numberOrStep === null && $step === null:
                return new SassCalculation('round', [$strategyOrNumber]);

            case $numberOrStep === null && $step === null:
                throw new SassScriptException("Single argument $strategyOrNumber expected to be simplifiable.");

            case $step === null:
                return new SassCalculation('round', [$strategyOrNumber, $numberOrStep]);

            case $strategyOrNumber instanceof SassString && (\in_array($strategyOrNumber->getText(), ['nearest', 'up', 'down', 'to-zero'], true) || $strategyOrNumber->isVar()) && $numberOrStep !== null:
                return new SassCalculation('round', [$strategyOrNumber, $numberOrStep, $step]);

            case $numberOrStep !== null:
                throw new SassScriptException("$strategyOrNumber must be either nearest, up, down or to-zero.");

            default:
                throw new SassScriptException('Invalid parameters.');
        }
    }

    /**
     * Creates and simplifies a {@see CalculationOperation} with the given $operator,
     * $left, and $right.
     *
     * This automatically simplifies the operation, so it may return a
     * {@see SassNumber} rather than a {@see CalculationOperation}.
     *
     * Each of $left and $right must be either a {@see SassNumber}, a
     * {@see SassCalculation}, an unquoted {@see SassString}, or a {@see CalculationOperation}.
     *
     * @throws SassScriptException
     */
    public static function operate(CalculationOperator $operator, object $left, object $right): object
    {
        return self::operateInternal($operator, $left, $right, false, true);
    }

    /**
     * Like {@see operate}, but with the internal-only $inLegacySassFunction parameter.
     *
     * If $inLegacySassFunction is `true`, this allows unitless numbers to be added and
     * subtracted with numbers with units, for backwards-compatibility with the
     * old global `min()` and `max()` functions.
     *
     * If $simplify is `false`, no simplification will be done.
     *
     * @return SassNumber|CalculationOperation|SassString|SassCalculation|Value
     *
     * @throws SassScriptException
     *
     * @internal
     */
    public static function operateInternal(CalculationOperator $operator, object $left, object $right, bool $inLegacySassFunction, bool $simplify): object
    {
        if (!$simplify) {
            return new CalculationOperation($operator, $left, $right);
        }

        $left = self::simplify($left);
        $right = self::simplify($right);

        if ($operator === CalculationOperator::PLUS || $operator === CalculationOperator::MINUS) {
            if ($left instanceof SassNumber && $right instanceof SassNumber && ($inLegacySassFunction ? $left->isComparableTo($right) : $left->hasCompatibleUnits($right))) {
                return $operator === CalculationOperator::PLUS ? $left->plus($right) : $left->minus($right);
            }

            self::verifyCompatibleNumbers([$left, $right]);

            if ($right instanceof SassNumber && NumberUtil::fuzzyLessThan($right->getValue(), 0)) {
                $right = $right->times(SassNumber::create(-1));
                $operator = $operator === CalculationOperator::PLUS ? CalculationOperator::MINUS : CalculationOperator::PLUS;
            }

            return new CalculationOperation($operator, $left, $right);
        }

        if ($left instanceof SassNumber && $right instanceof SassNumber) {
            return $operator === CalculationOperator::TIMES ? $left->times($right) : $left->dividedBy($right);
        }

        return new CalculationOperation($operator, $left, $right);
    }

    /**
     * An internal constructor that doesn't perform any validation or
     * simplification.
     *
     * @param list<object> $arguments
     */
    private function __construct(string $name, array $arguments)
    {
        $this->name = $name;
        $this->arguments = $arguments;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isSpecialNumber(): bool
    {
        return true;
    }

    /**
     * @return list<object>
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function accept(ValueVisitor $visitor)
    {
        return $visitor->visitCalculation($this);
    }

    public function assertCalculation(?string $name = null): SassCalculation
    {
        return $this;
    }

    public function plus(Value $other): Value
    {
        if ($other instanceof SassString) {
            return parent::plus($other);
        }

        throw new SassScriptException("Undefined operation \"$this + $other\".");
    }

    public function minus(Value $other): Value
    {
        throw new SassScriptException("Undefined operation \"$this - $other\".");
    }

    public function unaryPlus(): Value
    {
        throw new SassScriptException("Undefined operation \"+$this\".");
    }

    public function unaryMinus(): Value
    {
        throw new SassScriptException("Undefined operation \"-$this\".");
    }

    public function equals(object $other): bool
    {
        if (!$other instanceof SassCalculation || $this->name !== $other->name) {
            return false;
        }

        if (\count($this->arguments) !== \count($other->arguments)) {
            return false;
        }

        foreach ($this->arguments as $i => $argument) {
            assert($argument instanceof Equatable);
            $otherArgument = $other->arguments[$i];

            if (!$argument->equals($otherArgument)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns $value coerced to $number's units.
     */
    private static function matchUnits(float $value, SassNumber $number): SassNumber
    {
        return SassNumber::withUnits($value, $number->getNumeratorUnits(), $number->getDenominatorUnits());
    }

    /**
     * Returns a rounded $number based on a selected rounding $strategy,
     * to the nearest integer multiple of $step.
     */
    private static function roundWithStep(string $strategy, SassNumber $number, SassNumber $step): SassNumber
    {
        if (!\in_array($strategy, ['nearest', 'up', 'down', 'to-zero'], true)) {
            throw new \InvalidArgumentException('$strategy must be either nearest, up, down or to-zero.');
        }

        if (is_infinite($number->getValue()) && is_infinite($step->getValue()) || $step->getValue() === 0.0 || is_nan($number->getValue()) || is_nan($step->getValue())) {
            return self::matchUnits(NAN, $number);
        }

        if (is_infinite($number->getValue())) {
            return $number;
        }

        if (is_infinite($step->getValue())) {
            if ($number->getValue() === 0.0) {
                return $number;
            }

            switch ($strategy) {
                case 'nearest':
                case 'to-zero':
                    if ($number->getValue() > 0) {
                        return self::matchUnits(0.0, $number);
                    }

                    return self::matchUnits(-0.0, $number);

                case 'up':
                    if ($number->getValue() > 0) {
                        return self::matchUnits(INF, $number);
                    }

                    return self::matchUnits(-0.0, $number);

                case 'down':
                    if ($number->getValue() < 0) {
                        return self::matchUnits(-INF, $number);
                    }

                    return self::matchUnits(0.0, $number);
            }
        }

        $stepWithNumberUnit = $step->convertValueToMatch($number);

        switch ($strategy) {
            case 'nearest':
                return self::matchUnits(round($number->getValue() / $stepWithNumberUnit) * $stepWithNumberUnit, $number);
            case 'up':
                return self::matchUnits(($step->getValue() < 0 ? floor($number->getValue() / $stepWithNumberUnit) : ceil($number->getValue() / $stepWithNumberUnit)) * $stepWithNumberUnit, $number);
            case 'down':
                return self::matchUnits(($step->getValue() < 0 ? ceil($number->getValue() / $stepWithNumberUnit) : floor($number->getValue() / $stepWithNumberUnit)) * $stepWithNumberUnit, $number);
            case 'to-zero':
                if ($number->getValue() < 0) {
                    return self::matchUnits(ceil($number->getValue() / $stepWithNumberUnit) * $stepWithNumberUnit, $number);
                }

                return self::matchUnits(floor($number->getValue() / $stepWithNumberUnit) * $stepWithNumberUnit, $number);

            default:
                return self::matchUnits(NAN, $number);
        }
    }

    /**
     * @param list<object> $args
     *
     * @return list<object>
     *
     * @throws SassScriptException
     */
    private static function simplifyArguments(array $args): array
    {
        return array_map([self::class, 'simplify'], $args);
    }

    /**
     * @return SassNumber|CalculationOperation|SassString|SassCalculation
     *
     * @throws SassScriptException
     */
    private static function simplify(object $arg): object
    {
        if ($arg instanceof SassNumber || $arg instanceof CalculationOperation) {
            return $arg;
        }

        if ($arg instanceof SassString) {
            if (!$arg->hasQuotes()) {
                return $arg;
            }

            throw new SassScriptException("Quoted string $arg can't be used in a calculation.");
        }

        if ($arg instanceof SassCalculation) {
            if ($arg->getName() === 'calc') {
                $argument = $arg->getArguments()[0];

                if ($argument instanceof SassString && !$argument->hasQuotes() && self::needsParentheses($argument->getText())) {
                    return new SassString("({$argument->getText()})", false);
                }

                \assert($argument instanceof SassNumber || $argument instanceof SassString || $argument instanceof SassCalculation || $argument instanceof CalculationOperation);

                return $argument;
            }

            return $arg;
        }

        if ($arg instanceof Value) {
            throw new SassScriptException("Value $arg can't be used in a calculation.");
        }

        throw new \InvalidArgumentException(sprintf('Unexpected calculation argument %s.', get_debug_type($arg)));
    }

    /**
     * Returns whether $text needs parentheses if it's the contents of a
     * `calc()` being embedded in another calculation.
     */
    private static function needsParentheses(string $text): bool
    {
        $first = $text[0];
        if (self::charNeedsParentheses($first)) {
            return true;
        }

        $couldBeVar = \strlen($text) > 4 && ($first === 'v' || $first === 'V');

        if (\strlen($text) < 2) {
            return false;
        }
        $second = $text[1];
        if (self::charNeedsParentheses($second)) {
            return true;
        }
        $couldBeVar = $couldBeVar && ($second === 'a' || $second === 'A');

        if (\strlen($text) < 3) {
            return false;
        }
        $third = $text[2];
        if (self::charNeedsParentheses($third)) {
            return true;
        }
        $couldBeVar = $couldBeVar && ($third === 'r' || $third === 'R');

        if (\strlen($text) < 4) {
            return false;
        }
        $fourth = $text[3];
        if ($couldBeVar && $fourth === '(') {
            return true;
        }
        if (self::charNeedsParentheses($fourth)) {
            return true;
        }

        for ($i = 4; $i < \strlen($text); ++$i) {
            if (self::charNeedsParentheses($text[$i])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns whether $character intrinsically needs parentheses if it appears
     * in the unquoted string argument of a `calc()` being embedded in another
     * calculation.
     */
    private static function charNeedsParentheses(string $character): bool
    {
        return $character === '/' || $character === '*' || Character::isWhitespace($character);
    }

    /**
     * Verifies that all the numbers in $args aren't known to be incompatible
     * with one another, and that they don't have units that are too complex for
     * calculations.
     *
     * @param list<object> $args
     *
     * @throws SassScriptException
     */
    private static function verifyCompatibleNumbers(array $args): void
    {
        foreach ($args as $arg) {
            if (!$arg instanceof SassNumber) {
                continue;
            }

            if (\count($arg->getNumeratorUnits()) > 1 || \count($arg->getDenominatorUnits())) {
                throw new SassScriptException("Number $arg isn't compatible with CSS calculations.");
            }
        }

        for ($i = 0; $i < \count($args); $i++) {
            $number1 = $args[$i];

            if (!$number1 instanceof SassNumber) {
                continue;
            }

            for ($j = $i + 1; $j < \count($args); $j++) {
                $number2 = $args[$j];

                if (!$number2 instanceof SassNumber) {
                    continue;
                }

                if ($number1->hasPossiblyCompatibleUnits($number2)) {
                    continue;
                }

                throw new SassScriptException("$number1 and $number2 are incompatible.");
            }
        }
    }

    /**
     * Throws a {@see SassScriptException} if $args isn't $expectedLength *and*
     * doesn't contain either a {@see SassString}.
     *
     * @param list<object> $args
     *
     * @throws SassScriptException
     */
    private static function verifyLength(array $args, int $expectedLength): void
    {
        if (\count($args) === $expectedLength) {
            return;
        }

        foreach ($args as $arg) {
            if ($arg instanceof SassString) {
                return;
            }
        }

        $length = \count($args);
        $verb = StringUtil::pluralize('was', $length, 'were');

        throw new SassScriptException("$expectedLength arguments required, but only $length $verb passed.");
    }

    /**
     * @param callable(SassNumber): SassNumber $mathFunc
     *
     * @param-immediately-invoked-callable $mathFunc
     */
    private static function singleArgument(string $name, object $argument, callable $mathFunc, bool $forbidUnits = false): Value
    {
        $argument = self::simplify($argument);

        if (!$argument instanceof SassNumber) {
            return new SassCalculation($name, [$argument]);
        }

        if ($forbidUnits) {
            $argument->assertNoUnits();
        }

        return $mathFunc($argument);
    }
}
