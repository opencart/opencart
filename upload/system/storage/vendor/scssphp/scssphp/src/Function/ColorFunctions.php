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
use ScssPhp\ScssPhp\Util\IterableUtil;
use ScssPhp\ScssPhp\Util\NumberUtil;
use ScssPhp\ScssPhp\Util\StringUtil;
use ScssPhp\ScssPhp\Value\ColorFormatEnum;
use ScssPhp\ScssPhp\Value\ListSeparator;
use ScssPhp\ScssPhp\Value\SassArgumentList;
use ScssPhp\ScssPhp\Value\SassColor;
use ScssPhp\ScssPhp\Value\SassNumber;
use ScssPhp\ScssPhp\Value\SassString;
use ScssPhp\ScssPhp\Value\Value;
use ScssPhp\ScssPhp\Warn;

/**
 * @internal
 */
class ColorFunctions
{
    /**
     * @param list<Value> $arguments
     */
    public static function rgb(array $arguments): Value
    {
        return self::rgbImpl('rgb', $arguments);
    }

    /**
     * @param list<Value> $arguments
     */
    public static function rgbTwoArgs(array $arguments): Value
    {
        return self::rgbTwoArgsImpl('rgb', $arguments);
    }

    /**
     * @param list<Value> $arguments
     */
    public static function rgbOneArgs(array $arguments): Value
    {
        $parsed = self::parseChannels('rgb', ['$red', '$green', '$blue'], $arguments[0]);

        return $parsed instanceof SassString ? $parsed : self::rgbImpl('rgb', $parsed);
    }

    /**
     * @param list<Value> $arguments
     */
    public static function rgba(array $arguments): Value
    {
        return self::rgbImpl('rgba', $arguments);
    }

    /**
     * @param list<Value> $arguments
     */
    public static function rgbaTwoArgs(array $arguments): Value
    {
        return self::rgbTwoArgsImpl('rgba', $arguments);
    }

    /**
     * @param list<Value> $arguments
     */
    public static function rgbaOneArgs(array $arguments): Value
    {
        $parsed = self::parseChannels('rgba', ['$red', '$green', '$blue'], $arguments[0]);

        return $parsed instanceof SassString ? $parsed : self::rgbImpl('rgba', $parsed);
    }

    /**
     * @param list<Value> $arguments
     */
    public static function invert(array $arguments): Value
    {
        $weight = $arguments[1]->assertNumber('weight');
        if ($arguments[0] instanceof SassNumber || $arguments[0]->isSpecialNumber()) {
            if ($weight->getValue() !== 100.0 || !$weight->hasUnit('%')) {
                throw new SassScriptException('Only one argument may be passed to the plain-CSS invert() function.');
            }

            // Use the native CSS `invert` filter function.
            return self::functionString('invert', [$arguments[0]]);
        }

        $color = $arguments[0]->assertColor('color');
        $inverse = $color->changeRgb(255 - $color->getRed(), 255 - $color->getGreen(), 255 - $color->getBlue());

        return self::mixColors($inverse, $color, $weight);
    }

    /**
     * @param list<Value> $arguments
     */
    public static function hsl(array $arguments): Value
    {
        return self::hslImpl('hsl', $arguments);
    }

    /**
     * @param list<Value> $arguments
     */
    public static function hslTwoArgs(array $arguments): Value
    {
        // hsl(123, var(--foo)) is valid CSS because --foo might be `10%, 20%` and
        // functions are parsed after variable substitution.
        if ($arguments[0]->isVar() || $arguments[1]->isVar()) {
            return self::functionString('hsl', $arguments);
        }

        throw new SassScriptException('Missing argument $lightness.');
    }

    /**
     * @param list<Value> $arguments
     */
    public static function hslOneArgs(array $arguments): Value
    {
        $parsed = self::parseChannels('hsl', ['$hue', '$saturation', '$lightness'], $arguments[0]);

        return $parsed instanceof SassString ? $parsed : self::hslImpl('hsl', $parsed);
    }

    /**
     * @param list<Value> $arguments
     */
    public static function hsla(array $arguments): Value
    {
        return self::hslImpl('hsla', $arguments);
    }

