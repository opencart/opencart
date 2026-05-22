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

use League\Uri\Uri;
use ScssPhp\ScssPhp\SassCallable\BuiltInCallable;
use ScssPhp\ScssPhp\Value\Value;

/**
 * @internal
 */
class FunctionRegistry
{
    /**
     * @var array<string, array{overloads: array<string, callable(list<Value>): Value>, url?: string, canonical_name?: string}>
     */
    private const BUILTIN_FUNCTIONS = [
        // sass:color
        'red' => ['overloads' => ['$color' => [ColorFunctions::class, 'red']], 'url' => 'sass:color'],
        'green' => ['overloads' => ['$color' => [ColorFunctions::class, 'green']], 'url' => 'sass:color'],
        'blue' => ['overloads' => ['$color' => [ColorFunctions::class, 'blue']], 'url' => 'sass:color'],
        'mix' => ['overloads' => ['$color1, $color2, $weight: 50%' => [ColorFunctions::class, 'mix']], 'url' => 'sass:color'],
        'rgb' => ['overloads' => [
            '$red, $green, $blue, $alpha' => [ColorFunctions::class, 'rgb'],
            '$red, $green, $blue' => [ColorFunctions::class, 'rgb'],
            '$color, $alpha' => [ColorFunctions::class, 'rgbTwoArgs'],
            '$channels' => [ColorFunctions::class, 'rgbOneArgs'],
        ]],
        'rgba' => ['overloads' => [
            '$red, $green, $blue, $alpha' => [ColorFunctions::class, 'rgba'],
            '$red, $green, $blue' => [ColorFunctions::class, 'rgba'],
            '$color, $alpha' => [ColorFunctions::class, 'rgbaTwoArgs'],
            '$channels' => [ColorFunctions::class, 'rgbaOneArgs'],
        ]],
        'invert' => ['overloads' => ['$color, $weight: 100%' => [ColorFunctions::class, 'invert']], 'url' => 'sass:color'],
        'hue' => ['overloads' => ['$color' => [ColorFunctions::class, 'hue']], 'url' => 'sass:color'],
        'saturation' => ['overloads' => ['$color' => [ColorFunctions::class, 'saturation']], 'url' => 'sass:color'],
        'lightness' => ['overloads' => ['$color' => [ColorFunctions::class, 'lightness']], 'url' => 'sass:color'],
        'complement' => ['overloads' => ['$color' => [ColorFunctions::class, 'complement']], 'url' => 'sass:color'],
        'hsl' => ['overloads' => [
            '$hue, $saturation, $lightness, $alpha' => [ColorFunctions::class, 'hsl'],
            '$hue, $saturation, $lightness' => [ColorFunctions::class, 'hsl'],
            '$hue, $saturation' => [ColorFunctions::class, 'hslTwoArgs'],
            '$channels' => [ColorFunctions::class, 'hslOneArgs'],
        ]],
        'hsla' => ['overloads' => [
            '$hue, $saturation, $lightness, $alpha' => [ColorFunctions::class, 'hsla'],
            '$hue, $saturation, $lightness' => [ColorFunctions::class, 'hsla'],
            '$hue, $saturation' => [ColorFunctions::class, 'hslaTwoArgs'],
            '$channels' => [ColorFunctions::class, 'hslaOneArgs'],
        ]],
        'grayscale' => ['overloads' => ['$color' => [ColorFunctions::class, 'grayscale']], 'url' => 'sass:color'],
        'adjust-hue' => ['overloads' => ['$color, $degrees' => [ColorFunctions::class, 'adjustHue']], 'url' => 'sass:color'],
        'lighten' => ['overloads' => ['$color, $amount' => [ColorFunctions::class, 'lighten']], 'url' => 'sass:color'],
        'darken' => ['overloads' => ['$color, $amount' => [ColorFunctions::class, 'darken']], 'url' => 'sass:color'],
        'saturate' => ['overloads' => [
            '$amount' => [ColorFunctions::class, 'saturateCss'],
            '$color, $amount' => [ColorFunctions::class, 'saturate'],
        ]],
        'desaturate' => ['overloads' => ['$color, $amount' => [ColorFunctions::class, 'desaturate']], 'url' => 'sass:color'],
        'opacify' => ['overloads' => ['$color, $amount' => [ColorFunctions::class, 'opacify']], 'url' => 'sass:color'],
        'fade-in' => ['overloads' => ['$color, $amount' => [ColorFunctions::class, 'opacify']], 'url' => 'sass:color'],
        'transparentize' => ['overloads' => ['$color, $amount' => [ColorFunctions::class, 'transparentize']], 'url' => 'sass:color'],
        'fade-out' => ['overloads' => ['$color, $amount' => [ColorFunctions::class, 'transparentize']], 'url' => 'sass:color'],
        'alpha' => ['overloads' => [
            '$color' => [ColorFunctions::class, 'alpha'],
            '$args...' => [ColorFunctions::class, 'alphaMicrosoft'],
        ]],
        'opacity' => ['overloads' => ['$color' => [ColorFunctions::class, 'opacity']], 'url' => 'sass:color'],
        'ie-hex-str' => ['overloads' => ['$color' => [ColorFunctions::class, 'ieHexStr']], 'url' => 'sass:color'],
        'adjust-color' => ['overloads' => ['$color, $kwargs...' => [ColorFunctions::class, 'adjust']], 'url' => 'sass:color', 'canonical_name' => 'adjust'],
        'scale-color' => ['overloads' => ['$color, $kwargs...' => [ColorFunctions::class, 'scale']], 'url' => 'sass:color', 'canonical_name' => 'scale'],
        'change-color' => ['overloads' => ['$color, $kwargs...' => [ColorFunctions::class, 'change']], 'url' => 'sass:color', 'canonical_name' => 'change'],
        // sass:list
        'length' => ['overloads' => ['$list' => [ListFunctions::class, 'length']], 'url' => 'sass:list'],
        'nth' => ['overloads' => ['$list, $n' => [ListFunctions::class, 'nth']], 'url' => 'sass:list'],
        'set-nth' => ['overloads' => ['$list, $n, $value' => [ListFunctions::class, 'setNth']], 'url' => 'sass:list'],
        'join' => ['overloads' => ['$list1, $list2, $separator: auto, $bracketed: auto' => [ListFunctions::class, 'join']], 'url' => 'sass:list'],
        'append' => ['overloads' => ['$list, $val, $separator: auto' => [ListFunctions::class, 'append']], 'url' => 'sass:list'],
        'zip' => ['overloads' => ['$lists...' => [ListFunctions::class, 'zip']], 'url' => 'sass:list'],
        'index' => ['overloads' => ['$list, $value' => [ListFunctions::class, 'index']], 'url' => 'sass:list'],
        'is-bracketed' => ['overloads' => ['$list' => [ListFunctions::class, 'isBracketed']], 'url' => 'sass:list'],
        'list-separator' => ['overloads' => ['$list' => [ListFunctions::class, 'separator']], 'url' => 'sass:list', 'canonical_name' => 'separator'],
        // sass:map
        'map-get' => ['overloads' => ['$map, $key, $keys...' => [MapFunctions::class, 'get']], 'url' => 'sass:map', 'canonical_name' => 'get'],
        'map-merge' => ['overloads' => [
            '$map1, $map2' => [MapFunctions::class, 'mergeTwoArgs'],
            '$map1, $args...' => [MapFunctions::class, 'mergeVariadic'],
        ], 'canonical_name' => 'merge'],
        'map-remove' => ['overloads' => [
            // Because the signature below has an explicit `$key` argument, it doesn't
            // allow zero keys to be passed. We want to allow that case, so we add an
            // explicit overload for it.
            '$map' => [MapFunctions::class, 'removeNoKeys'],
            // The first argument has special handling so that the $key parameter can be
            // passed by name.
            '$map, $key, $keys...' => [MapFunctions::class, 'remove'],
        ], 'canonical_name' => 'remove'],
        'map-keys' => ['overloads' => ['$map' => [MapFunctions::class, 'keys']], 'url' => 'sass:map', 'canonical_name' => 'keys'],
        'map-values' => ['overloads' => ['$map' => [MapFunctions::class, 'values']], 'url' => 'sass:map', 'canonical_name' => 'values'],
        'map-has-key' => ['overloads' => ['$map, $key, $keys...' => [MapFunctions::class, 'hasKey']], 'url' => 'sass:map', 'canonical_name' => 'has-key'],
        // sass:math
        'abs' => ['overloads' => ['$number' => [MathFunctions::class, 'abs']], 'url' => 'sass:math'],
        'ceil' => ['overloads' => ['$number' => [MathFunctions::class, 'ceil']], 'url' => 'sass:math'],
        'floor' => ['overloads' => ['$number' => [MathFunctions::class, 'floor']], 'url' => 'sass:math'],
        'max' => ['overloads' => ['$numbers...' => [MathFunctions::class, 'max']], 'url' => 'sass:math'],
        'min' => ['overloads' => ['$numbers...' => [MathFunctions::class, 'min']], 'url' => 'sass:math'],
        'random' => ['overloads' => ['$limit: null' => [MathFunctions::class, 'random']], 'url' => 'sass:math'],
        'percentage' => ['overloads' => ['$number' => [MathFunctions::class, 'percentage']], 'url' => 'sass:math'],
        'round' => ['overloads' => ['$number' => [MathFunctions::class, 'round']], 'url' => 'sass:math'],
        'unit' => ['overloads' => ['$number' => [MathFunctions::class, 'unit']], 'url' => 'sass:math'],
        'comparable' => ['overloads' => ['$number1, $number2' => [MathFunctions::class, 'compatible']], 'url' => 'sass:math', 'canonical_name' => 'compatible'],
        'unitless' => ['overloads' => ['$number' => [MathFunctions::class, 'isUnitless']], 'url' => 'sass:math', 'canonical_name' => 'is-unitless'],
        // sass:meta
        'feature-exists' => ['overloads' => ['$feature' => [MetaFunctions::class, 'featureExists']], 'url' => 'sass:meta'],
        'inspect' => ['overloads' => ['$value' => [MetaFunctions::class, 'inspect']], 'url' => 'sass:meta'],
        'type-of' => ['overloads' => ['$value' => [MetaFunctions::class, 'typeof']], 'url' => 'sass:meta'],
        'keywords' => ['overloads' => ['$args' => [MetaFunctions::class, 'keywords']], 'url' => 'sass:meta'],
        // sass:selector
        'is-superselector' => ['overloads' => ['$super, $sub' => [SelectorFunctions::class, 'isSuperselector']], 'url' => 'sass:selector'],
        'simple-selectors' => ['overloads' => ['$selector' => [SelectorFunctions::class, 'simpleSelectors']], 'url' => 'sass:selector'],
        'selector-parse' => ['overloads' => ['$selector' => [SelectorFunctions::class, 'parse']], 'url' => 'sass:selector', 'canonical_name' => 'parse'],
        'selector-nest' => ['overloads' => ['$selectors...' => [SelectorFunctions::class, 'nest']], 'url' => 'sass:selector', 'canonical_name' => 'nest'],
        'selector-append' => ['overloads' => ['$selectors...' => [SelectorFunctions::class, 'append']], 'url' => 'sass:selector', 'canonical_name' => 'append'],
        'selector-extend' => ['overloads' => ['$selector, $extendee, $extender' => [SelectorFunctions::class, 'extend']], 'url' => 'sass:selector', 'canonical_name' => 'extend'],
        'selector-replace' => ['overloads' => ['$selector, $original, $replacement' => [SelectorFunctions::class, 'replace']], 'url' => 'sass:selector', 'canonical_name' => 'replace'],
        'selector-unify' => ['overloads' => ['$selector1, $selector2' => [SelectorFunctions::class, 'unify']], 'url' => 'sass:selector', 'canonical_name' => 'unify'],
        // sass:string
        'unquote' => ['overloads' => ['$string' => [StringFunctions::class, 'unquote']], 'url' => 'sass:string'],
        'quote' => ['overloads' => ['$string' => [StringFunctions::class, 'quote']], 'url' => 'sass:string'],
        'to-upper-case' => ['overloads' => ['$string' => [StringFunctions::class, 'toUpperCase']], 'url' => 'sass:string'],
        'to-lower-case' => ['overloads' => ['$string' => [StringFunctions::class, 'toLowerCase']], 'url' => 'sass:string'],
        'unique-id' => ['overloads' => ['' => [StringFunctions::class, 'uniqueId']], 'url' => 'sass:string'],
        'str-length' => ['overloads' => ['$string' => [StringFunctions::class, 'length']], 'url' => 'sass:string', 'canonical_name' => 'length'],
        'str-insert' => ['overloads' => ['$string, $insert, $index' => [StringFunctions::class, 'insert']], 'url' => 'sass:string', 'canonical_name' => 'insert'],
        'str-index' => ['overloads' => ['$string, $substring' => [StringFunctions::class, 'index']], 'url' => 'sass:string', 'canonical_name' => 'index'],
        'str-slice' => ['overloads' => ['$string, $start-at, $end-at: -1' => [StringFunctions::class, 'slice']], 'url' => 'sass:string', 'canonical_name' => 'slice'],
        // special
        // This is only invoked using `call()`. Hand-authored `if()`s are parsed as IfExpression.
        'if' => ['overloads' => ['$condition, $if-true, $if-false' => [self::class, 'if']]],
    ];

