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

namespace ScssPhp\ScssPhp;

use ScssPhp\ScssPhp\Collection\Map;
use ScssPhp\ScssPhp\Logger\QuietLogger;
use ScssPhp\ScssPhp\Node\Number;
use ScssPhp\ScssPhp\Value\ListSeparator;
use ScssPhp\ScssPhp\Value\SassBoolean;
use ScssPhp\ScssPhp\Value\SassList;
use ScssPhp\ScssPhp\Value\SassMap;
use ScssPhp\ScssPhp\Value\SassNull;
use ScssPhp\ScssPhp\Value\SassNumber;
use ScssPhp\ScssPhp\Value\SassString;
use ScssPhp\ScssPhp\Value\Value;

final class ValueConverter
{
    // Prevent instantiating it
    private function __construct()
    {
    }

    /**
     * Parses a value from a Scss source string.
     *
     * The returned value is guaranteed to be supported by the
     * Compiler methods for registering custom variables. No other
     * guarantee about it is provided. It should be considered
     * opaque values by the caller.
     */
    public static function parseValue(string $source): Value
    {
        $value = null;

        $compiler = new Compiler();
        $compiler->setLogger(new QuietLogger());
        $compiler->registerFunction('scssphp-parse-value', function (array $arguments) use (&$value): Value {
            \assert(\count($arguments) === 1);
            \assert($arguments[0] instanceof Value);
            $value = $arguments[0];

            return SassNull::create();
        }, ['arg']);
        $scss = <<<SCSS
        a {b: scssphp-parse-value(($source))}
        SCSS;

        $compiler->compileString($scss);

        \assert($value !== null);

        return $value;
    }

    /**
     * Converts a PHP value to a Sass value
     *
     * The returned value is guaranteed to be supported by the
     * Compiler methods for registering custom variables. No other
     * guarantee about it is provided. It should be considered
     * opaque values by the caller.
     */
    public static function fromPhp(mixed $value): Value
    {
        if ($value instanceof Value) {
            return $value;
        }

        if ($value instanceof Number) {
            return SassNumber::withUnits($value->getDimension(), $value->getNumeratorUnits(), $value->getDenominatorUnits());
        }

        if ($value === null) {
            return SassNull::create();
        }

        if ($value === true) {
            return SassBoolean::create(true);
        }

        if ($value === false) {
            return SassBoolean::create(false);
        }

        if ($value === '') {
            return new SassString('');
        }

        if (\is_int($value) || \is_float($value)) {
            return SassNumber::create($value);
        }

        if (\is_string($value)) {
            return new SassString($value);
        }

        if (\is_array($value)) {
            if (array_is_list($value)) {
                $result = [];
                foreach ($value as $val) {
                    $result[] = self::fromPhp($val);
                }

                return new SassList($result, \count($result) > 0 ? ListSeparator::COMMA : ListSeparator::UNDECIDED);
            }

            /** @var Map<Value> $map */
            $map = new Map();
            foreach ($value as $key => $val) {
                $map->put(new SassString($key), self::fromPhp($val));
            }

            return SassMap::create($map);
        }

        throw new \InvalidArgumentException(sprintf('Cannot convert the value of type "%s" to a Sass value.', get_debug_type($value)));
    }
}