    /**
     * @param list<Value> $arguments
     */
    public static function hslaTwoArgs(array $arguments): Value
    {
        // hsl(123, var(--foo)) is valid CSS because --foo might be `10%, 20%` and
        // functions are parsed after variable substitution.
        if ($arguments[0]->isVar() || $arguments[1]->isVar()) {
            return self::functionString('hsla', $arguments);
        }

        throw new SassScriptException('Missing argument $lightness.');
    }

    /**
     * @param list<Value> $arguments
     */
    public static function hslaOneArgs(array $arguments): Value
    {
        $parsed = self::parseChannels('hsla', ['$hue', '$saturation', '$lightness'], $arguments[0]);

        return $parsed instanceof SassString ? $parsed : self::hslImpl('hsla', $parsed);
    }

    /**
     * @param list<Value> $arguments
     */
    public static function grayscale(array $arguments): Value
    {
        if ($arguments[0] instanceof SassNumber || $arguments[0]->isSpecialNumber()) {
            // Use the native CSS `grayscale` filter function.
            return self::functionString('grayscale', $arguments);
        }

        $color = $arguments[0]->assertColor('color');

        return $color->changeHsl(saturation: 0);
    }

    /**
     * @param list<Value> $arguments
     */
    public static function adjustHue(array $arguments): Value
    {
        $color = $arguments[0]->assertColor('color');
        $degrees = self::angleValue($arguments[1], 'degrees');

        return $color->changeHsl(hue: $color->getHue() + $degrees);
    }

    /**
     * @param list<Value> $arguments
     */
    public static function lighten(array $arguments): Value
    {
        $color = $arguments[0]->assertColor('color');
        $amount = $arguments[1]->assertNumber('amount');

        return $color->changeHsl(lightness: NumberUtil::clamp($color->getLightness() + $amount->valueInRange(0, 100, 'amount'), 0, 100));
    }

    /**
     * @param list<Value> $arguments
     */
    public static function darken(array $arguments): Value
    {
        $color = $arguments[0]->assertColor('color');
        $amount = $arguments[1]->assertNumber('amount');

        return $color->changeHsl(lightness: NumberUtil::clamp($color->getLightness() - $amount->valueInRange(0, 100, 'amount'), 0, 100));
    }

    /**
     * @param list<Value> $arguments
     */
    public static function saturateCss(array $arguments): Value
    {
        if ($arguments[0] instanceof SassNumber || $arguments[0]->isSpecialNumber()) {
            // Use the native CSS `saturate` filter function.
            return self::functionString('saturate', $arguments);
        }

        $number = $arguments[0]->assertNumber('amount');

        return new SassString('saturate(' . $number->toCssString() . ')', false);
    }

    /**
     * @param list<Value> $arguments
     */
    public static function saturate(array $arguments): Value
    {
        $color = $arguments[0]->assertColor('color');
        $amount = $arguments[1]->assertNumber('amount');

        return $color->changeHsl(saturation: NumberUtil::clamp($color->getSaturation() + $amount->valueInRange(0, 100, 'amount'), 0, 100));
    }

    /**
     * @param list<Value> $arguments
     */
    public static function desaturate(array $arguments): Value
    {
        $color = $arguments[0]->assertColor('color');
        $amount = $arguments[1]->assertNumber('amount');

        return $color->changeHsl(saturation: NumberUtil::clamp($color->getSaturation() - $amount->valueInRange(0, 100, 'amount'), 0, 100));
    }

    /**
     * @param list<Value> $arguments
     */
    public static function alpha(array $arguments): Value
    {
        $argument = $arguments[0];
        if ($argument instanceof SassString && !$argument->hasQuotes() && preg_match('/^[a-zA-Z]+\s*=/', $argument->getText())) {
            // Support the proprietary Microsoft alpha() function.
            return self::functionString('alpha', $arguments);
        }

        $color = $arguments[0]->assertColor('color');

        return SassNumber::create($color->getAlpha());
    }

