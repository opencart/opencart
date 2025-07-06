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
 * A specialized subclass of {@see SassNumber} for numbers that have exactly one numerator unit.
 *
 * @internal
 */
final class SingleUnitSassNumber extends SassNumber
{
    private const COMPATIBLE_LENGTH_UNITS = ['em', 'rem', 'ex', 'rex', 'cap', 'rcap', 'ch', 'rch', 'ic', 'ric', 'lh', 'rlh', 'vw', 'lvw', 'svw', 'dvw', 'vh', 'lvh', 'svh', 'dvh', 'vi', 'lvi', 'svi', 'dvi', 'vb', 'lvb', 'svb', 'dvb', 'vmin', 'lvmin', 'svmin', 'dvmin', 'vmax', 'lvmax', 'svmax', 'dvmax', 'cqw', 'cqh', 'cqi', 'cqb', 'cqmin', 'cqmax', 'cm', 'mm', 'q', 'in', 'pc', 'pt', 'px'];

    private const KNOWN_COMPATIBILITIES_BY_UNIT = [
        // length
        'em' => self::COMPATIBLE_LENGTH_UNITS,
        'rem' => self::COMPATIBLE_LENGTH_UNITS,
        'ex' => self::COMPATIBLE_LENGTH_UNITS,
        'rex' => self::COMPATIBLE_LENGTH_UNITS,
        'cap' => self::COMPATIBLE_LENGTH_UNITS,
        'rcap' => self::COMPATIBLE_LENGTH_UNITS,
        'ch' => self::COMPATIBLE_LENGTH_UNITS,
        'rch' => self::COMPATIBLE_LENGTH_UNITS,
        'ic' => self::COMPATIBLE_LENGTH_UNITS,
        'ric' => self::COMPATIBLE_LENGTH_UNITS,
        'lh' => self::COMPATIBLE_LENGTH_UNITS,
        'rlh' => self::COMPATIBLE_LENGTH_UNITS,
        'vw' => self::COMPATIBLE_LENGTH_UNITS,
        'lvw' => self::COMPATIBLE_LENGTH_UNITS,
        'svw' => self::COMPATIBLE_LENGTH_UNITS,
        'dvw' => self::COMPATIBLE_LENGTH_UNITS,
        'vh' => self::COMPATIBLE_LENGTH_UNITS,
        'lvh' => self::COMPATIBLE_LENGTH_UNITS,
        'svh' => self::COMPATIBLE_LENGTH_UNITS,
        'dvh' => self::COMPATIBLE_LENGTH_UNITS,
        'vi' => self::COMPATIBLE_LENGTH_UNITS,
        'lvi' => self::COMPATIBLE_LENGTH_UNITS,
        'svi' => self::COMPATIBLE_LENGTH_UNITS,
        'dvi' => self::COMPATIBLE_LENGTH_UNITS,
        'vb' => self::COMPATIBLE_LENGTH_UNITS,
        'lvb' => self::COMPATIBLE_LENGTH_UNITS,
        'svb' => self::COMPATIBLE_LENGTH_UNITS,
        'dvb' => self::COMPATIBLE_LENGTH_UNITS,
        'vmin' => self::COMPATIBLE_LENGTH_UNITS,
        'lvmin' => self::COMPATIBLE_LENGTH_UNITS,
        'svmin' => self::COMPATIBLE_LENGTH_UNITS,
        'dvmin' => self::COMPATIBLE_LENGTH_UNITS,
        'vmax' => self::COMPATIBLE_LENGTH_UNITS,
        'lvmax' => self::COMPATIBLE_LENGTH_UNITS,
        'svmax' => self::COMPATIBLE_LENGTH_UNITS,
        'dvmax' => self::COMPATIBLE_LENGTH_UNITS,
        'cqw' => self::COMPATIBLE_LENGTH_UNITS,
        'cqh' => self::COMPATIBLE_LENGTH_UNITS,
        'cqi' => self::COMPATIBLE_LENGTH_UNITS,
        'cqb' => self::COMPATIBLE_LENGTH_UNITS,
        'cqmin' => self::COMPATIBLE_LENGTH_UNITS,
        'cqmax' => self::COMPATIBLE_LENGTH_UNITS,
        'cm' => self::COMPATIBLE_LENGTH_UNITS,
        'mm' => self::COMPATIBLE_LENGTH_UNITS,
        'q' => self::COMPATIBLE_LENGTH_UNITS,
        'in' => self::COMPATIBLE_LENGTH_UNITS,
        'pc' => self::COMPATIBLE_LENGTH_UNITS,
        'pt' => self::COMPATIBLE_LENGTH_UNITS,
        'px' => self::COMPATIBLE_LENGTH_UNITS,
        // angle
        'deg' => ['deg', 'grad', 'rad', 'turn'],
        'grad' => ['deg', 'grad', 'rad', 'turn'],
        'rad' => ['deg', 'grad', 'rad', 'turn'],
        'turn' => ['deg', 'grad', 'rad', 'turn'],
        // time
        's' => ['s', 'ms'],
        'ms' => ['s', 'ms'],
        // frequency
        'hz' => ['hz', 'khz'],
        'khz' => ['hz', 'khz'],
        // pixel density
        'dpi' => ['dpi', 'dpcm', 'dppx'],
        'dpcm' => ['dpi', 'dpcm', 'dppx'],
        'dppx' => ['dpi', 'dpcm', 'dppx'],
    ];

