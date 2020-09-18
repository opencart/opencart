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

namespace ScssPhp\ScssPhp\Node;

use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\Node;
use ScssPhp\ScssPhp\Type;

/**
 * Dimension + optional units
 *
 * {@internal
 *     This is a work-in-progress.
 *
 *     The \ArrayAccess interface is temporary until the migration is complete.
 * }}
 *
 * @author Anthon Pang <anthon.pang@gmail.com>
 */
class Number extends Node implements \ArrayAccess
{
    /**
     * @var integer
     */
    public static $precision = 10;

    /**
     * @see http://www.w3.org/TR/2012/WD-css3-values-20120308/
     *
     * @var array
     */
    protected static $unitTable = [
        'in' => [
            'in' => 1,
            'pc' => 6,
            'pt' => 72,
            'px' => 96,
            'cm' => 2.54,
            'mm' => 25.4,
            'q'  => 101.6,
        ],
        'turn' => [
            'deg'  => 360,
            'grad' => 400,
            'rad'  => 6.28318530717958647692528676, // 2 * M_PI
            'turn' => 1,
        ],
        's' => [
            's'  => 1,
            'ms' => 1000,
        ],
        'Hz' => [
            'Hz'  => 1,
            'kHz' => 0.001,
        ],
        'dpi' => [
            'dpi'  => 1,
            'dpcm' => 1/2.54,
            'dppx' => 1/96,
        ],
    ];

    /**
     * @var integer|float
     */
    public $dimension;

    /**
     * @var array
     */
    public $units;

    /**
     * Initialize number
     *
     * @param mixed $dimension
     * @param mixed $initialUnit
     */
    public function __construct($dimension, $initialUnit)
    {
        $this->type      = Type::T_NUMBER;
        $this->dimension = $dimension;
        $this->units     = \is_array($initialUnit)
            ? $initialUnit
            : ($initialUnit ? [$initialUnit => 1]
                            : []);
    }

    /**
     * Coerce number to target units
     *
     * @param array $units
     *
     * @return \ScssPhp\ScssPhp\Node\Number
     */
    public function coerce($units)
    {
        if ($this->unitless()) {
            return new Number($this->dimension, $units);
        }

        $dimension = $this->dimension;

        if (\count($units)) {
            $baseUnit = array_keys($units);
            $baseUnit = reset($baseUnit);
            $baseUnit = $this->findBaseUnit($baseUnit);
            if ($baseUnit && isset(static::$unitTable[$baseUnit])) {
                foreach (static::$unitTable[$baseUnit] as $unit => $conv) {
                    $from       = isset($this->units[$unit]) ? $this->units[$unit] : 0;
                    $to         = isset($units[$unit]) ? $units[$unit] : 0;
                    $factor     = pow($conv, $from - $to);
                    $dimension /= $factor;
                }
            }
        }

        return new Number($dimension, $units);
    }

