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

use ScssPhp\ScssPhp\Util;
use ScssPhp\ScssPhp\Util\EquatableUtil;
use ScssPhp\ScssPhp\Visitor\SelectorVisitor;
use SourceSpan\FileSpan;

/**
 * A pseudo-class or pseudo-element selector.
 *
 * The semantics of a specific pseudo selector depends on its name. Some
 * selectors take arguments, including other selectors. Sass manually encodes
 * logic for each pseudo selector that takes a selector as an argument, to
 * ensure that extension and other selector operations work properly.
 *
 * @internal
 */
final class PseudoSelector extends SimpleSelector
{
    /**
     * The name of this selector.
     */
    private readonly string $name;

    /**
     * Like {@see name}, but without any vendor prefixes.
     */
    private readonly string $normalizedName;

    private readonly bool $isClass;

    private readonly bool $isSyntacticClass;

    /**
     * The non-selector argument passed to this selector.
     *
     * This is `null` if there's no argument. If {@see argument} and {@see selector} are
     * both non-`null`, the selector follows the argument.
     */
    private readonly ?string $argument;

    /**
     * The selector argument passed to this selector.
     *
     * This is `null` if there's no selector. If {@see argument} and {@see selector} are
     * both non-`null`, the selector follows the argument.
     */
    private readonly ?SelectorList $selector;

    private ?int $specificity = null;

    public function __construct(string $name, FileSpan $span, bool $element = false, ?string $argument = null, ?SelectorList $selector = null)
    {
        $this->name = $name;
        $this->isClass = !$element && !self::isFakePseudoElement($name);
        $this->isSyntacticClass = !$element;
        $this->argument = $argument;
        $this->selector = $selector;
        $this->normalizedName = Util::unvendor($name);
        parent::__construct($span);
    }

