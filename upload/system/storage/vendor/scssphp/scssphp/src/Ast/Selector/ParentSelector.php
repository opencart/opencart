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
 * A selector that matches the parent in the Sass stylesheet.
 *
 * This is not a plain CSS selector—it should be removed before emitting a CSS
 * document.
 *
 * @internal
 */
final class ParentSelector extends SimpleSelector
{
    /**
     * The suffix that will be added to the parent selector after it's been
     * resolved.
     *
     * This is assumed to be a valid identifier suffix. It may be `null`,
     * indicating that the parent selector will not be modified.
     */
    private readonly ?string $suffix;

    public function __construct(FileSpan $span, ?string $suffix = null)
    {
        $this->suffix = $suffix;
        parent::__construct($span);
    }

    public function getSuffix(): ?string
    {
        return $this->suffix;
    }

    public function equals(object $other): bool
    {
        return $other === $this;
    }

    public function accept(SelectorVisitor $visitor)
    {
        return $visitor->visitParentSelector($this);
    }

    public function unify(array $compound): ?array
    {
        throw new \LogicException("& doesn't support unification.");
    }
}