    /**
     * Normalize number
     *
     * @return \ScssPhp\ScssPhp\Node\Number
     */
    public function normalize()
    {
        $dimension = $this->dimension;
        $units     = [];

        $this->normalizeUnits($dimension, $units);

        return new Number($dimension, $units);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        if ($offset === -3) {
            return ! \is_null($this->sourceColumn);
        }

        if ($offset === -2) {
            return ! \is_null($this->sourceLine);
        }

        if ($offset === -1 ||
            $offset === 0 ||
            $offset === 1 ||
            $offset === 2
        ) {
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        switch ($offset) {
            case -3:
                return $this->sourceColumn;

            case -2:
                return $this->sourceLine;

            case -1:
                return $this->sourceIndex;

            case 0:
                return $this->type;

            case 1:
                return $this->dimension;

            case 2:
                return $this->units;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        if ($offset === 1) {
            $this->dimension = $value;
        } elseif ($offset === 2) {
            $this->units = $value;
        } elseif ($offset == -1) {
            $this->sourceIndex = $value;
        } elseif ($offset == -2) {
            $this->sourceLine = $value;
        } elseif ($offset == -3) {
            $this->sourceColumn = $value;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        if ($offset === 1) {
            $this->dimension = null;
        } elseif ($offset === 2) {
            $this->units = null;
        } elseif ($offset === -1) {
            $this->sourceIndex = null;
        } elseif ($offset === -2) {
            $this->sourceLine = null;
        } elseif ($offset === -3) {
            $this->sourceColumn = null;
        }
    }

    /**
     * Returns true if the number is unitless
     *
     * @return boolean
     */
    public function unitless()
    {
        return ! array_sum($this->units);
    }

    /**
     * Test if a number can be normalized in a base unit
     * ie if its units are homogeneous
     *
     * @return boolean
     */
    public function isNormalizable()
    {
        if ($this->unitless()) {
            return false;
        }

        $baseUnit = null;

        foreach ($this->units as $unit => $exp) {
            $b = $this->findBaseUnit($unit);

            if (\is_null($baseUnit)) {
                $baseUnit = $b;
            }

            if (\is_null($b) or $b !== $baseUnit) {
                return false;
            }
        }

        return $baseUnit;
    }

    /**
     * Returns unit(s) as the product of numerator units divided by the product of denominator units
     *
     * @return string
     */
    public function unitStr()
    {
        $numerators   = [];
        $denominators = [];

        foreach ($this->units as $unit => $unitSize) {
            if ($unitSize > 0) {
                $numerators = array_pad($numerators, \count($numerators) + $unitSize, $unit);
                continue;
            }

            if ($unitSize < 0) {
                $denominators = array_pad($denominators, \count($denominators) - $unitSize, $unit);
                continue;
            }
        }

        return implode('*', $numerators) . (\count($denominators) ? '/' . implode('*', $denominators) : '');
    }

    /**
     * Output number
     *
     * @param \ScssPhp\ScssPhp\Compiler $compiler
     *
     * @return string
     */
    public function output(Compiler $compiler = null)
    {
        $dimension = round($this->dimension, static::$precision);

        $units = array_filter($this->units, function ($unitSize) {
            return $unitSize;
        });

        if (\count($units) > 1 && array_sum($units) === 0) {
            $dimension = $this->dimension;
            $units     = [];

            $this->normalizeUnits($dimension, $units);

            $dimension = round($dimension, static::$precision);
            $units     = array_filter($units, function ($unitSize) {
                return $unitSize;
            });
        }

        $unitSize = array_sum($units);

        if ($compiler && ($unitSize > 1 || $unitSize < 0 || \count($units) > 1)) {
            $this->units = $units;
            $unit = $this->unitStr();
        } else {
            reset($units);
            $unit = key($units);
        }

        $dimension = number_format($dimension, static::$precision, '.', '');

        return (static::$precision ? rtrim(rtrim($dimension, '0'), '.') : $dimension) . $unit;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->output();
    }

    /**
     * Normalize units
     *
     * @param integer|float $dimension
     * @param array         $units
     * @param string        $baseUnit
     */
    private function normalizeUnits(&$dimension, &$units, $baseUnit = null)
    {
        $dimension = $this->dimension;
        $units     = [];

        foreach ($this->units as $unit => $exp) {
            if (! $baseUnit) {
                $baseUnit = $this->findBaseUnit($unit);
            }

            if ($baseUnit && isset(static::$unitTable[$baseUnit][$unit])) {
                $factor = pow(static::$unitTable[$baseUnit][$unit], $exp);

                $unit = $baseUnit;
                $dimension /= $factor;
            }

            $units[$unit] = $exp + (isset($units[$unit]) ? $units[$unit] : 0);
        }
    }

    /**
     * Find the base unit family for a given unit
     *
     * @param string $unit
     *
     * @return string|null
     */
    private function findBaseUnit($unit)
    {
        foreach (static::$unitTable as $baseUnit => $unitVariants) {
            if (isset($unitVariants[$unit])) {
                return $baseUnit;
            }
        }

        return null;
    }
}
