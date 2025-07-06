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

use ScssPhp\ScssPhp\Ast\Selector\SimpleSelector;

/**
 * @template T
 * @template-extends \SplObjectStorage<SimpleSelector, T>
 *
 * @internal
 */
final class SimpleSelectorMap extends \SplObjectStorage
{
    public function getHash(object $object): string
    {
        \assert($object instanceof SimpleSelector);
        // For SimpleSelector, selectors that are equal by value semantic are exactly the ones that have the same string representation.
        return (string) $object;
    }
}
