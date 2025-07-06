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

use ScssPhp\ScssPhp\Collection\Map;
use ScssPhp\ScssPhp\Exception\SassScriptException;
use ScssPhp\ScssPhp\Util\ListUtil;
use ScssPhp\ScssPhp\Value\ListSeparator;
use ScssPhp\ScssPhp\Value\SassBoolean;
use ScssPhp\ScssPhp\Value\SassList;
use ScssPhp\ScssPhp\Value\SassMap;
use ScssPhp\ScssPhp\Value\SassNull;
use ScssPhp\ScssPhp\Value\Value;

/**
 * @internal
 */
class MapFunctions
{
    /**
     * @param list<Value> $arguments
     */
    public static function get(array $arguments): Value
    {
        $map = $arguments[0]->assertMap('map');
        $keys = [$arguments[1], ...$arguments[2]->asList()];

        foreach (ListUtil::exceptLast($keys) as $key) {
            $value = $map->getContents()->get($key);

            if (!$value instanceof SassMap) {
                return SassNull::create();
            }

            $map = $value;
        }

        return $map->getContents()->get(ListUtil::last($keys)) ?? SassNull::create();
    }

    /**
     * @param list<Value> $arguments
     */
    public static function mergeTwoArgs(array $arguments): Value
    {
        $map1 = $arguments[0]->assertMap('map1');
        $map2 = $arguments[1]->assertMap('map2');

        $result = Map::of($map1->getContents());

        foreach ($map2->getContents() as $key => $value) {
            $result->put($key, $value);
        }

        return SassMap::create($result);
    }

    /**
     * @param list<Value> $arguments
     */
    public static function mergeVariadic(array $arguments): Value
    {
        $map1 = $arguments[0]->assertMap('map1');
        $args = $arguments[1]->asList();

        if ($args === []) {
            throw new SassScriptException('Expected $args to contain a key.');
        }

        if (\count($args) === 1) {
            throw new SassScriptException('Expected $args to contain a map.');
        }

        $keys = ListUtil::exceptLast($args);
        $map2 = ListUtil::last($args)->assertMap('map2');

        return self::modify($map1, $keys, function (Value $oldValue) use ($map2) {
            $nestedMap = $oldValue->tryMap();
            if ($nestedMap === null) {
                return $map2;
            }

            $result = Map::of($nestedMap->getContents());

            foreach ($map2->getContents() as $key => $value) {
                $result->put($key, $value);
            }

            return SassMap::create($result);
        });
    }

    /**
     * @param list<Value> $arguments
     */
    public static function removeNoKeys(array $arguments): Value
    {
        return $arguments[0]->assertMap('map');
    }

    /**
     * @param list<Value> $arguments
     */
    public static function remove(array $arguments): Value
    {
        $map = $arguments[0]->assertMap('map');
        $keys = [$arguments[1], ...$arguments[2]->asList()];

        $mutableMap = Map::of($map->getContents());

        foreach ($keys as $key) {
            $mutableMap->remove($key);
        }

        return SassMap::create($mutableMap);
    }

    /**
     * @param list<Value> $arguments
     */
    public static function keys(array $arguments): Value
    {
        return new SassList($arguments[0]->assertMap('map')->getContents()->keys(), ListSeparator::COMMA);
    }

    /**
     * @param list<Value> $arguments
     */
    public static function values(array $arguments): Value
    {
        return new SassList($arguments[0]->assertMap('map')->getContents()->values(), ListSeparator::COMMA);
    }

    /**
     * @param list<Value> $arguments
     */
    public static function hasKey(array $arguments): Value
    {
        $map = $arguments[0]->assertMap('map');
        $keys = [$arguments[1], ...$arguments[2]->asList()];

        foreach (ListUtil::exceptLast($keys) as $key) {
            $value = $map->getContents()->get($key);

            if (!$value instanceof SassMap) {
                return SassBoolean::create(false);
            }

            $map = $value;
        }

        return SassBoolean::create($map->getContents()->containsKey(ListUtil::last($keys)));
    }

    /**
     * Updates the specified value in $map by applying the $modify callback to
     * it, then returns the resulting map.
     *
     * If more than one key is provided, this means the map targeted for update is
     * nested within $map. The multiple $keys form a path of nested maps that
     * leads to the targeted value, which is passed to $modify.
     *
     * If any value along the path (other than the last one) is not a map and
     * $addNesting is `true`, this creates nested maps to match $keys and passes
     * {@see SassNull} to $modify. Otherwise, this fails and returns $map with no
     * changes.
     *
     * If no keys are provided, this passes $map directly to modify and returns
     * the result.
     *
     * @param Value[] $keys
     * @param callable(Value $old): Value $modify
     *
     * @param-immediately-invoked-callable $modify
     */
    private static function modify(SassMap $map, array $keys, callable $modify, bool $addNesting = true): Value
    {
        $iterator = new \ArrayIterator($keys);

        $modifyNestedMap = function (SassMap $map) use ($iterator, $modify, $addNesting, &$modifyNestedMap): SassMap {
            $mutableMap = Map::of($map->getContents());
            $key = $iterator->current();

            $iterator->next();
            if (!$iterator->valid()) {
                $mutableMap->put($key, $modify($mutableMap->get($key) ?? SassNull::create()));
                return SassMap::create($mutableMap);
            }

            $nestedMap = $mutableMap->get($key)?->tryMap();
            if ($nestedMap === null && !$addNesting) {
                return SassMap::create($mutableMap);
            }

            $mutableMap->put($key, $modifyNestedMap($nestedMap ?? SassMap::createEmpty()));
            return SassMap::create($mutableMap);
        };

        $iterator->rewind();

        return $iterator->valid() ? $modifyNestedMap($map) : $modify($map);
    }
}
