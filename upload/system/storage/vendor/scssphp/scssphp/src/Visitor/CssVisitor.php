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
use ScssPhp\ScssPhp\Ast\Css\CssStyleRule;
use ScssPhp\ScssPhp\Ast\Css\CssStylesheet;
use ScssPhp\ScssPhp\Ast\Css\CssSupportsRule;

/**
 * An interface for visitors that traverse CSS statements.
 *
 * @internal
 *
 * @template T
 * @template-extends ModifiableCssVisitor<T>
 */
interface CssVisitor extends ModifiableCssVisitor
{
    /**
     * @return T
     */
    public function visitCssAtRule(CssAtRule $node);

    /**
     * @return T
     */
    public function visitCssComment(CssComment $node);

    /**
     * @return T
     */
    public function visitCssDeclaration(CssDeclaration $node);

    /**
     * @return T
     */
    public function visitCssImport(CssImport $node);

    /**
     * @return T
     */
    public function visitCssKeyframeBlock(CssKeyframeBlock $node);

    /**
     * @return T
     */
    public function visitCssMediaRule(CssMediaRule $node);

    /**
     * @return T
     */
    public function visitCssStyleRule(CssStyleRule $node);

    /**
     * @return T
     */
    public function visitCssStylesheet(CssStylesheet $node);

    /**
     * @return T
     */
    public function visitCssSupportsRule(CssSupportsRule $node);
}
