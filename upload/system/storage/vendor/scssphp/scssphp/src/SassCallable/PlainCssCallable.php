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

namespace ScssPhp\ScssPhp\SassCallable;

use ScssPhp\ScssPhp\Util\Equatable;

/**
 * A callable that emits a plain CSS function.
 *
 * This can't be used for mixins.
 *
 * @internal
 */
final class PlainCssCallable implements SassCallable, Equatable
{
    private readonly string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function equals(object $other): bool
    {
        return $other instanceof PlainCssCallable && $this->name === $other->name;
    }
}
