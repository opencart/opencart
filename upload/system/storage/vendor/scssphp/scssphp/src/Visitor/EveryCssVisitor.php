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

namespace ScssPhp\ScssPhp\Visitor;

use ScssPhp\ScssPhp\Ast\Css\CssAtRule;
use ScssPhp\ScssPhp\Ast\Css\CssComment;
use ScssPhp\ScssPhp\Ast\Css\CssDeclaration;
use ScssPhp\ScssPhp\Ast\Css\CssImport;
use ScssPhp\ScssPhp\Ast\Css\CssKeyframeBlock;
use ScssPhp\ScssPhp\Ast\Css\CssMediaRule;
use ScssPhp\ScssPhp\Ast\Css\CssNode;
use ScssPhp\ScssPhp\Ast\Css\CssStyleRule;
use ScssPhp\ScssPhp\Ast\Css\CssStylesheet;
use ScssPhp\ScssPhp\Ast\Css\CssSupportsRule;
use ScssPhp\ScssPhp\Util\IterableUtil;

/**
 * A visitor that visits each statement in a CSS AST and returns `true` if all
 * of the individual methods return `true`.
 *
 * Each method returns `false` by default.
 *
 * @template-implements CssVisitor<bool>
 * @internal
 */
abstract class EveryCssVisitor implements CssVisitor
{
    public function visitCssAtRule(CssAtRule $node): bool
    {
        return IterableUtil::every($node->getChildren(), fn (CssNode $child) => $child->accept($this));
    }

    public function visitCssComment(CssComment $node): bool
    {
        return false;
    }

    public function visitCssDeclaration(CssDeclaration $node): bool
    {
        return false;
    }

    public function visitCssImport(CssImport $node): bool
    {
        return false;
    }

    public function visitCssKeyframeBlock(CssKeyframeBlock $node): bool
    {
        return IterableUtil::every($node->getChildren(), fn (CssNode $child) => $child->accept($this));
    }

    public function visitCssMediaRule(CssMediaRule $node): bool
    {
        return IterableUtil::every($node->getChildren(), fn (CssNode $child) => $child->accept($this));
    }

    public function visitCssStyleRule(CssStyleRule $node): bool
    {
        return IterableUtil::every($node->getChildren(), fn (CssNode $child) => $child->accept($this));
    }

    public function visitCssStylesheet(CssStylesheet $node): bool
    {
        return IterableUtil::every($node->getChildren(), fn (CssNode $child) => $child->accept($this));
    }

    public function visitCssSupportsRule(CssSupportsRule $node): bool
    {
        return IterableUtil::every($node->getChildren(), fn (CssNode $child) => $child->accept($this));
    }
}