    /**
     * Special meta functions defined directly in the {@see EvaluateVisitor} constructor
     */
    private const SPECIAL_META_GLOBAL_FUNCTIONS = [
        'global-variable-exists',
        'variable-exists',
        'function-exists',
        'mixin-exists',
        'content-exists',
        'get-function',
        'get-mixin',
        'call',
    ];

    public static function has(string $name): bool
    {
        return isset(self::BUILTIN_FUNCTIONS[$name]);
    }

    public static function get(string $name): BuiltInCallable
    {
        if (!isset(self::BUILTIN_FUNCTIONS[$name])) {
            throw new \InvalidArgumentException("There is no builtin function named $name.");
        }

        $url = isset(self::BUILTIN_FUNCTIONS[$name]['url']) ? Uri::new(self::BUILTIN_FUNCTIONS[$name]['url']) : null;

        $callable = BuiltInCallable::overloadedFunction(self::BUILTIN_FUNCTIONS[$name]['canonical_name'] ?? $name, self::BUILTIN_FUNCTIONS[$name]['overloads'], $url);

        if (isset(self::BUILTIN_FUNCTIONS[$name]['canonical_name'])) {
            $callable = $callable->withName($name);
        }

        return $callable;
    }

    public static function isBuiltinFunction(string $name): bool
    {
        return isset(self::BUILTIN_FUNCTIONS[$name]) || \in_array($name, self::SPECIAL_META_GLOBAL_FUNCTIONS, true);
    }

    /**
     * @param list<Value> $arguments
     */
    public static function if(array $arguments): Value
    {
        return $arguments[0]->isTruthy() ? $arguments[1] : $arguments[2];
    }
}