    /**
     * @param list<Value> $arguments
     */
    public static function alphaMicrosoft(array $arguments): Value
    {
        $argList = $arguments[0]->asList();
        $argumentCount = \count($argList);

        if ($argumentCount > 0 && IterableUtil::every($argList, fn($argument) => $argument instanceof SassString && !$argument->hasQuotes() && preg_match('/^[a-zA-Z]+\s*=/', $argument->getText()))) {
            // Support the proprietary Microsoft alpha() function.
            return self::functionString('alpha', $arguments);
        }

        \assert($argumentCount !== 1);

        if ($argumentCount === 0) {
            throw new SassScriptException('Missing argument $color.');
        }

        throw new SassScriptException("Only 1 argument allowed, but $argumentCount were passed.");
    }

    /**
     * @param list<Value> $arguments
     */
    public static function opacity(array $arguments): Value
    {
        if ($arguments[0] instanceof SassNumber || $arguments[0]->isSpecialNumber()) {
            // Use the native CSS `opacity` filter function.
            return self::functionString('opacity', $arguments);
        }

        $color = $arguments[0]->assertColor('color');

        return SassNumber::create($color->getAlpha());
    }

    /**
     * @param list<Value> $arguments
     */
    public static function red(array $arguments): Value
    {
        return SassNumber::create($arguments[0]->assertColor('color')->getRed());
    }

    /**
     * @param list<Value> $arguments
     */
    public static function green(array $arguments): Value
    {
        return SassNumber::create($arguments[0]->assertColor('color')->getGreen());
    }

    /**
     * @param list<Value> $arguments
     */
    public static function blue(array $arguments): Value
    {
        return SassNumber::create($arguments[0]->assertColor('color')->getBlue());
    }

    /**
     * @param list<Value> $arguments
     */
    public static function mix(array $arguments): Value
    {
        $color1 = $arguments[0]->assertColor('color1');
        $color2 = $arguments[1]->assertColor('color2');
        $weight = $arguments[2]->assertNumber('weight');

        return self::mixColors($color1, $color2, $weight);
    }

    /**
     * @param list<Value> $arguments
     */
    public static function hue(array $arguments): Value
    {
        return SassNumber::create($arguments[0]->assertColor('color')->getHue(), 'deg');
    }

    /**
     * @param list<Value> $arguments
     */
    public static function saturation(array $arguments): Value
    {
        return SassNumber::create($arguments[0]->assertColor('color')->getSaturation(), '%');
    }

    /**
     * @param list<Value> $arguments
     */
    public static function lightness(array $arguments): Value
    {
        return SassNumber::create($arguments[0]->assertColor('color')->getLightness(), '%');
    }

    /**
     * @param list<Value> $arguments
     */
    public static function complement(array $arguments): Value
    {
        $color = $arguments[0]->assertColor('color');

        return $color->changeHsl(hue: $color->getHue() + 180);
    }

    /**
     * @param list<Value> $arguments
     */
    public static function adjust(array $arguments): Value
    {
        return self::updateComponents($arguments, adjust: true);
    }

    /**
     * @param list<Value> $arguments
     */
    public static function scale(array $arguments): Value
    {
        return self::updateComponents($arguments, scale: true);
    }

    /**
     * @param list<Value> $arguments
     */
    public static function change(array $arguments): Value
    {
        return self::updateComponents($arguments, change: true);
    }

    /**
     * @param list<Value> $arguments
     */
    public static function ieHexStr(array $arguments): Value
    {
        $color = $arguments[0]->assertColor('color');
        return new SassString('#' . self::hexString(NumberUtil::fuzzyRound($color->getAlpha() * 255)) . self::hexString($color->getRed()) . self::hexString($color->getGreen()) . self::hexString($color->getBlue()), false);
    }

    private static function hexString(int $component): string
    {
        return strtoupper(str_pad(dechex($component), 2, '0', STR_PAD_LEFT));
    }

