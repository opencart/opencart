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

namespace ScssPhp\ScssPhp\Ast\Selector;

use ScssPhp\ScssPhp\Visitor\SelectorVisitor;
use SourceSpan\FileSpan;

/**
 * An ID selector.
 *
 * This selects elements whose `id` attribute exactly matches the given name.
 *
 * @internal
 */
final class IDSelector extends SimpleSelector
{
    /**
     * The ID name this selects for.
     */
    private readonly string $name;

    public function __construct(string $name, FileSpan $span)
    {
        $this->name = $name;
        parent::__construct($span);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSpecificity(): int
    {
        return parent::getSpecificity() ** 2;
    }

    public function accept(SelectorVisitor $visitor)
    {
        return $visitor->visitIDSelector($this);
    }

    public function addSuffix(string $suffix): SimpleSelector
    {
        return new IDSelector($this->name . $suffix, $this->getSpan());
    }

    public function unify(array $compound): ?array
    {
        // A given compound selector may only contain one ID.
        foreach ($compound as $simple) {
            if ($simple instanceof IDSelector && !$simple->equals($this)) {
                return null;
            }
        }

        return parent::unify($compound);
    }

    public function equals(object $other): bool
    {
        return $other instanceof IDSelector && $other->name === $this->name;
    }
}
