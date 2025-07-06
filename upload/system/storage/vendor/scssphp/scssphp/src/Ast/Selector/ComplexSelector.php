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

use League\Uri\Contracts\UriInterface;
use ScssPhp\ScssPhp\Ast\Css\CssValue;
use ScssPhp\ScssPhp\Exception\SassFormatException;
use ScssPhp\ScssPhp\Extend\ExtendUtil;
use ScssPhp\ScssPhp\Logger\LoggerInterface;
use ScssPhp\ScssPhp\Parser\SelectorParser;
use ScssPhp\ScssPhp\Util\EquatableUtil;
use ScssPhp\ScssPhp\Util\ListUtil;
use ScssPhp\ScssPhp\Visitor\SelectorVisitor;
use SourceSpan\FileSpan;

/**
 * A complex selector.
 *
 * A complex selector is composed of {@see CompoundSelector}s separated by
 * {@see Combinator}s. It selects elements based on their parent selectors.
 *
 * @internal
 */
final class ComplexSelector extends Selector
{
    /**
     * This selector's leading combinators.
     *
     * If this is empty, that indicates that it has no leading combinator. If
     * it's more than one element, that means it's invalid CSS; however, we still
     * support this for backwards-compatibility purposes.
     *
     * @var list<CssValue<Combinator>>
     */
    private readonly array $leadingCombinators;

    /**
     * The components of this selector.
     *
     * This is only empty if {@see $leadingCombinators} is not empty.
     *
     * Descendant combinators aren't explicitly represented here. If two
     * {@see CompoundSelector}s are adjacent to one another, there's an implicit
     * descendant combinator between them.
     *
     * It's possible for multiple {@see Combinator}s to be adjacent to one another.
     * This isn't valid CSS, but Sass supports it for CSS hack purposes.
     *
     * @var list<ComplexSelectorComponent>
     */
    private readonly array $components;

    /**
     * Whether a line break should be emitted *before* this selector.
     */
    private readonly bool $lineBreak;

    private ?int $specificity = null;

    /**
     * @param list<CssValue<Combinator>>     $leadingCombinators
     * @param list<ComplexSelectorComponent> $components
     */
    public function __construct(array $leadingCombinators, array $components, FileSpan $span, bool $lineBreak = false)
    {
        if ($leadingCombinators === [] && $components === []) {
            throw new \InvalidArgumentException('leadingCombinators and components may not both be empty.');
        }

        $this->leadingCombinators = $leadingCombinators;
        $this->components = $components;
        $this->lineBreak = $lineBreak;
        parent::__construct($span);
    }

    /**
     * Parses a complex selector from $contents.
     *
     * If passed, $url is the name of the file from which $contents comes.
     * $allowParent controls whether a {@see ParentSelector} is allowed in this
     * selector.
     *
     * @throws SassFormatException if parsing fails.
     */
    public static function parse(string $contents, ?LoggerInterface $logger = null, ?UriInterface $url = null, bool $allowParent = true): ComplexSelector
    {
        return (new SelectorParser($contents, $logger, $url, $allowParent))->parseComplexSelector();
    }

    /**
     * @return list<CssValue<Combinator>>
     */
    public function getLeadingCombinators(): array
    {
        return $this->leadingCombinators;
    }

    /**
     * @return list<ComplexSelectorComponent>
     */
    public function getComponents(): array
    {
        return $this->components;
    }

    /**
     * If this compound selector is composed of a single compound selector with
     * no combinators, returns it.
     *
     * Otherwise, returns null.
     *
     * @return CompoundSelector|null
     */
    public function getSingleCompound(): ?CompoundSelector
    {
        if (\count($this->leadingCombinators) === 0 && \count($this->components) === 1 && \count($this->components[0]->getCombinators()) === 0) {
            return $this->components[0]->getSelector();
        }

        return null;
    }

    public function getLastComponent(): ComplexSelectorComponent
    {
        if (\count($this->components) === 0) {
            throw new \OutOfBoundsException('Cannot get the last component of an empty list.');
        }

        return $this->components[\count($this->components) - 1];
    }

