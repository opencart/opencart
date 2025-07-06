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

use ScssPhp\ScssPhp\Ast\Sass\Statement\AtRootRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\AtRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\ContentBlock;
use ScssPhp\ScssPhp\Ast\Sass\Statement\ContentRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\DebugRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\Declaration;
use ScssPhp\ScssPhp\Ast\Sass\Statement\EachRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\ErrorRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\ExtendRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\ForRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\FunctionRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\IfRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\ImportRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\IncludeRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\LoudComment;
use ScssPhp\ScssPhp\Ast\Sass\Statement\MediaRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\MixinRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\ReturnRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\SilentComment;
use ScssPhp\ScssPhp\Ast\Sass\Statement\StyleRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\Stylesheet;
use ScssPhp\ScssPhp\Ast\Sass\Statement\SupportsRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\VariableDeclaration;
use ScssPhp\ScssPhp\Ast\Sass\Statement\WarnRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\WhileRule;

/**
 * An interface for visitors that traverse SassScript statements.
 *
 * @internal
 *
 * @template T
 */
interface StatementVisitor
{
    /**
     * @return T
     */
    public function visitAtRootRule(AtRootRule $node);

    /**
     * @return T
     */
    public function visitAtRule(AtRule $node);

    /**
     * @return T
     */
    public function visitContentBlock(ContentBlock $node);

    /**
     * @return T
     */
    public function visitContentRule(ContentRule $node);

    /**
     * @return T
     */
    public function visitDebugRule(DebugRule $node);

    /**
     * @return T
     */
    public function visitDeclaration(Declaration $node);

    /**
     * @return T
     */
    public function visitEachRule(EachRule $node);

    /**
     * @return T
     */
    public function visitErrorRule(ErrorRule $node);

    /**
     * @return T
     */
    public function visitExtendRule(ExtendRule $node);

    /**
     * @return T
     */
    public function visitForRule(ForRule $node);

    /**
     * @return T
     */
    public function visitFunctionRule(FunctionRule $node);

    /**
     * @return T
     */
    public function visitIfRule(IfRule $node);

    /**
     * @return T
     */
    public function visitImportRule(ImportRule $node);

    /**
     * @return T
     */
    public function visitIncludeRule(IncludeRule $node);

    /**
     * @return T
     */
    public function visitLoudComment(LoudComment $node);

    /**
     * @return T
     */
    public function visitMediaRule(MediaRule $node);

    /**
     * @return T
     */
    public function visitMixinRule(MixinRule $node);

    /**
     * @return T
     */
    public function visitReturnRule(ReturnRule $node);

    /**
     * @return T
     */
    public function visitSilentComment(SilentComment $node);

    /**
     * @return T
     */
    public function visitStyleRule(StyleRule $node);

    /**
     * @return T
     */
    public function visitStylesheet(Stylesheet $node);

    /**
     * @return T
     */
    public function visitSupportsRule(SupportsRule $node);

    /**
     * @return T
     */
    public function visitVariableDeclaration(VariableDeclaration $node);

    /**
     * @return T
     */
    public function visitWarnRule(WarnRule $node);

    /**
     * @return T
     */
    public function visitWhileRule(WhileRule $node);
}
