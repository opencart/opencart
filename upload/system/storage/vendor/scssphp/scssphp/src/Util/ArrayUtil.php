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

namespace ScssPhp\ScssPhp\Util;

/**
 * @internal
 */
final class ArrayUtil
{
    /**
     * Reduces a collection to a single value by iteratively combining elements
     * of the collection using the provided function.
     *
     * The array must have at least one element.
     * If it has only one element, that element is returned.
     *
     * Otherwise this method starts with the first element from the array,
     * and then combines it with the remaining elements in iteration order.
     *
     * @template T
     *
     * @param non-empty-array<T> $items
     * @param callable(T, T): T $combine
     * @return T
     *
     * @param-immediately-invoked-callable $combine
     */
    public static function reduce(array $items, callable $combine)
    {
        if (\count($items) === 0) {
            throw new \LogicException('Cannot reduce an empty array');
        }

        $first = array_shift($items);

        return array_reduce($items, $combine, $first);
    }
}
