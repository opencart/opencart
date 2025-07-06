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

use JiriPudil\SealedClasses\Sealed;
use ScssPhp\ScssPhp\Exception\SassScriptException;
use ScssPhp\ScssPhp\Util\NumberUtil;
use ScssPhp\ScssPhp\Util\StringUtil;
use ScssPhp\ScssPhp\Visitor\ValueVisitor;

/**
 * A SassScript number.
 *
 * Numbers can have units. Although there's no literal syntax for it, numbers
 * support scientific-style numerator and denominator units (for example,
 * `miles/hour`). These are expected to be resolved before being emitted to
 * CSS.
 */
#[Sealed(permits: [UnitlessSassNumber::class, SingleUnitSassNumber::class, ComplexSassNumber::class])]
abstract class SassNumber extends Value
{
    final const PRECISION = 10;

    /**
     * @see https://www.w3.org/TR/css-values-3/
     */
    private const CONVERSIONS = [
        'in' => [
            'in' => 1.0,
            'pc' => 6.0,
            'pt' => 72.0,
            'px' => 96.0,
            'cm' => 2.54,
            'mm' => 25.4,
            'q'  => 101.6,
        ],
        'deg' => [
            'deg'  => 360.0,
            'grad' => 400.0,
            'rad'  => 2 * M_PI,
            'turn' => 1.0,
        ],
        's' => [
            's'  => 1.0,
            'ms' => 1000.0,
        ],
        'Hz' => [
            'Hz'  => 1.0,
            'kHz' => 0.001,
        ],
        'dpi' => [
            'dpi'  => 1.0,
            'dpcm' => 1 / 2.54,
            'dppx' => 1 / 96,
        ],
    ];

    /**
     * A map from human-readable names of unit types to the convertible units that
     * fall into those types.
     */
    private const UNITS_BY_TYPE = [
        'length' => ['in', 'cm', 'pc', 'mm', 'q', 'pt', 'px'],
        'angle' => ['deg', 'grad', 'rad', 'turn'],
        'time' => ['s', 'ms'],
        'frequency' => ['Hz', 'kHz'],
        'pixel density' => ['dpi', 'dpcm', 'dppx']
    ];

    /**
     * A map from units to the human-readable names of those unit types.
     */
    private const TYPES_BY_UNIT = [
        'in' => 'length',
        'cm' => 'length',
        'pc' => 'length',
        'mm' => 'length',
        'q' => 'length',
        'pt' => 'length',
        'px' => 'length',
        'deg' => 'angle',
        'grad' => 'angle',
        'rad' => 'angle',
        'turn' => 'angle',
        's' => 'time',
        'ms' => 'time',
        'Hz' => 'frequency',
        'kHz' => 'frequency',
        'dpi' => 'pixel density',
        'dpcm' => 'pixel density',
        'dppx' => 'pixel density',
    ];

    private readonly float $value;

    /**
     * The representation of this number as two slash-separated numbers, if it has one.
     *
     * @var array{SassNumber, SassNumber}|null
     * @internal
     */
    private readonly ?array $asSlash;

    /**
     * @param array{SassNumber, SassNumber}|null $asSlash
     */
    protected function __construct(float $value, ?array $asSlash = null)
    {
        $this->value = $value;
        $this->asSlash = $asSlash;
    }

    /**
     * Creates a number, optionally with a single numerator unit.
     *
     * This matches the numbers that can be written as literals.
     * {@see SassNumber::withUnits} can be used to construct more complex units.
     */
    final public static function create(float $value, ?string $unit = null): SassNumber
    {
        if ($unit === null) {
            return new UnitlessSassNumber($value);
        }

        return new SingleUnitSassNumber($value, $unit);
    }

