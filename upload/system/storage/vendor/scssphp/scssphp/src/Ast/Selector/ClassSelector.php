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
 * A class selector.
 *
 * This selects elements whose `class` attribute contains an identifier with
 * the given name.
 *
 * @internal
 */
final class ClassSelector extends SimpleSelector
{
    /**
     * The class name this selects for.
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

    public function accept(SelectorVisitor $visitor)
    {
        return $visitor->visitClassSelector($this);
    }

    public function equals(object $other): bool
    {
        return $other instanceof ClassSelector && $other->name === $this->name;
    }

    public function addSuffix(string $suffix): SimpleSelector
    {
        return new ClassSelector($this->name . $suffix, $this->getSpan());
    }
}