    /**
     * @param list<Value> $arguments
     */
    private static function updateComponents(array $arguments, bool $change = false, bool $adjust = false, bool $scale = false): SassColor
    {
        \assert(\count(array_filter([$change, $adjust, $scale])) === 1);

        $color = $arguments[0]->assertColor('color');
        $argumentList = $arguments[1];
        \assert($argumentList instanceof SassArgumentList);

        if (\count($argumentList->asList()) > 0) {
            throw new SassScriptException('Only one positional argument is allowed. All other arguments must be passed by name.');
        }

        $keywords = $argumentList->getKeywords();

        $getParam = function (string $name, float $max, bool $checkPercent = false, bool $assertPercent = false, bool $checkUnitless = false) use (&$keywords, $change, $scale): ?float {
            $number = ($keywords[$name] ?? null)?->assertNumber($name);
            unset($keywords[$name]);

            if ($number === null) {
                return null;
            }

            if (!$scale && $checkUnitless) {
                if ($number->hasUnits()) {
                    Warn::forDeprecation(
                        <<<TXT
\$$name: Passing a number with unit {$number->getUnitString()} is deprecated.

To preserve current behavior: {$number->unitSuggestion($name)}

More info: https://sass-lang.com/d/function-units
TXT,
                        Deprecation::functionUnits
                    );
                }
            }
            if (!$scale && $checkPercent) {
                self::checkPercent($number, $name);
            }
            if ($scale || $assertPercent) {
                $number->assertUnit('%', $name);
            }
            if ($scale) {
                $max = 100;
            }

            return $scale || $assertPercent
                ? $number->valueInRange($change ? 0 : -$max, $max, $name)
                : $number->valueInRangeWithUnit($change ? 0 : -$max, $max, $name, $checkPercent ? '%' : '');
        };

        $alpha = $getParam('alpha', 1, checkUnitless: true);
        $red = $getParam('red', 255);
        $green = $getParam('green', 255);
        $blue = $getParam('blue', 255);

        if ($scale) {
            $hue = null;
        } else {
            $hueValue = $keywords['hue'] ?? null;
            unset($keywords['hue']);
            $hue = $hueValue === null ? null : self::angleValue($hueValue, 'hue');
        }

        $saturation = $getParam('saturation', 100, checkPercent: true);
        $lightness = $getParam('lightness', 100, checkPercent: true);
        $whiteness = $getParam('whiteness', 100, assertPercent: true);
        $blackness = $getParam('blackness', 100, assertPercent: true);

        if (\count($keywords) > 0) {
            throw new SassScriptException(sprintf(
                'No %s named %s.',
                StringUtil::pluralize('argument', \count($keywords)),
                StringUtil::toSentence(array_map(fn($name) => "\$$name", array_keys($keywords)), 'or')
            ));
        }

        $hasRgb = $red !== null || $green !== null || $blue !== null;
        $hasSL = $saturation !== null || $lightness !== null;
        $hasWB = $whiteness !== null || $blackness !== null;

        if ($hasRgb && ($hasSL || $hasWB || $hue !== null)) {
            $format = $hasWB ? 'HWB' : 'HSL';
            throw new SassScriptException("RGB parameters may not be passed along with $format parameters.");
        }

        if ($hasSL && $hasWB) {
            throw new SassScriptException('HSL parameters may not be passed along with HWB parameters.');
        }

        $updateValue = function (float $current, ?float $param, float $max) use ($change, $adjust): float {
            if ($param === null) {
                return $current;
            }

            if ($change) {
                return $param;
            }

            if ($adjust) {
                return NumberUtil::clamp($current + $param, 0, $max);
            }

            return $current + ($param > 0 ? $max - $current : $current) * $param / 100;
        };

        $updateRgb = function (int $current, ?float $param) use ($updateValue): int {
            return NumberUtil::fuzzyRound($updateValue($current, $param, 255));
        };

        if ($hasRgb) {
            return $color->changeRgb(
                $updateRgb($color->getRed(), $red),
                $updateRgb($color->getGreen(), $green),
                $updateRgb($color->getBlue(), $blue),
                $updateValue($color->getAlpha(), $alpha, 1)
            );
        }

        if ($hasWB) {
            return $color->changeHwb(
                $change ? $hue : $color->getHue() + ($hue ?? 0),
                $updateValue($color->getWhiteness(), $whiteness, 100),
                $updateValue($color->getBlackness(), $blackness, 100),
                $updateValue($color->getAlpha(), $alpha, 1)
            );
        }

        if ($hue !== null || $hasSL) {
            return $color->changeHsl(
                $change ? $hue : $color->getHue() + ($hue ?? 0),
                $updateValue($color->getSaturation(), $saturation, 100),
                $updateValue($color->getLightness(), $lightness, 100),
                $updateValue($color->getAlpha(), $alpha, 1)
            );
        }

        if ($alpha !== null) {
            return $color->changeAlpha($updateValue($color->getAlpha(), $alpha, 1));
        }

        return $color;
    }