    /**
     * Creates a number with full $numeratorUnits and $denominatorUnits.
     *
     * @param list<string> $numeratorUnits
     * @param list<string> $denominatorUnits
     */
    final public static function withUnits(float $value, array $numeratorUnits = [], array $denominatorUnits = []): SassNumber
    {
        if (empty($numeratorUnits) && empty($denominatorUnits)) {
            return new UnitlessSassNumber($value);
        }

        if (empty($denominatorUnits) && \count($numeratorUnits) === 1) {
            return new SingleUnitSassNumber($value, $numeratorUnits[0]);
        }

        if (empty($numeratorUnits)) {
            return new ComplexSassNumber($value, $numeratorUnits, $denominatorUnits);
        }

        $numerators = $numeratorUnits;
        $unsimplifiedDenominators = $denominatorUnits;
        $denominators = [];

        foreach ($unsimplifiedDenominators as $denominator) {
            $simplifiedAway = false;

            foreach ($numerators as $i => $numerator) {
                $factor = self::getConversionFactor($denominator, $numerator);

                if ($factor === null) {
                    continue;
                }

                $value *= $factor;
                unset($numerators[$i]);
                $simplifiedAway = true;
                break;
            }

            if (!$simplifiedAway) {
                $denominators[] = $denominator;
            }
        }

        $numerators = array_values($numerators);

        if (empty($denominators)) {
            if (empty($numerators)) {
                return new UnitlessSassNumber($value);
            }

            if (\count($numerators) === 1) {
                return new SingleUnitSassNumber($value, $numerators[0]);
            }
        }

        return new ComplexSassNumber($value, $numerators, $denominators);
    }

    /**
     * The value of this number.
     *
     * Note that due to details of floating-point arithmetic, this may be a
     * float even if $this represents an int from Sass's perspective. Use
     * {@see isInt} to determine whether this is an integer, {@see asInt} to get its
     * integer value, or {@see assertInt} to do both at once.
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @return list<string>
     */
    abstract public function getNumeratorUnits(): array;

    /**
     * @return list<string>
     */
    abstract public function getDenominatorUnits(): array;

    /**
     * @return array{SassNumber, SassNumber}|null
     *
     * @internal
     */
    final public function getAsSlash(): ?array
    {
        return $this->asSlash;
    }

    public function accept(ValueVisitor $visitor)
    {
        return $visitor->visitNumber($this);
    }

    /**
     * Returns a SassNumber with this value and the same units.
     */
    abstract protected function withValue(float $value): SassNumber;

    /**
     * @internal
     */
    abstract public function withSlash(SassNumber $numerator, SassNumber $denominator): SassNumber;

    public function withoutSlash(): SassNumber
    {
        if ($this->asSlash === null) {
            return $this;
        }

        return $this->withValue($this->value);
    }

    public function assertNumber(?string $name = null): SassNumber
    {
        return $this;
    }

    /**
     * Returns a human-readable string representation of this number's units.
     */
    public function getUnitString(): string
    {
        return $this->hasUnits() ? self::buildUnitString($this->getNumeratorUnits(), $this->getDenominatorUnits()) : '';
    }

    /**
     * Whether $this is an integer, according to {@see NumberUtil::fuzzyEquals}.
     *
     * The int value can be accessed using {@see asInt} or {@see assertInt}. Note that
     * this may return `false` for very large doubles even though they may be
     * mathematically integers, because not all platforms have a valid
     * representation for integers that large.
     */
    public function isInt(): bool
    {
        return NumberUtil::fuzzyIsInt($this->value);
    }

    /**
     * If $this is an integer according to {@see isInt}, returns {@see value} as an int.
     *
     * Otherwise, returns `null`.
     */
    public function asInt(): ?int
    {
        return NumberUtil::fuzzyAsInt($this->value);
    }

    /**
     * Returns the value as an int, if it's an integer value according to
     * {@see isInt}.
     *
     * @throws SassScriptException if the value isn't an integer. If this came
     * from a function argument, $name is the argument name (without the `$`).
     * It's used for error reporting.
     */
    public function assertInt(?string $name = null): int
    {
        $integer = NumberUtil::fuzzyAsInt($this->value);

        if ($integer !== null) {
            return $integer;
        }

        throw SassScriptException::forArgument("$this is not an int.", $name);
    }

