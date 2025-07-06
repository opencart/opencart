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

namespace ScssPhp\ScssPhp\Ast\Sass\Statement;

use ScssPhp\ScssPhp\Ast\Sass\ArgumentDeclaration;
use ScssPhp\ScssPhp\Ast\Sass\Statement;
use ScssPhp\ScssPhp\Visitor\StatementVisitor;
use SourceSpan\FileSpan;

/**
 * An anonymous block of code that's invoked for a {@see ContentRule}.
 *
 * @internal
 */
final class ContentBlock extends CallableDeclaration
{
    /**
     * @param Statement[] $children
     */
    public function __construct(ArgumentDeclaration $arguments, array $children, FileSpan $span)
    {
        parent::__construct('@content', $arguments, $span, $children);
    }

    public function accept(StatementVisitor $visitor)
    {
        return $visitor->visitContentBlock($this);
    }

    public function __toString(): string
    {
        $buffer = $this->getArguments()->isEmpty() ? '' : ' using (' . $this->getArguments() . ')';

        return $buffer . '{' . implode(' ', $this->getChildren()) . '}';
    }
}
