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

namespace ScssPhp\ScssPhp\Extend;

use ScssPhp\ScssPhp\Ast\Selector\ComplexSelector;

/**
 * @template T
 * @template-extends \SplObjectStorage<ComplexSelector, T>
 *
 * @internal
 */
final class ComplexSelectorMap extends \SplObjectStorage
{
    public function getHash(object $object): string
    {
        \assert($object instanceof ComplexSelector);
        // For ComplexSelector, selectors that are equal by value semantic are exactly the ones that have the same string representation.
        return (string) $object;
    }

    /**
     * @return iterable<T>
     */
    public function getValues(): iterable
    {
        foreach ($this as $selector) {
            yield $this[$selector];
        }
    }
}