    /**
     * If {@see value} is between $min and $max, returns it.
     *
     * If {@see value} is {@see NumberUtil::fuzzyEquals} to $min or $max, it's clamped to the
     * appropriate value. Otherwise, this throws a {@see SassScriptException}. If this
     * came from a function argument, $name is the argument name (without the
     * `$`). It's used for error reporting.
     *
     * @throws SassScriptException if the value is outside the range
     */
    public function valueInRange(float $min, float $max, ?string $name = null): float
    {
        $result = NumberUtil::fuzzyCheckRange($this->value, $min, $max);

        if ($result !== null) {
            return $result;
        }

        $unitString = $this->getUnitString();

        throw SassScriptException::forArgument("Expected $this to be within $min$unitString and $max$unitString.", $name);
    }

    /**
     * Like {@see valueInRange}, but with an explicit unit for the expected upper and
     * lower bounds.
     *
     * This exists to solve the confusing error message in https://github.com/sass/dart-sass/issues/1745,
     * and should be removed once https://github.com/sass/sass/issues/3374 fully lands and unitless values
     * are required in these positions.
     *
     * @throws SassScriptException if the value is outside the range
     *
     * @internal
     */
    public function valueInRangeWithUnit(float $min, float $max, string $name, string $unit): float
    {
        $result = NumberUtil::fuzzyCheckRange($this->value, $min, $max);

        if ($result !== null) {
            return $result;
        }

        throw SassScriptException::forArgument("Expected $this to be within $min$unit and $max$unit.", $name);
    }

    /**
     * Returns true if the number has units.
     */
    abstract public function hasUnits(): bool;

    /**
     * Whether $this has more than one numerator unit, or any denominator units.
     *
     * This is `true` for numbers whose units make them unrepresentable as CSS
     * lengths.
     */
    abstract public function hasComplexUnits(): bool;

    /**
     * Returns whether $this has $unit as its only unit (and as a numerator).
     */
    abstract public function hasUnit(string $unit): bool;

    /**
     * Returns whether $this has units that are compatible with $other.
     *
     * Unlike {@see isComparableTo}, unitless numbers are only considered compatible
     * with other unitless numbers.
     */
    public function hasCompatibleUnits(SassNumber $other): bool
    {
        if (\count($this->getNumeratorUnits()) !== \count($other->getNumeratorUnits())) {
            return false;
        }

        if (\count($this->getDenominatorUnits()) !== \count($other->getDenominatorUnits())) {
            return false;
        }

        return $this->isComparableTo($other);
    }

    /**
     * Returns whether $this has units that are possibly-compatible with
     * $other, as defined by the Sass spec.
     *
     * @internal
     */
    abstract public function hasPossiblyCompatibleUnits(SassNumber $other): bool;

    /**
     * Returns whether $this can be coerced to the given unit.
     *
     * This always returns `true` for a unitless number.
     */
    abstract public function compatibleWithUnit(string $unit): bool;

    /**
     * Throws a SassScriptException unless $this has $unit as its only unit
     * (and as a numerator).
     *
     * If this came from a function argument, $name is the argument name
     * (without the `$`). It's used for error reporting.
     *
     * @throws SassScriptException
     */
    public function assertUnit(string $unit, ?string $varName = null): void
    {
        if ($this->hasUnit($unit)) {
            return;
        }

        throw SassScriptException::forArgument(sprintf('Expected %s to have unit "%s".', $this, $unit), $varName);
    }

    /**
     * Throws a SassScriptException unless $this has no units.
     *
     * If this came from a function argument, $name is the argument name
     * (without the `$`). It's used for error reporting.
     *
     * @throws SassScriptException
     */
    public function assertNoUnits(?string $varName = null): void
    {
        if (!$this->hasUnits()) {
            return;
        }

        throw SassScriptException::forArgument(sprintf('Expected %s to have no units.', $this), $varName);
    }

    /**
     * Returns a copy of this number, converted to the units represented by $newNumeratorUnits and $newDenominatorUnits.
     *
     * Note that {@see convertValue} is generally more efficient if the value
     * is going to be accessed directly.
     *
     * @param list<string> $newNumeratorUnits
     * @param list<string> $newDenominatorUnits
     *
     * @throws SassScriptException if this number's units are not compatible with $newNumeratorUnits and $newDenominatorUnits, or if either number is unitless but the other is not.
     */
    public function convert(array $newNumeratorUnits, array $newDenominatorUnits, ?string $name = null): SassNumber
    {
        return self::withUnits($this->convertValue($newNumeratorUnits, $newDenominatorUnits, $name), $newNumeratorUnits, $newDenominatorUnits);
    }