    /**
     * Returns a string representation of $name called with $arguments, as though
     * it were a plain CSS function.
     *
     * @param Value[] $arguments
     */
    private static function functionString(string $name, array $arguments): SassString
    {
        return new SassString($name . '(' . implode(', ', array_map(fn(Value $argument) => $argument->toCssString(), $arguments)) . ')', false);
    }

    /**
     * @param list<Value> $arguments
     */
    private static function rgbImpl(string $name, array $arguments): Value
    {
        $alpha = $arguments[3] ?? null;

        if ($arguments[0]->isSpecialNumber() || $arguments[1]->isSpecialNumber() || $arguments[2]->isSpecialNumber() || ($alpha?->isSpecialNumber() ?? false)) {
            return self::functionString($name, $arguments);
        }

        $red = $arguments[0]->assertNumber('red');
        $green = $arguments[1]->assertNumber('green');
        $blue = $arguments[2]->assertNumber('blue');

        return SassColor::rgbInternal(
            NumberUtil::fuzzyRound(self::percentageOrUnitless($red, 255, 'red')),
            NumberUtil::fuzzyRound(self::percentageOrUnitless($green, 255, 'green')),
            NumberUtil::fuzzyRound(self::percentageOrUnitless($blue, 255, 'blue')),
            $alpha !== null ? self::percentageOrUnitless($alpha->assertNumber('alpha'), 1, 'alpha') : 1,
            ColorFormatEnum::rgbFunction
        );
    }

    /**
     * @param list<Value> $arguments
     */
    private static function rgbTwoArgsImpl(string $name, array $arguments): Value
    {
        // rgba(var(--foo), 0.5) is valid CSS because --foo might be `123, 456, 789`
        // and functions are parsed after variable substitution.
        if ($arguments[0]->isVar() || (!$arguments[0] instanceof SassColor && $arguments[1]->isVar())) {
            return self::functionString($name, $arguments);
        }

        if ($arguments[1]->isSpecialNumber()) {
            $color = $arguments[0]->assertColor('color');

            return new SassString("$name({$color->getRed()}, {$color->getGreen()}, {$color->getBlue()}, {$arguments[1]->toCssString()})", false);
        }

        $color = $arguments[0]->assertColor('color');
        $alpha = $arguments[1]->assertNumber('alpha');

        return $color->changeAlpha(self::percentageOrUnitless($alpha, 1, 'alpha'));
    }

    /**
     * @param list<Value> $arguments
     */
    private static function hslImpl(string $name, array $arguments): Value
    {
        $alpha = $arguments[3] ?? null;

        if ($arguments[0]->isSpecialNumber() || $arguments[1]->isSpecialNumber() || $arguments[2]->isSpecialNumber() || ($alpha?->isSpecialNumber() ?? false)) {
            return self::functionString($name, $arguments);
        }

        $hue = self::angleValue($arguments[0], 'hue');
        $saturation = $arguments[1]->assertNumber('saturation');
        $lightness = $arguments[2]->assertNumber('lightness');

        self::checkPercent($saturation, 'saturation');
        self::checkPercent($lightness, 'lightness');

        return SassColor::hslInternal(
            $hue,
            NumberUtil::clamp($saturation->getValue(), 0, 100),
            NumberUtil::clamp($lightness->getValue(), 0, 100),
            $alpha !== null ? self::percentageOrUnitless($alpha->assertNumber('alpha'), 1, 'alpha') : 1,
            ColorFormatEnum::hslFunction
        );
    }

