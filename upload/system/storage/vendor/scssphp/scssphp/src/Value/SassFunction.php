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

namespace ScssPhp\ScssPhp\Value;

use ScssPhp\ScssPhp\SassCallable\SassCallable;
use ScssPhp\ScssPhp\Util\EquatableUtil;
use ScssPhp\ScssPhp\Visitor\ValueVisitor;

/**
 * A SassScript function reference.
 *
 * A function reference captures a function from the local environment so that
 * it may be passed between modules.
 */
final class SassFunction extends Value
{
    private readonly SassCallable $callable;

    /**
     * @internal
     */
    public function __construct(SassCallable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * @internal
     */
    public function getCallable(): SassCallable
    {
        return $this->callable;
    }

    public function accept(ValueVisitor $visitor)
    {
        return $visitor->visitFunction($this);
    }

    public function assertFunction(?string $name = null): SassFunction
    {
        return $this;
    }

    public function equals(object $other): bool
    {
        return $other instanceof SassFunction && EquatableUtil::equals($this->callable, $other->callable);
    }
}
