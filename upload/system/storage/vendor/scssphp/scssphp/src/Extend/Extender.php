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

namespace ScssPhp\ScssPhp\Extend;

use ScssPhp\ScssPhp\Ast\Css\CssMediaQuery;
use ScssPhp\ScssPhp\Ast\Selector\ComplexSelector;
use ScssPhp\ScssPhp\Exception\SimpleSassException;
use ScssPhp\ScssPhp\Util\EquatableUtil;

/**
 * A selector that's extending another selector, such as `A` in `A {@extend B}`.
 * @internal
 */
final class Extender
{
    public readonly ComplexSelector $selector;

    /**
     * The minimum specificity required for any selector generated from this
     * extender.
     */
    public readonly int $specificity;

    /**
     * Whether this extender represents a selector that was originally in the
     * document, rather than one defined with `@extend`.
     */
    public readonly bool $isOriginal;

    /**
     * The extension that created this Extender.
     *
     * Not all {@see Extender}s are created by extensions. Some simply represent the
     * original selectors that exist in the document.
     */
    private readonly ?Extension $extension;

    private function __construct(ComplexSelector $selector, ?int $specificity = null, bool $original = false, ?Extension $extension = null)
    {
        $this->selector = $selector;
        $this->specificity = $specificity ?? $selector->getSpecificity();
        $this->isOriginal = $original;
        $this->extension = $extension;
    }

    public static function create(ComplexSelector $selector, ?int $specificity = null, bool $original = false): self
    {
        return new Extender($selector, $specificity, $original);
    }

    public static function forExtension(ComplexSelector $selector, Extension $extension): self
    {
        return new Extender($selector, extension: $extension);
    }

    /**
     * @param list<CssMediaQuery>|null $mediaContext
     */
    public function assertCompatibleMediaContext(?array $mediaContext): void
    {
        if ($this->extension === null) {
            return;
        }

        $expectedMediaContext = $this->extension->mediaContext;
        if ($expectedMediaContext === null) {
            return;
        }

        if ($mediaContext !== null && EquatableUtil::listEquals($expectedMediaContext, $mediaContext)) {
            return;
        }

        throw new SimpleSassException('You may not @extend selectors across media queries.', $this->extension->span);
    }
}
