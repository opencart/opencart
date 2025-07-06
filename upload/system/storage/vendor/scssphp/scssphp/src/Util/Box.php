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
 * An unmodifiable reference to a value that may be mutated elsewhere.
 *
 * This uses reference equality based on the underlying {@see ModifiableBox}, even
 * when the underlying type uses value equality.
 *
 * @template T
 */
final class Box implements Equatable
{
    /**
     * @var ModifiableBox<T>
     */
    private readonly ModifiableBox $inner;

    /**
     * @param ModifiableBox<T> $inner
     */
    public function __construct(ModifiableBox $inner)
    {
        $this->inner = $inner;
    }

    /**
     * @return T
     */
    public function getValue()
    {
        return $this->inner->getValue();
    }

    public function equals(object $other): bool
    {
        return $other instanceof Box && $this->inner === $other->inner;
    }
}