    public function getLineBreak(): bool
    {
        return $this->lineBreak;
    }

    /**
     * This selector's specificity.
     *
     * Specificity is represented in base 1000. The spec says this should be
     * "sufficiently high"; it's extremely unlikely that any single selector
     * sequence will contain 1000 simple selectors.
     */
    public function getSpecificity(): int
    {
        if ($this->specificity === null) {
            $specificity = 0;

            foreach ($this->components as $component) {
                $specificity += $component->getSelector()->getSpecificity();
            }

            $this->specificity = $specificity;
        }

        return $this->specificity;
    }

    public function accept(SelectorVisitor $visitor)
    {
        return $visitor->visitComplexSelector($this);
    }

    /**
     * Whether this is a superselector of $other.
     *
     * That is, whether this matches every element that $other matches, as well
     * as possibly additional elements.
     */
    public function isSuperselector(ComplexSelector $other): bool
    {
        return \count($this->leadingCombinators) === 0 && \count($other->leadingCombinators) === 0 && ExtendUtil::complexIsSuperselector($this->components, $other->components);
    }

    public function equals(object $other): bool
    {
        return $other instanceof ComplexSelector && EquatableUtil::listEquals($this->leadingCombinators, $other->leadingCombinators) && EquatableUtil::listEquals($this->components, $other->components);
    }

    /**
     * Returns a copy of `$this` with $combinators added to the end of the final
     * component in {@see components}.
     *
     * If $forceLineBreak is `true`, this will mark the new complex selector as
     * having a line break.
     *
     * @param list<CssValue<Combinator>> $combinators
     */
    public function withAdditionalCombinators(array $combinators, bool $forceLineBreak = false): ComplexSelector
    {
        if ($combinators === []) {
            return $this;
        }

        if ($this->components === []) {
            return new ComplexSelector(array_merge($this->leadingCombinators, $combinators), [], $this->getSpan(), $this->lineBreak || $forceLineBreak);
        }

        return new ComplexSelector(
            $this->leadingCombinators,
            array_merge(
                ListUtil::exceptLast($this->components),
                [ListUtil::last($this->components)->withAdditionalCombinators($combinators)]
            ),
            $this->getSpan(),
            $this->lineBreak || $forceLineBreak
        );
    }

    /**
     * Returns a copy of `$this` with an additional $component added to the end.
     *
     * If $forceLineBreak is `true`, this will mark the new complex selector as
     * having a line break.
     */
    public function withAdditionalComponent(ComplexSelectorComponent $component, FileSpan $span, bool $forceLineBreak = false): ComplexSelector
    {
        return new ComplexSelector($this->leadingCombinators, array_merge($this->components, [$component]), $span, $this->lineBreak || $forceLineBreak);
    }

    /**
     * Returns a copy of `this` with $child's combinators added to the end.
     *
     * If $child has {@see leadingCombinators}, they're appended to `this`'s last
     * combinator. This does _not_ resolve parent selectors.
     *
     * If $forceLineBreak is `true`, this will mark the new complex selector as
     * having a line break.
     */
    public function concatenate(ComplexSelector $child, FileSpan $span, bool $forceLineBreak = false): ComplexSelector
    {
        if (\count($child->leadingCombinators) === 0) {
            return new ComplexSelector(
                $this->leadingCombinators,
                array_merge($this->components, $child->components),
                $span,
                $this->lineBreak || $child->lineBreak || $forceLineBreak
            );
        }

        if (\count($this->components) === 0) {
            return new ComplexSelector(
                array_merge($this->leadingCombinators, $child->leadingCombinators),
                $child->components,
                $span,
                $this->lineBreak || $child->lineBreak || $forceLineBreak
            );
        }

        return new ComplexSelector(
            $this->leadingCombinators,
            array_merge(
                ListUtil::exceptLast($this->components),
                [ListUtil::last($this->components)->withAdditionalCombinators($child->leadingCombinators)],
                $child->components
            ),
            $span,
            $this->lineBreak || $child->lineBreak || $forceLineBreak
        );
    }
}