    /**
     * Returns whether $name is the name of a pseudo-element that can be written
     * with pseudo-class syntax (`:before`, `:after`, `:first-line`, or
     * `:first-letter`)
     */
    private static function isFakePseudoElement(string $name): bool
    {
        if ($name === '') {
            return false;
        }

        switch ($name[0]) {
            case 'a':
            case 'A':
                return strtolower($name) === 'after';

            case 'b':
            case 'B':
                return strtolower($name) === 'before';

            case 'f':
            case 'F':
                $lowerCasedName = strtolower($name);

                return $lowerCasedName === 'first-line' || $lowerCasedName === 'first-letter';

            default:
                return false;
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNormalizedName(): string
    {
        return $this->normalizedName;
    }

    /**
     * Whether this is a pseudo-class selector.
     *
     * This is `true` if and only if {@see isElement} is `false`.
     */
    public function isClass(): bool
    {
        return $this->isClass;
    }

    /**
     * Whether this is a pseudo-element selector.
     *
     * This is `true` if and only if {@see isClass} is `false`.
     */
    public function isElement(): bool
    {
        return !$this->isClass;
    }

    /**
     * Whether this is syntactically a pseudo-class selector.
     *
     * This is the same as {@see isClass} unless this selector is a pseudo-element
     * that was written syntactically as a pseudo-class (`:before`, `:after`,
     * `:first-line`, or `:first-letter`).
     *
     * This is `true` if and only if {@see isSyntacticElement} is `false`.
     */
    public function isSyntacticClass(): bool
    {
        return $this->isSyntacticClass;
    }

    /**
     * Whether this is syntactically a pseudo-element selector.
     *
     * This is `true` if and only if {@see isSyntacticClass} is `false`.
     */
    public function isSyntacticElement(): bool
    {
        return !$this->isSyntacticClass;
    }

    /**
     * Whether this is a valid `:host` selector.
     *
     * @internal
     */
    public function isHost(): bool
    {
        return $this->isClass && $this->name === 'host';
    }

    /**
     * Whether this is a valid `:host-context` selector.
     *
     * @internal
     */
    public function isHostContext(): bool
    {
        return $this->isClass && $this->name === 'host-context' && $this->selector !== null;
    }

    public function getArgument(): ?string
    {
        return $this->argument;
    }

    public function getSelector(): ?SelectorList
    {
        return $this->selector;
    }

    public function getSpecificity(): int
    {
        if ($this->specificity === null) {
            $this->specificity = $this->computeSpecificity();
        }

        return $this->specificity;
    }

    /**
     * @internal
     */
    public function hasComplicatedSuperselectorSemantics(): bool
    {
        return $this->isElement() || $this->selector !== null;
    }

    private function computeSpecificity(): int
    {
        if ($this->isElement()) {
            return 1;
        }

        $selector = $this->selector;

        if ($selector === null) {
            return parent::getSpecificity();
        }

        // https://www.w3.org/TR/selectors-4/#specificity-rules
        switch ($this->normalizedName) {
            case 'where':
                return 0;
            case 'is':
            case 'not':
            case 'has':
            case 'matches':
                $maxSpecificity = 0;

                foreach ($selector->getComponents() as $complex) {
                    $maxSpecificity = max($maxSpecificity, $complex->getSpecificity());
                }

                return $maxSpecificity;
            case 'nth-child':
            case 'nth-last-child':
                $maxSpecificity = 0;

                foreach ($selector->getComponents() as $complex) {
                    $maxSpecificity = max($maxSpecificity, $complex->getSpecificity());
                }

                return parent::getSpecificity() + $maxSpecificity;
            default:
                return parent::getSpecificity();
        }
    }

    public function withSelector(SelectorList $selector): PseudoSelector
    {
        return new PseudoSelector($this->name, $this->getSpan(), $this->isElement(), $this->argument, $selector);
    }

    public function addSuffix(string $suffix): SimpleSelector
    {
        if ($this->argument !== null || $this->selector !== null) {
            parent::addSuffix($suffix);
        }

        return new PseudoSelector($this->name . $suffix, $this->getSpan(), $this->isElement());
    }

    public function unify(array $compound): ?array
    {
        if ($this->name === 'host' || $this->name === 'host-context') {
            foreach ($compound as $simple) {
                if (!$simple instanceof PseudoSelector || (!$simple->isHost() && $simple->selector === null)) {
                    return null;
                }
            }
        } elseif (\count($compound) === 1) {
            $other = $compound[0];

            if ($other instanceof UniversalSelector || $other instanceof PseudoSelector && ($other->isHost() || $other->isHostContext())) {
                return $other->unify([$this]);
            }
        }

        if (EquatableUtil::iterableContains($compound, $this)) {
            return $compound;
        }

        $result = [];
        $addedThis = false;

        foreach ($compound as $simple) {
            if ($simple instanceof PseudoSelector && $simple->isElement()) {
                // A given compound selector may only contain one pseudo element. If
                // $compound has a different one than $this, unification fails.
                if ($this->isElement()) {
                    return null;
                }

                // Otherwise, this is a pseudo selector and should come before pseudo
                // elements.
                $result[] = $this;
                $addedThis = true;
            }

            $result[] = $simple;
        }

        if (!$addedThis) {
            $result[] = $this;
        }

        return $result;
    }

    public function isSuperselector(SimpleSelector $other): bool
    {
        if (parent::isSuperselector($other)) {
            return true;
        }

        $selector = $this->selector;

        if ($selector === null) {
            return $this === $other || $this->equals($other);
        }

        if ($other instanceof PseudoSelector && $this->isElement() && $other->isElement() && $this->normalizedName === 'slotted' && $other->name === $this->name) {
            if ($other->getSelector() !== null) {
                return $selector->isSuperselector($other->getSelector());
            }

            return false;
        }

        // Fall back to the logic defined in ExtendUtil, which knows how to
        // compare selector pseudoclasses against raw selectors.
        return (new CompoundSelector([$this], $this->getSpan()))->isSuperselector(new CompoundSelector([$other], $this->getSpan()));
    }

    public function accept(SelectorVisitor $visitor)
    {
        return $visitor->visitPseudoSelector($this);
    }

    public function equals(object $other): bool
    {
        return $other instanceof PseudoSelector &&
            $other->name === $this->name &&
            $other->isClass === $this->isClass &&
            $other->argument === $this->argument &&
            ($this->selector === $other->selector || ($this->selector !== null && $other->selector !== null && $this->selector->equals($other->selector)));
    }
}