    /**
     * Asserts that $angle is a number and returns its value in degrees.
     *
     * Prints a deprecation warning if $angle has a non-angle unit.
     */
    private static function angleValue(Value $angleValue, string $name): float
    {
        $angle = $angleValue->assertNumber($name);

        if ($angle->compatibleWithUnit('deg')) {
            return $angle->coerceValueToUnit('deg');
        }

        Warn::forDeprecation(
            <<<TXT
\$$name: Passing a unit other than deg ($angle) is deprecated.

To preserve current behavior: {$angle->unitSuggestion($name)}

See https://sass-lang.com/d/function-units
TXT,
            Deprecation::functionUnits
        );

        return $angle->getValue();
    }

    private static function checkPercent(SassNumber $number, string $name): void
    {
        if ($number->hasUnit('%')) {
            return;
        }

        Warn::forDeprecation(
            <<<TXT
\$$name: Passing a number without unit % ($number) is deprecated.

To preserve current behavior: {$number->unitSuggestion($name, '%')}

More info: https://sass-lang.com/d/function-units
TXT,
            Deprecation::functionUnits
        );
    }

    /**
     * @param list<string> $argumentNames
     *
     * @return SassString|list<Value>
     */
    private static function parseChannels(string $name, array $argumentNames, Value $channels): SassString|array
    {
        if ($channels->isVar()) {
            return self::functionString($name, [$channels]);
        }

        $originalChannels = $channels;
        $alphaFromSlashList = null;

        if ($channels->getSeparator() === ListSeparator::SLASH) {
            $list = $channels->asList();

            if (\count($list) !== 2) {
                throw new SassScriptException(sprintf(
                    'Only 2 slash-separated elements allowed, but %s %s passed.',
                    \count($list),
                    StringUtil::pluralize('was', \count($list), 'were')
                ));
            }

            $channels = $list[0];
            $alphaFromSlashList = $list[1];

            if (!$alphaFromSlashList->isSpecialNumber()) {
                $alphaFromSlashList->assertNumber('alpha');
            }

            if ($list[0]->isVar()) {
                return self::functionString($name, [$originalChannels]);
            }
        }

        $isCommaSeparated = $channels->getSeparator() === ListSeparator::COMMA;
        $isBracketed = $channels->hasBrackets();

        if ($isCommaSeparated || $isBracketed) {
            $buffer = '$channels must be';
            if ($isBracketed) {
                $buffer .= ' an unbracketed';
            }
            if ($isCommaSeparated) {
                $buffer .= $isBracketed ? ',' : ' a';
                $buffer .= ' space-separated';
            }

            $buffer .= ' list.';

            throw new SassScriptException($buffer);
        }

        $list = $channels->asList();

        if (\count($list) >= 2 && $list[0] instanceof SassString && !$list[0]->hasQuotes() && StringUtil::equalsIgnoreCase($list[0]->getText(), 'from')) {
            return self::functionString($name, [$originalChannels]);
        }

        if (\count($list) > 3) {
            throw new SassScriptException(sprintf(
                'Only 3 elements allowed, but %s were passed',
                \count($list)
            ));
        }

        if (\count($list) < 3) {
            if (IterableUtil::any($list, fn (Value $value) => $value->isVar()) || (\count($list) > 0 && self::isVarSlash($list[0]))) {
                return self::functionString($name, [$originalChannels]);
            }

            $argument = $argumentNames[\count($list)];

            throw new SassScriptException("Missing element $argument.");
        }

        if ($alphaFromSlashList !== null) {
            return [...$list, $alphaFromSlashList];
        }

        if ($list[2] instanceof SassNumber && $list[2]->getAsSlash() !== null) {
            [$channel3, $alpha] = $list[2]->getAsSlash();

            return [$list[0], $list[1], $channel3, $alpha];
        }

        if ($list[2] instanceof SassString && !$list[2]->hasQuotes() && str_contains($list[2]->getText(), '/')) {
            return self::functionString($name, [$channels]);
        }

        return $list;
    }

