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
 * A mutable reference to a (presumably immutable) value.
 *
 * This always uses reference equality, even when the underlying type uses
 * value equality.
 *
 * @template T
 *
 * @internal
 */
final class ModifiableBox
{
    /**
     * @var T
     */
    private mixed $value;

    /**
     * @param T $value
     */
    public function __construct(mixed $value)
    {
        $this->value = $value;
    }

    /**
     * @return T
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param T $value
     */
    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }

    /**
     * Returns an unmodifiable reference to this box.
     *
     * The underlying modifiable box may still be modified.
     *
     * @return Box<T>
     */
    public function seal(): Box
    {
        return new Box($this);
    }
}
