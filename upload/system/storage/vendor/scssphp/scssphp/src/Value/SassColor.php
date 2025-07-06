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

use ScssPhp\ScssPhp\Exception\SassScriptException;
use ScssPhp\ScssPhp\Util\ErrorUtil;
use ScssPhp\ScssPhp\Util\NumberUtil;
use ScssPhp\ScssPhp\Visitor\ValueVisitor;

/**
 * A SassScript color.
 */
final class SassColor extends Value
{
    /**
     * This color's red channel, between `0` and `255`.
     */
    private ?int $red;

    /**
     * This color's blue channel, between `0` and `255`.
     */
    private ?int $blue;

    /**
     * This color's green channel, between `0` and `255`.
     */
    private ?int $green;

    /**
     * This color's hue, between `0` and `360`.
     */
    private ?float $hue;

    /**
     * This color's saturation, a percentage between `0` and `100`.
     */
    private ?float $saturation;

    /**
     * This color's lightness, a percentage between `0` and `100`.
     */
    private ?float $lightness;

    /**
     * This color's alpha channel, between `0` and `1`.
     */
    private readonly float $alpha;

    private readonly ?ColorFormat $format;

    /**
     * Creates a RGB color
     *
     * @throws \OutOfRangeException if values are outside the expected range.
     */
    public static function rgb(int $red, int $green, int $blue, float $alpha = 1.0): SassColor
    {
        return self::rgbInternal($red, $green, $blue, $alpha);
    }

    /**
     * Like {@see rgb} but also takes a color format.
     *
     * @internal
     *
     * @throws \OutOfRangeException if values are outside the expected range.
     */
    public static function rgbInternal(int $red, int $green, int $blue, float $alpha = 1.0, ?ColorFormat $format = null): SassColor
    {
        $alpha = NumberUtil::fuzzyAssertRange($alpha, 0, 1, 'alpha');

        ErrorUtil::checkIntInInterval($red, 0, 255, 'red');
        ErrorUtil::checkIntInInterval($green, 0, 255, 'green');
        ErrorUtil::checkIntInInterval($blue, 0, 255, 'blue');

        return new self($red, $green, $blue, null, null, null, $alpha, $format);
    }

    /**
     * @throws \OutOfRangeException if values are outside the expected range.
     */
    public static function hsl(float $hue, float $saturation, float $lightness, float $alpha = 1.0): SassColor
    {
        return self::hslInternal($hue, $saturation, $lightness, $alpha);
    }

    /**
     * Like {@see hsl} but also takes a color format.
     *
     * @internal
     *
     * @throws \OutOfRangeException if values are outside the expected range.
     */
    public static function hslInternal(float $hue, float $saturation, float $lightness, float $alpha = 1.0, ?ColorFormat $format = null): SassColor
    {
        $alpha = NumberUtil::fuzzyAssertRange($alpha, 0, 1, 'alpha');

        $hue = fmod($hue, 360);
        if ($hue < 0) {
            $hue += 360;
        }
        $saturation = NumberUtil::fuzzyAssertRange($saturation, 0, 100, 'saturation');
        $lightness = NumberUtil::fuzzyAssertRange($lightness, 0, 100, 'lightness');

        return new self(null, null, null, $hue, $saturation, $lightness, $alpha, $format);
    }

    public static function hwb(float $hue, float $whiteness, float $blackness, float $alpha = 1.0): SassColor
    {
        $hue = fmod($hue, 360);
        if ($hue < 0) {
            $hue += 360;
        }
        $scaledHue = $hue / 360;
        $scaledWhiteness = NumberUtil::fuzzyAssertRange($whiteness, 0, 100, 'whiteness') / 100;
        $scaledBlackness = NumberUtil::fuzzyAssertRange($blackness, 0, 100, 'blackness') / 100;

        $sum = $scaledWhiteness + $scaledBlackness;

        if ($sum > 1) {
            $scaledWhiteness /= $sum;
            $scaledBlackness /= $sum;
        }

        $factor = 1 - $scaledWhiteness - $scaledBlackness;

        $toRgb = function (float $hue) use ($factor, $scaledWhiteness) {
            $channel = self::hueToRgb(0, 1, $hue) * $factor + $scaledWhiteness;

            return NumberUtil::fuzzyRound($channel * 255);
        };

        return self::rgb($toRgb($scaledHue + 1 / 3), $toRgb($scaledHue), $toRgb($scaledHue - 1 / 3), $alpha);
    }