    /**
     * Returns {@see value}, converted to the units represented by $newNumeratorUnits and $newDenominatorUnits.
     *
     * @param list<string> $newNumeratorUnits
     * @param list<string> $newDenominatorUnits
     *
     * @throws SassScriptException if this number's units are not compatible with $newNumeratorUnits and $newDenominatorUnits, or if either number is unitless but the other is not.
     */
    public function convertValue(array $newNumeratorUnits, array $newDenominatorUnits, ?string $name = null): float
    {
        return $this->convertOrCoerceValue($newNumeratorUnits, $newDenominatorUnits, false, $name);
    }

    /**
     * Returns a copy of this number, converted to the same units as $other.
     *
     * Note that {@see convertValueToMatch} is generally more efficient if the value
     * is going to be accessed directly.
     *
     * @param string|null $name      The argument name if this is a function argument
     * @param string|null $otherName The argument name for $other if this is a function argument
     *
     * @throws SassScriptException if the units are not compatible or if either number is unitless but the other is not.
     */
    public function convertToMatch(SassNumber $other, ?string $name = null, ?string $otherName = null): SassNumber
    {
        return self::withUnits($this->convertValueToMatch($other, $name, $otherName), $other->getNumeratorUnits(), $other->getDenominatorUnits());
    }

    /**
     * Returns {@see value}, converted to the same units as $other.
     *
     * @param string|null $name      The argument name if this is a function argument
     * @param string|null $otherName The argument name for $other if this is a function argument
     *
     * @throws SassScriptException if the units are not compatible or if either number is unitless but the other is not.
     */
    public function convertValueToMatch(SassNumber $other, ?string $name = null, ?string $otherName = null): float
    {
        return $this->convertOrCoerceValue($other->getNumeratorUnits(), $other->getDenominatorUnits(), false, $name, $other, $otherName);
    }

    /**
     * Returns a copy of this number, converted to the units represented by $newNumeratorUnits and $newDenominatorUnits.
     *
     * This does not throw an error if this number is unitless and
     * $newNumeratorUnits/$newDenominatorUnits are not empty, or vice versa. Instead,
     * it treats all unitless numbers as convertible to and from all units without
     * changing the value.
     *
     * Note that {@see coerceValue} is generally more efficient if the value
     * is going to be accessed directly.
     *
     * @param list<string> $newNumeratorUnits
     * @param list<string> $newDenominatorUnits
     *
     * @throws SassScriptException if this number's units are not compatible with $newNumeratorUnits and $newDenominatorUnits
     */
    public function coerce(array $newNumeratorUnits, array $newDenominatorUnits, ?string $name = null): SassNumber
    {
        return self::withUnits($this->coerceValue($newNumeratorUnits, $newDenominatorUnits, $name), $newNumeratorUnits, $newDenominatorUnits);
    }

    /**
     * Returns {@see value}, converted to the units represented by $newNumeratorUnits and $newDenominatorUnits.
     *
     * This does not throw an error if this number is unitless and
     * $newNumeratorUnits/$newDenominatorUnits are not empty, or vice versa. Instead,
     * it treats all unitless numbers as convertible to and from all units without
     * changing the value.
     *
     * @param list<string> $newNumeratorUnits
     * @param list<string> $newDenominatorUnits
     *
     * @throws SassScriptException if this number's units are not compatible with $newNumeratorUnits and $newDenominatorUnits
     */
    public function coerceValue(array $newNumeratorUnits, array $newDenominatorUnits, ?string $name = null): float
    {
        return $this->convertOrCoerceValue($newNumeratorUnits, $newDenominatorUnits, true, $name);
    }

    /**
     * A shorthand for {@see coerceValue} with a single unit
     */
    public function coerceValueToUnit(string $unit, ?string $name = null): float
    {
        return $this->coerceValue([$unit], [], $name);
    }

