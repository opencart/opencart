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
final class ListUtil
{
    /**
     * Flattens the first level of nested arrays in $queues.
     *
     * The return value is ordered first by index in the nested iterable, then by
     * the index *of* that iterable in $queues. For example,
     * `flattenVertically([["1a", "1b"], ["2a", "2b"]])` returns `["1a", "2a",
     * "1b", "2b"]`.
     *
     * @template T
     *
     * @param list<list<T>> $queues
     *
     * @return list<T>
     */
    public static function flattenVertically(array $queues): array
    {
        if (\count($queues) === 1) {
            return $queues[0];
        }

        $result = [];

        while (!empty($queues)) {
            foreach ($queues as $i => &$queue) {
                $item = array_shift($queue);

                if ($item === null) {
                    unset($queues[$i]);
                } else {
                    $result[] = $item;
                }
            }
            unset($queue);
        }

        return $result;
    }

    /**
     * Returns the longest common subsequence between $list1 and $list2.
     *
     * If there are more than one equally long common subsequence, returns the one
     * which starts first in $list1.
     *
     * If $select is passed, it's used to check equality between elements in each
     * list. If it returns `null`, the elements are considered unequal; otherwise,
     * it should return the element to include in the return value.
     *
     * @template T
     *
     * @param list<T>                         $list1
     * @param list<T>                         $list2
     * @param (callable(T, T): (T|null))|null $select
     *
     * @return list<T>
     */
    public static function longestCommonSubsequence(array $list1, array $list2, ?callable $select = null): array
    {
        if ($select === null) {
            $select = fn($element1, $element2) => EquatableUtil::equals($element1, $element2) ? $element1 : null;
        }

        $lengths = array_fill(0, \count($list1) + 1, array_fill(0, \count($list2) + 1, 0));
        $selections = array_fill(0, \count($list1) + 1, array_fill(0, \count($list2) + 1, null));

        for ($i = 0; $i < \count($list1); $i++) {
            for ($j = 0; $j < \count($list2); $j++) {
                $selection = $select($list1[$i], $list2[$j]);
                $selections[$i][$j] = $selection;
                $lengths[$i + 1][$j + 1] = $selection === null
                    ? max($lengths[$i + 1][$j], $lengths[$i][$j + 1])
                    : $lengths[$i][$j] + 1;
            }
        }

        /**
         * @param int $i
         * @param int $j
         * @return list<T>
         */
        $backtrack = function (int $i, int $j) use ($selections, $lengths, &$backtrack) {
            if ($i === -1 || $j === -1) {
                return [];
            }

            $selection = $selections[$i][$j];

            if ($selection !== null) {
                $selected = $backtrack($i - 1, $j - 1);
                $selected[] = $selection;

                return $selected;
            }

            return $lengths[$i + 1][$j] > $lengths[$i][$j + 1]
                ? $backtrack($i, $j - 1)
                : $backtrack($i - 1, $j);
        };

        return $backtrack(\count($list1) - 1, \count($list2) - 1);
    }

    /**
     * @template T
     *
     * @param list<T> $list
     *
     * @return T
     */
    public static function last(array $list)
    {
        $count = count($list);

        if ($count === 0) {
            throw new \LogicException('The list may not be empty.');
        }

        return $list[$count - 1];
    }

    /**
     * @template T
     *
     * @param list<T> $list
     *
     * @return list<T>
     */
    public static function exceptLast(array $list): array
    {
        $count = count($list);

        if ($count === 0) {
            throw new \LogicException('The list may not be empty.');
        }

        return array_slice($list, 0, $count - 1);
    }
}