    /**
     * This must always provide non-null values for either RGB or HSL values.
     * If they are all provided, they are expected to be in sync and this not
     * revalidated. This constructor does not revalidate ranges either.
     * Use named factories when this cannot be guaranteed.
     */
    private function __construct(?int $red, ?int $green, ?int $blue, ?float $hue, ?float $saturation, ?float $lightness, float $alpha, ?ColorFormat $format = null)
    {
        $this->red = $red;
        $this->green = $green;
        $this->blue = $blue;
        $this->hue = $hue;
        $this->saturation = $saturation;
        $this->lightness = $lightness;
        $this->alpha = $alpha;
        $this->format = $format;
    }

    public function getRed(): int
    {
        if (\is_null($this->red)) {
            $this->hslToRgb();
            assert(!\is_null($this->red));
        }

        return $this->red;
    }

    public function getGreen(): int
    {
        if (\is_null($this->green)) {
            $this->hslToRgb();
            assert(!\is_null($this->green));
        }

        return $this->green;
    }

    public function getBlue(): int
    {
        if (\is_null($this->blue)) {
            $this->hslToRgb();
            assert(!\is_null($this->blue));
        }

        return $this->blue;
    }

    public function getHue(): float
    {
        if (\is_null($this->hue)) {
            $this->rgbToHsl();
            assert(!\is_null($this->hue));
        }

        return $this->hue;
    }

    public function getSaturation(): float
    {
        if (\is_null($this->saturation)) {
            $this->rgbToHsl();
            assert(!\is_null($this->saturation));
        }

        return $this->saturation;
    }

    public function getLightness(): float
    {
        if (\is_null($this->lightness)) {
            $this->rgbToHsl();
            assert(!\is_null($this->lightness));
        }

        return $this->lightness;
    }

    public function getWhiteness(): float
    {
        return min($this->getRed(), $this->getGreen(), $this->getBlue()) / 255 * 100;
    }

    public function getBlackness(): float
    {
        return 100 - max($this->getRed(), $this->getGreen(), $this->getBlue()) / 255 * 100;
    }

    public function getAlpha(): float
    {
        return $this->alpha;
    }

    /**
     * The format in which this color was originally written and should be
     * serialized in expanded mode, or `null` if the color wasn't written in a
     * supported format.
     *
     * @internal
     */
    public function getFormat(): ?ColorFormat
    {
        return $this->format;
    }

    public function accept(ValueVisitor $visitor)
    {
        return $visitor->visitColor($this);
    }

    public function assertColor(?string $name = null): SassColor
    {
        return $this;
    }

    public function changeRgb(?int $red = null, ?int $green = null, ?int $blue = null, ?float $alpha = null): SassColor
    {
        return self::rgb($red ?? $this->getRed(), $green ?? $this->getGreen(), $blue ?? $this->getBlue(), $alpha ?? $this->alpha);
    }

    public function changeHsl(?float $hue = null, ?float $saturation = null, ?float $lightness = null, ?float $alpha = null): SassColor
    {
        return self::hsl($hue ?? $this->getHue(), $saturation ?? $this->getSaturation(), $lightness ?? $this->getLightness(), $alpha ?? $this->alpha);
    }

    public function changeHwb(?float $hue = null, ?float $whiteness = null, ?float $blackness = null, ?float $alpha = null): SassColor
    {
        return self::hwb($hue ?? $this->getHue(), $whiteness ?? $this->getWhiteness(), $blackness ?? $this->getBlackness(), $alpha ?? $this->alpha);
    }

    public function changeAlpha(float $alpha): SassColor
    {
        return new self(
            $this->red,
            $this->green,
            $this->blue,
            $this->hue,
            $this->saturation,
            $this->lightness,
            NumberUtil::fuzzyAssertRange($alpha, 0, 1, 'alpha')
        );
    }

