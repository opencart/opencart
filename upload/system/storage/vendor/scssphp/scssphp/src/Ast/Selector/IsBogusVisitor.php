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
 * The visitor used to implement {@see Selector::isBogus}.
 *
 * @internal
 */
final class IsBogusVisitor extends AnySelectorVisitor
{
    /**
     * Whether to consider selectors with leading combinators as bogus.
     */
    private readonly bool $includeLeadingCombinator;

    public function __construct(bool $includeLeadingCombinator)
    {
        $this->includeLeadingCombinator = $includeLeadingCombinator;
    }

    public function visitComplexSelector(ComplexSelector $complex): bool
    {
        if (\count($complex->getComponents()) === 0) {
            return \count($complex->getLeadingCombinators()) > 0;
        }

        if (\count($complex->getLeadingCombinators()) > ($this->includeLeadingCombinator ? 0 : 1) || count($complex->getLastComponent()->getCombinators()) !== 0) {
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
        $selector = $pseudo->getSelector();

        if ($selector === null) {
            return false;
        }

        // The CSS spec specifically allows leading combinators in `:has()`.
        return $pseudo->getName() === 'has' ? $selector->isBogusOtherThanLeadingCombinator() : $selector->isBogus();
    }
}