    /**
     * Returns a copy of this number, converted to the same units as $other.
     *
     * Unlike {@see convertToMatch}, this does not throw an error if this number is
     * unitless and $other is not, or vice versa. Instead, it treats all unitless
     * numbers as convertible to and from all units without changing the value.
     *
     * Note that {@see coerceValueToMatch} is generally more efficient if the value
     * is going to be accessed directly.
     *
     * @param string|null $name      The argument name if this is a function argument
     * @param string|null $otherName The argument name for $other if this is a function argument
     *
     * @throws SassScriptException if the units are not compatible
     */
    public function coerceToMatch(SassNumber $other, ?string $name = null, ?string $otherName = null): SassNumber
    {
        return self::withUnits($this->coerceValueToMatch($other, $name, $otherName), $other->getNumeratorUnits(), $other->getDenominatorUnits());
    }

    /**
     * Returns {@see value}, converted to the same units as $other.
     *
     * Unlike {@see convertValueToMatch}, this does not throw an error if this number
     * is unitless and $other is not, or vice versa. Instead, it treats all unitless
     * numbers as convertible to and from all units without changing the value.
     *
     * @param string|null $name      The argument name if this is a function argument
     * @param string|null $otherName The argument name for $other if this is a function argument
     *
     * @throws SassScriptException if the units are not compatible
     */
    public function coerceValueToMatch(SassNumber $other, ?string $name = null, ?string $otherName = null): float
    {
        return $this->convertOrCoerceValue($other->getNumeratorUnits(), $other->getDenominatorUnits(), true, $name, $other, $otherName);
    }

    /**
     * Returns whether this number can be compared to $other.
     *
     * Two numbers can be compared if they have compatible units, or if either
     * number has no units.
     *
     * @internal
     */
    public function isComparableTo(SassNumber $other): bool
    {
        if (!$this->hasUnits() || !$other->hasUnits()) {
            return true;
        }

        try {
            $this->greaterThan($other);
            return true;
        } catch (SassScriptException) {
            return false;
        }
    }

    public function greaterThan(Value $other): SassBoolean
    {
        if ($other instanceof SassNumber) {
            // Not using a first-class callable for NumberUtil::fuzzyGreaterThan(),
            // because of a PHP 8.1 bug that results in a segmentation
            // fault, when an Exception is thrown from a function taking the FCC as
            // a parameter.
            //
            // see: https://github.com/php/php-src/commit/b3e26c3036a54e9821ea7119c26cdabe484fe36d
            // see: https://github.com/scssphp/scssphp/issues/752#issuecomment-2423857568
            return SassBoolean::create($this->coerceUnits($other, [NumberUtil::class, 'fuzzyGreaterThan']));
        }

        throw new SassScriptException("Undefined operation \"$this > $other\".");
    }

    public function greaterThanOrEquals(Value $other): SassBoolean
    {
        if ($other instanceof SassNumber) {
            return SassBoolean::create($this->coerceUnits($other, NumberUtil::fuzzyGreaterThanOrEquals(...)));
        }

        throw new SassScriptException("Undefined operation \"$this >= $other\".");
    }

    public function lessThan(Value $other): SassBoolean
    {
        if ($other instanceof SassNumber) {
            return SassBoolean::create($this->coerceUnits($other, NumberUtil::fuzzyLessThan(...)));
        }

        throw new SassScriptException("Undefined operation \"$this < $other\".");
    }

    public function lessThanOrEquals(Value $other): SassBoolean
    {
        if ($other instanceof SassNumber) {
            return SassBoolean::create($this->coerceUnits($other, NumberUtil::fuzzyLessThanOrEquals(...)));
        }

        throw new SassScriptException("Undefined operation \"$this > $other\".");
    }

    public function modulo(Value $other): SassNumber
    {
        if ($other instanceof SassNumber) {
            return $this->withValue($this->coerceUnits($other, NumberUtil::moduloLikeSass(...)));
        }

        throw new SassScriptException("Undefined operation \"$this % $other\".");
    }