    private readonly string $unit;

    /**
     * @param array{SassNumber, SassNumber}|null $asSlash
     */
    public function __construct(float $value, string $unit, ?array $asSlash = null)
    {
        parent::__construct($value, $asSlash);
        $this->unit = $unit;
    }

    public function getNumeratorUnits(): array
    {
        return [$this->unit];
    }

    public function getDenominatorUnits(): array
    {
        return [];
    }

    public function hasUnits(): bool
    {
        return true;
    }

    public function hasComplexUnits(): bool
    {
        return false;
    }

    protected function withValue(float $value): SassNumber
    {
        return new self($value, $this->unit);
    }

    public function withSlash(SassNumber $numerator, SassNumber $denominator): SassNumber
    {
        return new self($this->getValue(), $this->unit, array($numerator, $denominator));
    }

    public function hasUnit(string $unit): bool
    {
        return $unit === $this->unit;
    }

    public function hasCompatibleUnits(SassNumber $other): bool
    {
        return $other instanceof SingleUnitSassNumber && $this->compatibleWithUnit($other->unit);
    }

    public function hasPossiblyCompatibleUnits(SassNumber $other): bool
    {
        if (!$other instanceof SingleUnitSassNumber) {
            return false;
        }

        $knownCompatibilities = self::KNOWN_COMPATIBILITIES_BY_UNIT[strtolower($this->unit)] ?? null;

        if ($knownCompatibilities === null) {
            return true;
        }

        $otherUnit = strtolower($other->unit);

        return !isset(self::KNOWN_COMPATIBILITIES_BY_UNIT[$otherUnit]) || \in_array($otherUnit, $knownCompatibilities, true);
    }

    public function compatibleWithUnit(string $unit): bool
    {
        return self::getConversionFactor($this->unit, $unit) !== null;
    }

    public function coerceToMatch(SassNumber $other, ?string $name = null, ?string $otherName = null): SassNumber
    {
        if ($other instanceof SingleUnitSassNumber) {
            $coerced = $this->tryCoerceToUnit($other->unit);

            if ($coerced !== null) {
                return $coerced;
            }
        }

        // Call the parent to generate a consistent error message.
        return parent::coerceToMatch($other, $name, $otherName);
    }

    public function coerceValueToMatch(SassNumber $other, ?string $name = null, ?string $otherName = null): float
    {
        if ($other instanceof SingleUnitSassNumber) {
            $coerced = $this->tryCoerceValueToUnit($other->unit);

            if ($coerced !== null) {
                return $coerced;
            }
        }

        // Call the parent to generate a consistent error message.
        return parent::coerceValueToMatch($other, $name, $otherName);
    }

