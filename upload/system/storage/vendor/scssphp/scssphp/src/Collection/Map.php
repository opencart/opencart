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

namespace ScssPhp\ScssPhp\Collection;

use ScssPhp\ScssPhp\Value\Value;

/**
 * A map using Sass values as keys based on Value::equals.
 *
 * The map can be either modifiable or unmodifiable. For unmodifiable
 * maps, all mutators will throw a LogicException.
 *
 * Iteration preserves the order in which keys have been inserted.
 *
 * @template T
 * @template-implements \IteratorAggregate<Value, T>
 */
final class Map implements \Countable, \IteratorAggregate
{
    private bool $modifiable = true;

    // TODO implement a better internal storage to allow reading keys in O(1).

    /**
     * @var array<int, array{Value, T}>
     */
    private array $pairs = [];

    /**
     * Returns a modifiable version of the Map.
     *
     * @template V
     * @param Map<V> $map
     *
     * @return Map<V>
     */
    public static function of(Map $map): Map
    {
        $modifiableMap = clone $map;
        $modifiableMap->modifiable = true;

        return $modifiableMap;
    }

    /**
     * Returns an unmodifiable version of the Map.
     *
     * All mutators will throw a LogicException when trying to use them.
     *
     * @template V
     * @param Map<V> $map
     *
     * @return Map<V>
     */
    public static function unmodifiable(Map $map): Map
    {
        if (!$map->modifiable) {
            return $map;
        }

        $unmodifiableMap = clone $map;
        $unmodifiableMap->modifiable = false;

        return $unmodifiableMap;
    }

    public function getIterator(): \Traversable
    {
        foreach ($this->pairs as $pair) {
            yield $pair[0] => $pair[1];
        }
    }

    public function count(): int
    {
        return \count($this->pairs);
    }

    /**
     * The value for the given key, or `null` if $key is not in the map.
     *
     * @return T|null
     */
    public function get(Value $key)
    {
        foreach ($this->pairs as $pair) {
            if ($key->equals($pair[0])) {
                return $pair[1];
            }
        }

        return null;
    }

    public function containsKey(Value $key): bool
    {
        return $this->get($key) !== null;
    }

    /**
     * Associates the key with the given value.
     *
     * If the key was already in the map, its associated value is changed.
     * Otherwise the key/value pair is added to the map.
     *
     * @param T $value
     */
    public function put(Value $key, $value): void
    {
        $this->assertModifiable();

        foreach ($this->pairs as $i => $pair) {
            if ($key->equals($pair[0])) {
                $this->pairs[$i][1] = $value;

                return;
            }
        }

        $this->pairs[] = [$key, $value];
    }

    /**
     * Removes $key and its associated value, if present, from the map.
     *
     * Returns the value associated with `key` before it was removed.
     * Returns `null` if `key` was not in the map.
     *
     * Note that some maps allow `null` as a value,
     * so a returned `null` value doesn't always mean that the key was absent.
     *
     * @return T|null
     */
    public function remove(Value $key)
    {
        $this->assertModifiable();

        foreach ($this->pairs as $i => $pair) {
            if ($key->equals($pair[0])) {
                unset($this->pairs[$i]);

                return $pair[1];
            }
        }

        return null;
    }

    /**
     * @return list<Value>
     */
    public function keys(): array
    {
        $keys = [];

        foreach ($this->pairs as $pair) {
            $keys[] = $pair[0];
        }

        return $keys;
    }

    /**
     * @return list<T>
     */
    public function values(): array
    {
        $values = [];

        foreach ($this->pairs as $pair) {
            $values[] = $pair[1];
        }

        return $values;
    }

    private function assertModifiable(): void
    {
        if (!$this->modifiable) {
            throw new \LogicException('Mutating an unmodifiable Map is not supported. Use Map::of to create a modifiable copy.');
        }
    }
}
