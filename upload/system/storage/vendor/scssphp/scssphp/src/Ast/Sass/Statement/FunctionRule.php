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

use ScssPhp\ScssPhp\Ast\Sass\SassDeclaration;
use ScssPhp\ScssPhp\Util\SpanUtil;
use ScssPhp\ScssPhp\Visitor\StatementVisitor;
use SourceSpan\FileSpan;

/**
 * A function declaration.
 *
 * This declares a function that's invoked using normal CSS function syntax.
 *
 * @internal
 */
final class FunctionRule extends CallableDeclaration implements SassDeclaration
{
    public function getNameSpan(): FileSpan
    {
        return SpanUtil::initialIdentifier(SpanUtil::withoutInitialAtRule($this->getSpan()));
    }

    public function accept(StatementVisitor $visitor)
    {
        return $visitor->visitFunctionRule($this);
    }

    public function __toString(): string
    {
        return '@function ' . $this->getName() . '(' . $this->getArguments() . ') {' . implode(' ', $this->getChildren()) . '}';
    }
}