    public function convertToMatch(SassNumber $other, ?string $name = null, ?string $otherName = null): SassNumber
    {
        if ($other instanceof SingleUnitSassNumber) {
            $coerced = $this->tryCoerceToUnit($other->unit);

            if ($coerced !== null) {
                return $coerced;
            }
        }

        // Call the parent to generate a consistent error message.
        return parent::convertToMatch($other, $name, $otherName);
    }

    public function convertValueToMatch(SassNumber $other, ?string $name = null, ?string $otherName = null): float
    {
        if ($other instanceof SingleUnitSassNumber) {
            $coerced = $this->tryCoerceValueToUnit($other->unit);

            if ($coerced !== null) {
                return $coerced;
            }
        }

        // Call the parent to generate a consistent error message.
        return parent::convertValueToMatch($other, $name, $otherName);
    }

    public function coerce(array $newNumeratorUnits, array $newDenominatorUnits, ?string $name = null): SassNumber
    {
        if (\count($newNumeratorUnits) === 1 && \count($newDenominatorUnits) === 0) {
            $coerced = $this->tryCoerceToUnit($newNumeratorUnits[0]);

            if ($coerced !== null) {
                return $coerced;
            }
        }

        // Call the parent to generate a consistent error message.
        return parent::coerce($newNumeratorUnits, $newDenominatorUnits, $name);
    }

    public function coerceValue(array $newNumeratorUnits, array $newDenominatorUnits, ?string $name = null): float
    {
        if (\count($newNumeratorUnits) === 1 && \count($newDenominatorUnits) === 0) {
            $coerced = $this->tryCoerceValueToUnit($newNumeratorUnits[0]);

            if ($coerced !== null) {
                return $coerced;
            }
        }

        // Call the parent to generate a consistent error message.
        return parent::coerceValue($newNumeratorUnits, $newDenominatorUnits, $name);
    }

    public function coerceValueToUnit(string $unit, ?string $name = null): float
    {
        $coerced = $this->tryCoerceValueToUnit($unit);

        if ($coerced !== null) {
            return $coerced;
        }

        // Call the parent to generate a consistent error message.
        return parent::coerceValueToUnit($unit, $name);
    }

    public function unaryMinus(): Value
    {
        return new self(-$this->getValue(), $this->unit);
    }

    public function equals(object $other): bool
    {
        if ($other instanceof SingleUnitSassNumber) {
            $factor = self::getConversionFactor($other->unit, $this->unit);

            return $factor !== null && NumberUtil::fuzzyEquals($this->getValue() * $factor, $other->getValue());
        }

        return false;
    }

    /**
     * @param list<string> $otherNumerators
     * @param list<string> $otherDenominators
     */
    protected function multiplyUnits(float $value, array $otherNumerators, array $otherDenominators): SassNumber
    {
        $newNumerators = $otherNumerators;
        $removed = false;

        foreach ($otherDenominators as $key => $denominator) {
            $conversionFactor = self::getConversionFactor($denominator, $this->unit);

            if (\is_null($conversionFactor)) {
                continue;
            }

            $value *= $conversionFactor;
            unset($otherDenominators[$key]);
            $removed = true;
            break;
        }

        if (!$removed) {
            array_unshift($newNumerators, $this->unit);
        }

        return SassNumber::withUnits($value, $newNumerators, array_values($otherDenominators));
    }

    private function tryCoerceToUnit(string $unit): ?SassNumber
    {
        if ($unit === $this->unit) {
            return $this;
        }

        $factor = self::getConversionFactor($unit, $this->unit);

        if ($factor === null) {
            return null;
        }

        return new SingleUnitSassNumber($this->getValue() * $factor, $unit);
    }

    private function tryCoerceValueToUnit(string $unit): ?float
    {
        $factor = self::getConversionFactor($unit, $this->unit);

        if ($factor === null) {
            return null;
        }

        return $this->getValue() * $factor;
    }
}