    public function plus(Value $other): Value
    {
        if ($other instanceof SassNumber) {
            return $this->withValue($this->coerceUnits($other, fn($num1, $num2) => $num1 + $num2));
        }

        if (!$other instanceof SassColor) {
            return parent::plus($other);
        }

        throw new SassScriptException("Undefined operation \"$this + $other\".");
    }

    public function minus(Value $other): Value
    {
        if ($other instanceof SassNumber) {
            return $this->withValue($this->coerceUnits($other, fn($num1, $num2) => $num1 - $num2));
        }

        if (!$other instanceof SassColor) {
            return parent::minus($other);
        }

        throw new SassScriptException("Undefined operation \"$this - $other\".");
    }

    public function times(Value $other): Value
    {
        if ($other instanceof SassNumber) {
            if (!$other->hasUnits()) {
                return $this->withValue($this->value * $other->value);
            }

            return $this->multiplyUnits($this->value * $other->value, $other->getNumeratorUnits(), $other->getDenominatorUnits());
        }

        throw new SassScriptException("Undefined operation \"$this * $other\".");
    }

    public function dividedBy(Value $other): Value
    {
        if ($other instanceof SassNumber) {
            $value = NumberUtil::divideLikeSass($this->value, $other->value);

            if (!$other->hasUnits()) {
                return $this->withValue($value);
            }

            return $this->multiplyUnits($value, $other->getDenominatorUnits(), $other->getNumeratorUnits());
        }

        return parent::dividedBy($other);
    }

    public function unaryPlus(): Value
    {
        return $this;
    }

    public function equals(object $other): bool
    {
        if (!$other instanceof SassNumber) {
            return false;
        }

        if (\count($this->getNumeratorUnits()) !== \count($other->getNumeratorUnits()) || \count($this->getDenominatorUnits()) !== \count($other->getDenominatorUnits())) {
            return false;
        }

        // In Sass, neither NaN nor Infinity are equal to themselves, while PHP defines INF==INF
        if (is_nan($this->value) || is_nan($other->value) || !is_finite($this->value) || !is_finite($other->value)) {
            return false;
        }

        if (!$this->hasUnits()) {
            return NumberUtil::fuzzyEquals($this->value, $other->value);
        }

        if (
            self::canonicalizeUnitList($this->getNumeratorUnits()) !== self::canonicalizeUnitList($other->getNumeratorUnits()) ||
            self::canonicalizeUnitList($this->getDenominatorUnits()) !== self::canonicalizeUnitList($other->getDenominatorUnits())
        ) {
            return false;
        }

        return NumberUtil::fuzzyEquals(
            $this->value * self::getCanonicalMultiplier($this->getNumeratorUnits()) / self::getCanonicalMultiplier($this->getDenominatorUnits()),
            $other->value * self::getCanonicalMultiplier($other->getNumeratorUnits()) / self::getCanonicalMultiplier($other->getDenominatorUnits())
        );
    }

    /**
     * @param list<string> $units
     */
    private static function getCanonicalMultiplier(array $units): float
    {
        return array_reduce($units, fn($multiplier, $unit) => $multiplier * self::getCanonicalMultiplierForUnit($unit), 1.0);
    }

    private static function getCanonicalMultiplierForUnit(string $unit): float
    {
        foreach (self::CONVERSIONS as $canonicalUnit => $conversions) {
            if (isset($conversions[$unit])) {
                return $conversions[$canonicalUnit] / $conversions[$unit];
            }
        }

        return 1.0;
    }

    /**
     * @param list<string> $units
     *
     * @return list<string>
     */
    private static function canonicalizeUnitList(array $units): array
    {
        if (\count($units) === 0) {
            return $units;
        }

        if (\count($units) === 1) {
            if (isset(self::TYPES_BY_UNIT[$units[0]])) {
                $type = self::TYPES_BY_UNIT[$units[0]];

                return [self::UNITS_BY_TYPE[$type][0]];
            }

            return $units;
        }

        $canonicalUnits = [];

        foreach ($units as $unit) {
            if (isset(self::TYPES_BY_UNIT[$unit])) {
                $type = self::TYPES_BY_UNIT[$unit];

                $canonicalUnits[] = self::UNITS_BY_TYPE[$type][0];
            } else {
                $canonicalUnits[] = $unit;
            }
        }

        sort($canonicalUnits);

        return $canonicalUnits;
    }

