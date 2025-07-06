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

use ScssPhp\ScssPhp\Visitor\ValueVisitor;

/**
 * A SassScript boolean value.
 */
final class SassBoolean extends Value
{
    private static SassBoolean $trueInstance;

    private static SassBoolean $falseInstance;

    private readonly bool $value;

    public static function create(bool $value): SassBoolean
    {
        if ($value) {
            return self::$trueInstance ??= new self(true);
        }

        return self::$falseInstance ??= new self(false);
    }

    private function __construct(bool $value)
    {
        $this->value = $value;
    }

    public function getValue(): bool
    {
        return $this->value;
    }

    public function isTruthy(): bool
    {
        return $this->value;
    }

    public function accept(ValueVisitor $visitor)
    {
        return $visitor->visitBoolean($this);
    }

    public function assertBoolean(?string $name = null): SassBoolean
    {
        return $this;
    }

    public function unaryNot(): Value
    {
        return self::create(!$this->value);
    }

    public function equals(object $other): bool
    {
        if (!$other instanceof SassBoolean) {
            return false;
        }

        return $this->value === $other->value;
    }
}
