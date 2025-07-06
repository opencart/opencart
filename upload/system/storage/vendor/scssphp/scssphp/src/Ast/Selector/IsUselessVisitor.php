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

use ScssPhp\ScssPhp\Visitor\AnySelectorVisitor;

/**
 * The visitor used to implement {@see Selector::isUseless}.
 *
 * @internal
 */
final class IsUselessVisitor extends AnySelectorVisitor
{
    public function visitComplexSelector(ComplexSelector $complex): bool
    {
        if (\count($complex->getLeadingCombinators()) > 1) {
            return true;
        }

        foreach ($complex->getComponents() as $component) {
            if (\count($component->getCombinators()) > 1 || $component->getSelector()->accept($this)) {
                return true;
            }
        }

        return false;
    }

    public function visitPseudoSelector(PseudoSelector $pseudo): bool
    {
        return $pseudo->isBogus();
    }
}