    /**
     * @template T
     *
     * @param callable(float, float): T $operation
     *
     * @return T
     *
     * @param-immediately-invoked-callable $operation
     */
    private function coerceUnits(SassNumber $other, callable $operation)
    {
        try {
            return \call_user_func($operation, $this->value, $other->coerceValueToMatch($this));
        } catch (SassScriptException $e) {
            // If the conversion fails, re-run it in the other direction. This will
            // generate an error message that prints $this before $other, which is
            // more readable.
            $this->coerceValueToMatch($other);

            throw $e; // Should be unreadable as the coercion should throw.
        }
    }

    /**
     * @param list<string> $newNumeratorUnits
     * @param list<string> $newDenominatorUnits
     * @param string|null  $name      The argument name if this is a function argument
     * @param string|null  $otherName The argument name for $other if this is a function argument
     *
     * @throws SassScriptException if this number's units are not compatible with $newNumeratorUnits and $newDenominatorUnits
     */
    private function convertOrCoerceValue(array $newNumeratorUnits, array $newDenominatorUnits, bool $coerceUnitless, ?string $name = null, ?SassNumber $other = null, ?string $otherName = null): float
    {
        assert($other === null || ($other->getNumeratorUnits() === $newNumeratorUnits && $other->getDenominatorUnits() === $newDenominatorUnits), sprintf("Expected %s to have units %s.", $other, self::buildUnitString($newNumeratorUnits, $newDenominatorUnits)));

        if ($this->getNumeratorUnits() === $newNumeratorUnits && $this->getDenominatorUnits() === $newDenominatorUnits) {
            return $this->value;
        }

        $otherHasUnits = !empty($newNumeratorUnits) || !empty($newDenominatorUnits);

        if ($coerceUnitless && (!$otherHasUnits || !$this->hasUnits())) {
            return $this->value;
        }

        $value = $this->value;
        $oldNumerators = $this->getNumeratorUnits();

        foreach ($newNumeratorUnits as $newNumerator) {
            foreach ($oldNumerators as $key => $oldNumerator) {
                $conversionFactor = self::getConversionFactor($newNumerator, $oldNumerator);

                if (\is_null($conversionFactor)) {
                    continue;
                }

                $value *= $conversionFactor;
                unset($oldNumerators[$key]);
                continue 2;
            }

            throw $this->compatibilityException($otherHasUnits, $newNumeratorUnits, $newDenominatorUnits, $name, $other, $otherName);
        }

        $oldDenominators = $this->getDenominatorUnits();

        foreach ($newDenominatorUnits as $newDenominator) {
            foreach ($oldDenominators as $key => $oldDenominator) {
                $conversionFactor = self::getConversionFactor($newDenominator, $oldDenominator);

                if (\is_null($conversionFactor)) {
                    continue;
                }

                $value /= $conversionFactor;
                unset($oldDenominators[$key]);
                continue 2;
            }

            throw $this->compatibilityException($otherHasUnits, $newNumeratorUnits, $newDenominatorUnits, $name, $other, $otherName);
        }

        if (\count($oldNumerators) || \count($oldDenominators)) {
            throw $this->compatibilityException($otherHasUnits, $newNumeratorUnits, $newDenominatorUnits, $name, $other, $otherName);
        }

        return $value;
    }

