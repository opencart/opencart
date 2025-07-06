<?php

/**
 * SCSSPHP
 *
 * @copyright 2018-2020 Anthon Pang
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 * @link http://scssphp.github.io/scssphp
 */

namespace ScssPhp\ScssPhp\Util;

/**
 * @internal
 */
final class IterableUtil
{
    /**
     * @template T
     *
     * @param iterable<T>       $list
     * @param callable(T): bool $callback
     *
     * @param-immediately-invoked-callable $callback
     */
    public static function any(iterable $list, callable $callback): bool
    {
        foreach ($list as $item) {
            if ($callback($item)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @template T
     *
     * @param iterable<T>       $list
     * @param callable(T): bool $callback
     *
     * @param-immediately-invoked-callable $callback
     */
    public static function every(iterable $list, callable $callback): bool
    {
        foreach ($list as $item) {
            if (!$callback($item)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @template T
     *
     * @param iterable<T> $iterable
     * @return T|null
     */
    public static function firstOrNull(iterable $iterable): mixed
    {
        foreach ($iterable as $item) {
            return $item;
        }

        return null;
    }

    /**
     * Returns the first `T` returned by $callback for an element of $iterable,
     * or `null` if it returns `null` for every element.
     *
     * @template T
     * @template E
     * @param iterable<E> $iterable
     * @param callable(E): (T|null) $callback
     *
     * @return T|null
     *
     * @param-immediately-invoked-callable $callback
     */
    public static function search(iterable $iterable, callable $callback)
    {
        foreach ($iterable as $element) {
            $value = $callback($element);

            if ($value !== null) {
                return $value;
            }
        }

        return null;
    }
}
