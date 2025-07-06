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

/**
 * A specialized subclass of {@see SassNumber} for numbers that are neither {@see UnitlessSassNumber} nor {@see SingleUnitSassNumber}.
 *
 * @internal
 */
final class ComplexSassNumber extends SassNumber
{
    /**
     * @var list<string>
     */
    private readonly array $numeratorUnits;

    /**
     * @var list<string>
     */
    private readonly array $denominatorUnits;

    /**
     * @param list<string>                       $numeratorUnits
     * @param list<string>                       $denominatorUnits
     * @param array{SassNumber, SassNumber}|null $asSlash
     */
    public function __construct(float $value, array $numeratorUnits, array $denominatorUnits, ?array $asSlash = null)
    {
        assert(\count($numeratorUnits) > 1 || \count($denominatorUnits) > 0);

        parent::__construct($value, $asSlash);
        $this->numeratorUnits = $numeratorUnits;
        $this->denominatorUnits = $denominatorUnits;
    }

    public function getNumeratorUnits(): array
    {
        return $this->numeratorUnits;
    }

    public function getDenominatorUnits(): array
    {
        return $this->denominatorUnits;
    }

    public function hasUnits(): bool
    {
        return true;
    }

    public function hasComplexUnits(): bool
    {
        return true;
    }

    public function hasUnit(string $unit): bool
    {
        return false;
    }

    public function compatibleWithUnit(string $unit): bool
    {
        return false;
    }

    public function hasPossiblyCompatibleUnits(SassNumber $other): bool
    {
        // This logic is well-defined, and we could implement it in principle.
        // However, it would be fairly complex and there's no clear need for it yet.
        throw new \BadMethodCallException(__METHOD__ . 'is not implemented.');
    }

    protected function withValue(float $value): SassNumber
    {
        return new self($value, $this->numeratorUnits, $this->denominatorUnits);
    }

    public function withSlash(SassNumber $numerator, SassNumber $denominator): SassNumber
    {
        return new self($this->getValue(), $this->numeratorUnits, $this->denominatorUnits, array($numerator, $denominator));
    }
}