    /**
     * @param list<string> $newNumeratorUnits
     * @param list<string> $newDenominatorUnits
     */
    private function compatibilityException(bool $otherHasUnits, array $newNumeratorUnits, array $newDenominatorUnits, ?string $name, ?SassNumber $other = null, ?string $otherName = null): SassScriptException
    {
        if ($other !== null) {
            $message = "$this and";

            if ($otherName !== null) {
                $message .= " \$$otherName:";
            }

            $message .= "$other have incompatible units";

            if (!$this->hasUnits() || !$otherHasUnits) {
                $message .= " (one has units and the other doesn't)";
            }

            return SassScriptException::forArgument("$message.", $name);
        }

        if (!$otherHasUnits) {
            return SassScriptException::forArgument("Expected $this to have no units.", $name);
        }

        if (\count($newNumeratorUnits) === 1 && \count($newDenominatorUnits) === 0 && isset(self::TYPES_BY_UNIT[$newNumeratorUnits[0]])) {
            $type = self::TYPES_BY_UNIT[$newNumeratorUnits[0]];
            $article = \in_array($type[0], ['a', 'e', 'i', 'o', 'u'], true) ? 'an' : 'a';
            $supportedUnits = implode(', ', self::UNITS_BY_TYPE[$type]);

            return SassScriptException::forArgument("Expected $this to have $article $type unit ($supportedUnits).", $name);
        }

        return SassScriptException::forArgument(sprintf('Expected %s to have %s %s.', $this, StringUtil::pluralize('unit', \count($newNumeratorUnits) + \count($newDenominatorUnits)), self::buildUnitString($newNumeratorUnits, $newDenominatorUnits)), $name);
    }

    /**
     * @param list<string> $otherNumerators
     * @param list<string> $otherDenominators
     */
    protected function multiplyUnits(float $value, array $otherNumerators, array $otherDenominators): SassNumber
    {
        $newNumerators = array();

        foreach ($this->getNumeratorUnits() as $numerator) {
            foreach ($otherDenominators as $key => $denominator) {
                $conversionFactor = self::getConversionFactor($numerator, $denominator);

                if (\is_null($conversionFactor)) {
                    continue;
                }

                $value /= $conversionFactor;
                unset($otherDenominators[$key]);
                continue 2;
            }

            $newNumerators[] = $numerator;
        }

        $denominators = $this->getDenominatorUnits();

        foreach ($otherNumerators as $numerator) {
            foreach ($denominators as $key => $denominator) {
                $conversionFactor = self::getConversionFactor($numerator, $denominator);

                if (\is_null($conversionFactor)) {
                    continue;
                }

                $value /= $conversionFactor;
                unset($denominators[$key]);
                continue 2;
            }

            $newNumerators[] = $numerator;
        }

        $newDenominators = array_values(array_merge($denominators, $otherDenominators));

        return self::withUnits($value, $newNumerators, $newDenominators);
    }

    /**
     * Returns the number of [unit1]s per [unit2].
     *
     * Equivalently, `1unit2 * conversionFactor(unit1, unit2) = 1unit1`.
     */
    protected static function getConversionFactor(string $unit1, string $unit2): ?float
    {
        if ($unit1 === $unit2) {
            return 1;
        }

        foreach (self::CONVERSIONS as $unitVariants) {
            if (isset($unitVariants[$unit1]) && isset($unitVariants[$unit2])) {
                return $unitVariants[$unit1] / $unitVariants[$unit2];
            }
        }

        return null;
    }

    /**
     * Returns unit(s) as the product of numerator units divided by the product of denominator units
     *
     * @param list<string> $numerators
     * @param list<string> $denominators
     */
    private static function buildUnitString(array $numerators, array $denominators): string
    {
        if (!\count($numerators)) {
            if (\count($denominators) === 0) {
                return 'no units';
            }

            if (\count($denominators) === 1) {
                return $denominators[0] . '^-1';
            }

            return '(' . implode('*', $denominators) . ')^-1';
        }

        return implode('*', $numerators) . (\count($denominators) ? '/' . implode('*', $denominators) : '');
    }

    /**
     * Returns a suggested Sass snippet for converting a variable named $name
     * (without `%`) containing this number into a number with the same value and
     * the given $unit.
     *
     * If $unit is null, this forces the number to be unitless.
     *
     * This is used for deprecation warnings when restricting which units are
     * allowed for a given function.
     *
     * @internal
     */
    public function unitSuggestion(string $name, ?string $unit = null): string
    {
        $result = "\$$name"
            . implode(array_map(fn($unit) => " * 1$unit", $this->getDenominatorUnits()))
            . implode(array_map(fn($unit) => " / 1$unit", $this->getNumeratorUnits()))
            . ($unit === null ? '' : " * 1$unit");

        return $this->getNumeratorUnits() === [] ? $result : "calc($result)";
    }
}
