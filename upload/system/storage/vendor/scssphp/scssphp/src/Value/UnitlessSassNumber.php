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

use ScssPhp\ScssPhp\Util\NumberUtil;

/**
 * A specialized subclass of {@see SassNumber} for numbers that have no units.
 *
 * @internal
 */
final class UnitlessSassNumber extends SassNumber
{
    /**
     * @param array{SassNumber, SassNumber}|null $asSlash
     */
    public function __construct(float $value, ?array $asSlash = null)
    {
        parent::__construct($value, $asSlash);
    }

    public function getNumeratorUnits(): array
    {
        return [];
    }

    public function getDenominatorUnits(): array
    {
        return [];
    }

    public function hasUnits(): bool
    {
        return false;
    }

    public function hasComplexUnits(): bool
    {
        return false;
    }

    protected function withValue(float $value): SassNumber
    {
        return new self($value);
    }

    public function withSlash(SassNumber $numerator, SassNumber $denominator): SassNumber
    {
        return new self($this->getValue(), array($numerator, $denominator));
    }

    public function hasUnit(string $unit): bool
    {
        return false;
    }

    public function hasCompatibleUnits(SassNumber $other): bool
    {
        return $other instanceof UnitlessSassNumber;
    }

    public function hasPossiblyCompatibleUnits(SassNumber $other): bool
    {
        return $other instanceof UnitlessSassNumber;
    }

    public function compatibleWithUnit(string $unit): bool
    {
        return true;
    }

    public function coerceToMatch(SassNumber $other, ?string $name = null, ?string $otherName = null): SassNumber
    {
        return $other->withValue($this->getValue());
    }

    public function coerceValueToMatch(SassNumber $other, ?string $name = null, ?string $otherName = null): float
    {
        return $this->getValue();
    }

    public function convertToMatch(SassNumber $other, ?string $name = null, ?string $otherName = null): SassNumber
    {
        if (!$other->hasUnits()) {
            return $this;
        }

        // Call the parent to generate a consistent error message.
        return parent::convertToMatch($other, $name, $otherName);
    }

    public function convertValueToMatch(SassNumber $other, ?string $name = null, ?string $otherName = null): float
    {
        if (!$other->hasUnits()) {
            return $this->getValue();
        }

        // Call the parent to generate a consistent error message.
        return parent::convertValueToMatch($other, $name, $otherName);
    }

    public function coerce(array $newNumeratorUnits, array $newDenominatorUnits, ?string $name = null): SassNumber
    {
        return SassNumber::withUnits($this->getValue(), $newNumeratorUnits, $newDenominatorUnits);
    }

    public function coerceValue(array $newNumeratorUnits, array $newDenominatorUnits, ?string $name = null): float
    {
        return $this->getValue();
    }

    public function coerceValueToUnit(string $unit, ?string $name = null): float
    {
        return $this->getValue();
    }

    public function greaterThan(Value $other): SassBoolean
    {
        if ($other instanceof SassNumber) {
            return SassBoolean::create(NumberUtil::fuzzyGreaterThan($this->getValue(), $other->getValue()));
        }

        return parent::greaterThan($other);
    }

    public function greaterThanOrEquals(Value $other): SassBoolean
    {
        if ($other instanceof SassNumber) {
            return SassBoolean::create(NumberUtil::fuzzyGreaterThanOrEquals($this->getValue(), $other->getValue()));
        }

        return parent::greaterThanOrEquals($other);
    }

    public function lessThan(Value $other): SassBoolean
    {
        if ($other instanceof SassNumber) {
            return SassBoolean::create(NumberUtil::fuzzyLessThan($this->getValue(), $other->getValue()));
        }

        return parent::lessThan($other);
    }

    public function lessThanOrEquals(Value $other): SassBoolean
    {
        if ($other instanceof SassNumber) {
            return SassBoolean::create(NumberUtil::fuzzyLessThanOrEquals($this->getValue(), $other->getValue()));
        }

        return parent::lessThanOrEquals($other);
    }

    public function modulo(Value $other): SassNumber
    {
        if ($other instanceof SassNumber) {
            return $other->withValue(NumberUtil::moduloLikeSass($this->getValue(), $other->getValue()));
        }

        return parent::modulo($other);
    }

    public function plus(Value $other): Value
    {
        if ($other instanceof SassNumber) {
            return $other->withValue($this->getValue() + $other->getValue());
        }

        return parent::plus($other);
    }

    public function minus(Value $other): Value
    {
        if ($other instanceof SassNumber) {
            return $other->withValue($this->getValue() - $other->getValue());
        }

        return parent::minus($other);
    }

    public function times(Value $other): Value
    {
        if ($other instanceof SassNumber) {
            return $other->withValue($this->getValue() * $other->getValue());
        }

        return parent::times($other);
    }

    public function dividedBy(Value $other): Value
    {
        if ($other instanceof SassNumber) {
            $value = NumberUtil::divideLikeSass($this->getValue(), $other->getValue());

            if ($other->hasUnits()) {
                return SassNumber::withUnits($value, $other->getDenominatorUnits(), $other->getNumeratorUnits());
            }

            return new self($value);
        }

        return parent::dividedBy($other);
    }

    public function unaryMinus(): Value
    {
        return new self(-$this->getValue());
    }

    public function equals(object $other): bool
    {
        return $other instanceof UnitlessSassNumber && NumberUtil::fuzzyEquals($this->getValue(), $other->getValue());
    }
}