    public function plus(Value $other): Value
    {
        if (!$other instanceof SassColor && !$other instanceof SassNumber) {
            return parent::plus($other);
        }

        throw new SassScriptException("Undefined operation \"$this + $other\".");
    }

    public function minus(Value $other): Value
    {
        if (!$other instanceof SassColor && !$other instanceof SassNumber) {
            return parent::minus($other);
        }

        throw new SassScriptException("Undefined operation \"$this - $other\".");
    }

    public function dividedBy(Value $other): Value
    {
        if (!$other instanceof SassColor && !$other instanceof SassNumber) {
            return parent::dividedBy($other);
        }

        throw new SassScriptException("Undefined operation \"$this / $other\".");
    }

    public function modulo(Value $other): Value
    {
        if (!$other instanceof SassColor && !$other instanceof SassNumber) {
            return parent::modulo($other);
        }

        throw new SassScriptException("Undefined operation \"$this % $other\".");
    }

    public function equals(object $other): bool
    {
        return $other instanceof SassColor && $this->getRed() === $other->getRed() && $this->getGreen() === $other->getGreen() && $this->getBlue() === $other->getBlue() && $this->alpha === $other->alpha;
    }

    private function rgbToHsl(): void
    {
        $scaledRed = $this->getRed() / 255;
        $scaledGreen = $this->getGreen() / 255;
        $scaledBlue = $this->getBlue() / 255;

        $min = min($scaledRed, $scaledGreen, $scaledBlue);
        $max = max($scaledRed, $scaledGreen, $scaledBlue);
        $delta = $max - $min;

        if ($delta == 0) {
            $this->hue = 0;
        } elseif ($max == $scaledRed) {
            $this->hue = fmod(60 * ($scaledGreen - $scaledBlue) / $delta, 360);
        } elseif ($max == $scaledGreen) {
            $this->hue = fmod(120 + 60 * ($scaledBlue - $scaledRed) / $delta, 360);
        } else {
            $this->hue = fmod(240 + 60 * ($scaledRed - $scaledGreen) / $delta, 360);
        }

        if ($this->hue < 0) {
            $this->hue += 360;
        }

        $this->lightness = 50 * ($max + $min);

        if ($max == $min) {
            $this->saturation = 0;
        } elseif ($this->lightness < 50) {
            $this->saturation = 100 * $delta / ($max + $min);
        } else {
            $this->saturation = 100 * $delta / (2 - $max - $min);
        }
    }

    private function hslToRgb(): void
    {
        $scaledHue = $this->getHue() / 360;
        $scaledSaturation = $this->getSaturation() / 100;
        $scaledLightness = $this->getLightness() / 100;

        if ($scaledLightness <= 0.5) {
            $m2 = $scaledLightness * ($scaledSaturation + 1);
        } else {
            $m2 = $scaledLightness + $scaledSaturation - $scaledLightness * $scaledSaturation;
        }

        $m1 = $scaledLightness * 2 - $m2;

        $this->red = NumberUtil::fuzzyRound(self::hueToRgb($m1, $m2, $scaledHue + 1 / 3) * 255);
        $this->green = NumberUtil::fuzzyRound(self::hueToRgb($m1, $m2, $scaledHue) * 255);
        $this->blue = NumberUtil::fuzzyRound(self::hueToRgb($m1, $m2, $scaledHue - 1 / 3) * 255);
    }

    private static function hueToRgb(float $m1, float $m2, float $hue): float
    {
        if ($hue < 0) {
            $hue += 1;
        } elseif ($hue > 1) {
            $hue -= 1;
        }

        if ($hue < 1 / 6) {
            return $m1 + ($m2 - $m1) * $hue * 6;
        }

        if ($hue < 1 / 2) {
            return $m2;
        }

        if ($hue < 2 / 3) {
            return $m1 + ($m2 - $m1) * (2 / 3 - $hue) * 6;
        }

        return $m1;
    }
}