    /**
     * Returns whether $value is an unquoted string that start with `var(` and
     * contains `/`.
     */
    private static function isVarSlash(Value $value): bool
    {
        return $value instanceof SassString && $value->hasQuotes() && StringUtil::startsWithIgnoreCase($value->getText(), 'var(') && str_contains($value->getText(), '/');
    }

    /**
     * Asserts that $number is a percentage or has no units, and normalizes the
     * value.
     *
     * If $number has no units, its value is clamped to be greater than `0` or
     * less than $max and returned. If $number is a percentage, it's scaled to be
     * within `0` and $max. Otherwise, this throws a {@see SassScriptException}.
     *
     * $name is used to identify the argument in the error message.
     */
    private static function percentageOrUnitless(SassNumber $number, float $max, string $name): float
    {
        if (!$number->hasUnits()) {
            $value = $number->getValue();
        } elseif ($number->hasUnit('%')) {
            $value = $max * $number->getValue() / 100;
        } else {
            throw new SassScriptException("\$$name: Expected $number to have unit \"%\" or no units.");
        }

        return NumberUtil::clamp($value, 0, $max);
    }

    private static function mixColors(SassColor $color1, SassColor $color2, SassNumber $weight): SassColor
    {
        self::checkPercent($weight, 'weight');

        // This algorithm factors in both the user-provided weight (w) and the
        // difference between the alpha values of the two colors (a) to decide how
        // to perform the weighted average of the two RGB values.
        //
        // It works by first normalizing both parameters to be within [-1, 1], where
        // 1 indicates "only use color1", -1 indicates "only use color2", and all
        // values in between indicated a proportionately weighted average.
        //
        // Once we have the normalized variables w and a, we apply the formula
        // (w + a)/(1 + w*a) to get the combined weight (in [-1, 1]) of color1. This
        // formula has two especially nice properties:
        //
        //   * When either w or a are -1 or 1, the combined weight is also that
        //     number (cases where w * a == -1 are undefined, and handled as a
        //     special case).
        //
        //   * When a is 0, the combined weight is w, and vice versa.
        //
        // Finally, the weight of color1 is renormalized to be within [0, 1] and the
        // weight of color2 is given by 1 minus the weight of color1.

        $weightScale = $weight->valueInRange(0, 100, 'weight') / 100;
        $normalizedWeight = $weightScale * 2 - 1;
        $alphaDistance = $color1->getAlpha() - $color2->getAlpha();

        $combinedWeight1 = $normalizedWeight * $alphaDistance == -1
            ? $normalizedWeight
            : ($normalizedWeight + $alphaDistance) / (1 + $normalizedWeight * $alphaDistance);

        $weight1 = ($combinedWeight1 + 1) / 2;
        $weight2 = 1 - $weight1;

        return SassColor::rgb(
            NumberUtil::fuzzyRound($color1->getRed() * $weight1 + $color2->getRed() * $weight2),
            NumberUtil::fuzzyRound($color1->getGreen() * $weight1 + $color2->getGreen() * $weight2),
            NumberUtil::fuzzyRound($color1->getBlue() * $weight1 + $color2->getBlue() * $weight2),
            $color1->getAlpha() * $weightScale + $color2->getAlpha() * (1 -  $weightScale)
        );
    }

    /**
     * @param list<Value> $arguments
     */
    public static function opacify(array $arguments): SassColor
    {
        $color = $arguments[0]->assertColor('color');
        $amount = $arguments[1]->assertNumber('amount');

        return $color->changeAlpha(NumberUtil::clamp($color->getAlpha() + $amount->valueInRangeWithUnit(0, 1, 'amount', ''), 0, 1));
    }

    /**
     * @param list<Value> $arguments
     */
    public static function transparentize(array $arguments): SassColor
    {
        $color = $arguments[0]->assertColor('color');
        $amount = $arguments[1]->assertNumber('amount');

        return $color->changeAlpha(NumberUtil::clamp($color->getAlpha() - $amount->valueInRangeWithUnit(0, 1, 'amount', ''), 0, 1));
    }
}
