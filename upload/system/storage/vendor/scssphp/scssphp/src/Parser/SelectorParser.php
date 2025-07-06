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

namespace ScssPhp\ScssPhp\Parser;

use League\Uri\Contracts\UriInterface;
use ScssPhp\ScssPhp\Ast\Css\CssValue;
use ScssPhp\ScssPhp\Ast\Selector\AttributeOperator;
use ScssPhp\ScssPhp\Ast\Selector\AttributeSelector;
use ScssPhp\ScssPhp\Ast\Selector\ClassSelector;
use ScssPhp\ScssPhp\Ast\Selector\Combinator;
use ScssPhp\ScssPhp\Ast\Selector\ComplexSelector;
use ScssPhp\ScssPhp\Ast\Selector\ComplexSelectorComponent;
use ScssPhp\ScssPhp\Ast\Selector\CompoundSelector;
use ScssPhp\ScssPhp\Ast\Selector\IDSelector;
use ScssPhp\ScssPhp\Ast\Selector\ParentSelector;
use ScssPhp\ScssPhp\Ast\Selector\PlaceholderSelector;
use ScssPhp\ScssPhp\Ast\Selector\PseudoSelector;
use ScssPhp\ScssPhp\Ast\Selector\QualifiedName;
use ScssPhp\ScssPhp\Ast\Selector\SelectorList;
use ScssPhp\ScssPhp\Ast\Selector\SimpleSelector;
use ScssPhp\ScssPhp\Ast\Selector\TypeSelector;
use ScssPhp\ScssPhp\Ast\Selector\UniversalSelector;
use ScssPhp\ScssPhp\Exception\SassFormatException;
use ScssPhp\ScssPhp\Logger\LoggerInterface;
use ScssPhp\ScssPhp\Util;
use ScssPhp\ScssPhp\Util\Character;

/**
 * A parser for selectors.
 *
 * @internal
 */
final class SelectorParser extends Parser
{
    /**
     * Pseudo-class selectors that take unadorned selectors as arguments.
     */
    private const SELECTOR_PSEUDO_CLASSES = ['not', 'is', 'matches', 'where', 'current', 'any', 'has', 'host', 'host-context'];

    /**
     * Pseudo-element selectors that take unadorned selectors as arguments.
     */
    private const SELECTOR_PSEUDO_ELEMENTS = ['slotted'];

    private readonly bool $allowParent;

    /**
     * Whether to parse the selector as plain CSS.
     */
    private readonly bool $plainCss;

    /**
     * Creates a parser that parses CSS selectors.
     *
     * If $allowParent is `false`, this will throw a @see SassFormatException} if
     * the selector includes the parent selector `&`.
     *
     * If $plainCss is `true`, this will parse the selector as a plain CSS
     * selector rather than a Sass selector.
     */
    public function __construct(string $contents, ?LoggerInterface $logger = null, ?UriInterface $url = null, bool $allowParent = true, ?InterpolationMap $interpolationMap = null, bool $plainCss = false)
    {
        $this->allowParent = $allowParent;
        $this->plainCss = $plainCss;
        parent::__construct($contents, $logger, $url, $interpolationMap);
    }

    /**
     * @throws SassFormatException
     */
    public function parse(): SelectorList
    {
        return $this->wrapSpanFormatException(function () {
            $selector = $this->selectorList();

            if (!$this->scanner->isDone()) {
                $this->scanner->error('expected selector.');
            }

            return $selector;
        });
    }

    public function parseComplexSelector(): ComplexSelector
    {
        return $this->wrapSpanFormatException(function () {
            $complex = $this->complexSelector();

            if (!$this->scanner->isDone()) {
                $this->scanner->error('expected selector.');
            }

            return $complex;
        });
    }

    public function parseCompoundSelector(): CompoundSelector
    {
        return $this->wrapSpanFormatException(function () {
            $compound = $this->compoundSelector();

            if (!$this->scanner->isDone()) {
                $this->scanner->error('expected selector.');
            }

            return $compound;
        });
    }

    public function parseSimpleSelector(): SimpleSelector
    {
        return $this->wrapSpanFormatException(function () {
            $simple = $this->simpleSelector();

            if (!$this->scanner->isDone()) {
                $this->scanner->error('unexpected token.');
            }

            return $simple;
        });
    }

    /**
     * Consumes a selector list.
     */
    private function selectorList(): SelectorList
    {
        $start = $this->scanner->getPosition();
        $previousLine = $this->scanner->getLine();
        $components = [$this->complexSelector()];

        $this->whitespace();
        while ($this->scanner->scanChar(',')) {
            $this->whitespace();
            $next = $this->scanner->peekChar();

            if ($next === ',') {
                continue;
            }

            if ($this->scanner->isDone()) {
                break;
            }

            $lineBreak = $this->scanner->getLine() !== $previousLine;

            if ($lineBreak) {
                $previousLine = $this->scanner->getLine();
            }

            $components[] = $this->complexSelector($lineBreak);
        }

        return new SelectorList($components, $this->spanFrom($start));
    }

    /**
     * Consumes a complex selector.
     *
     * If $lineBreak is `true`, that indicates that there was a line break
     * before this selector.
     */
    private function complexSelector(bool $lineBreak = false): ComplexSelector
    {
        $start = $this->scanner->getPosition();

        $componentStart = $this->scanner->getPosition();
        $lastCompound = null;
        /** @var list<CssValue<Combinator>> $combinators */
        $combinators = [];

        $initialCombinators = null;
        $components = [];

        while (true) {
            $this->whitespace();

            $next = $this->scanner->peekChar();

            switch ($next) {
                case '+':
                    $combinatorStart = $this->scanner->getPosition();
                    $this->scanner->readChar();
                    $combinators[] = new CssValue(Combinator::NEXT_SIBLING, $this->spanFrom($combinatorStart));
                    break;

                case '>':
                    $combinatorStart = $this->scanner->getPosition();
                    $this->scanner->readChar();
                    $combinators[] = new CssValue(Combinator::CHILD, $this->spanFrom($combinatorStart));
                    break;

                case '~':
                    $combinatorStart = $this->scanner->getPosition();
                    $this->scanner->readChar();
                    $combinators[] = new CssValue(Combinator::FOLLOWING_SIBLING, $this->spanFrom($combinatorStart));
                    break;

                default:
                    if ($next === null || (!\in_array($next, ['[', '.', '#', '%', ':', '&', '*', '|'], true) && !$this->lookingAtIdentifier())) {
                        break 2;
                    }

                    if ($lastCompound !== null) {
                        $components[] = new ComplexSelectorComponent($lastCompound, $combinators, $this->spanFrom($componentStart));
                    } elseif (\count($combinators) !== 0) {
                        \assert($initialCombinators === null);
                        $initialCombinators = $combinators;
                        $componentStart = $this->scanner->getPosition();
                    }
                    $lastCompound = $this->compoundSelector();
                    $combinators = [];

                    if ($this->scanner->peekChar() === '&') {
                        $this->scanner->error('"&" may only used at the beginning of a compound selector.');
                    }
                    break;
            }
        }

        if (\count($combinators) > 0 && $this->plainCss) {
            $this->scanner->error('expected selector.');
        }
        if ($lastCompound !== null) {
            $components[] = new ComplexSelectorComponent($lastCompound, $combinators, $this->spanFrom($componentStart));
        } elseif (\count($combinators) !== 0) {
            $initialCombinators = $combinators;
        } else {
            $this->scanner->error('expected selector.');
        }

        return new ComplexSelector($initialCombinators ?? [], $components, $this->spanFrom($start), $lineBreak);
    }

    /**
     * Consumes a compound selector.
     */
    private function compoundSelector(): CompoundSelector
    {
        $start = $this->scanner->getPosition();
        $components = [$this->simpleSelector()];

        while ($this->isSimpleSelectorStart($this->scanner->peekChar())) {
            $components[] = $this->simpleSelector(false);
        }

        return new CompoundSelector($components, $this->spanFrom($start));
    }

    /**
     * Consumes a simple selector.
     *
     * If $allowParent is passed, it controls whether the parent selector `&` is
     * allowed. Otherwise, it defaults to {@see allowParent}.
     */
    private function simpleSelector(?bool $allowParent = null): SimpleSelector
    {
        $start = $this->scanner->getPosition();
        $allowParent ??= $this->allowParent;

        switch ($this->scanner->peekChar()) {
            case '[':
                return $this->attributeSelector();

            case '.':
                return $this->classSelector();

            case '#':
                return $this->idSelector();

            case '%':
                $selector = $this->placeholderSelector();
                if ($this->plainCss) {
                    $this->error("Placeholder selectors aren't allowed in plain CSS.", $this->scanner->spanFrom($start));
                }
                return $selector;

            case ':':
                return $this->pseudoSelector();

            case '&':
                $selector = $this->parentSelector();
                if (!$allowParent) {
                    $this->error("Parent selectors aren't allowed here.", $this->scanner->spanFrom($start));
                }
                return $selector;

            default:
                return $this->typeOrUniversalSelector();
        }
    }

    /**
     * Consumes an attribute selector.
     */
    private function attributeSelector(): AttributeSelector
    {
        $start = $this->scanner->getPosition();
        $this->scanner->expectChar('[');
        $this->whitespace();

        $name = $this->attributeName();
        $this->whitespace();

        if ($this->scanner->scanChar(']')) {
            return AttributeSelector::create($name, $this->spanFrom($start));
        }

        $operator = $this->attributeOperator();
        $this->whitespace();

        $next = $this->scanner->peekChar();
        $value = $next === "'" || $next === '"' ? $this->string() : $this->identifier();
        $this->whitespace();

        $next = $this->scanner->peekChar();
        $modifier = $next !== null && Character::isAlphabetic($next) ? $this->scanner->readChar() : null;

        $this->scanner->expectChar(']');

        return AttributeSelector::withOperator($name, $operator, $value, $this->spanFrom($start), $modifier);
    }

    /**
     * Consumes a qualified name as part of an attribute selector.
     */
    private function attributeName(): QualifiedName
    {
        if ($this->scanner->scanChar('*')) {
            $this->scanner->expectChar('|');

            return new QualifiedName($this->identifier(), '*');
        }

        if ($this->scanner->scanChar('|')) {
            return new QualifiedName($this->identifier(), '');
        }

        $nameOrNamespace = $this->identifier();

        if ($this->scanner->peekChar() !== '|' || $this->scanner->peekChar(1) === '=') {
            return new QualifiedName($nameOrNamespace);
        }

        $this->scanner->readChar();

        return new QualifiedName($this->identifier(), $nameOrNamespace);
    }

    /**
     * Consumes an attribute selector's operator.
     */
    private function attributeOperator(): AttributeOperator
    {
        $start = $this->scanner->getPosition();

        switch ($this->scanner->readChar()) {
            case '=':
                return AttributeOperator::EQUAL;

            case '~':
                $this->scanner->expectChar('=');
                return AttributeOperator::INCLUDE;

            case '|':
                $this->scanner->expectChar('=');
                return AttributeOperator::DASH;

            case '^':
                $this->scanner->expectChar('=');
                return AttributeOperator::PREFIX;

            case '$':
                $this->scanner->expectChar('=');
                return AttributeOperator::SUFFIX;

            case '*':
                $this->scanner->expectChar('=');
                return AttributeOperator::SUBSTRING;

            default:
                $this->scanner->error('Expected "]".', $start);
        }
    }

    /**
     * Consumes a class selector.
     */
    private function classSelector(): ClassSelector
    {
        $start = $this->scanner->getPosition();
        $this->scanner->expectChar('.');
        $name = $this->identifier();

        return new ClassSelector($name, $this->spanFrom($start));
    }

    /**
     * Consumes an ID selector.
     */
    private function idSelector(): IDSelector
    {
        $start = $this->scanner->getPosition();
        $this->scanner->expectChar('#');
        $name = $this->identifier();

        return new IDSelector($name, $this->spanFrom($start));
    }

    /**
     * Consumes a placeholder selector.
     */
    private function placeholderSelector(): PlaceholderSelector
    {
        $start = $this->scanner->getPosition();
        $this->scanner->expectChar('%');
        $name = $this->identifier();

        return new PlaceholderSelector($name, $this->spanFrom($start));
    }

    /**
     * Consumes a parent selector.
     */
    private function parentSelector(): ParentSelector
    {
        $start = $this->scanner->getPosition();
        $this->scanner->expectChar('&');
        $suffix = $this->lookingAtIdentifierBody() ? $this->identifierBody() : null;

        if ($this->plainCss && $suffix !== null) {
            $this->scanner->error("Parent selectors can't have suffixes in plain CSS.", $start, $this->scanner->getPosition() - $start);
        }

        return new ParentSelector($this->spanFrom($start), $suffix);
    }

    /**
     * Consumes a pseudo selector.
     */
    private function pseudoSelector(): PseudoSelector
    {
        $start = $this->scanner->getPosition();
        $this->scanner->expectChar(':');
        $element = $this->scanner->scanChar(':');
        $name = $this->identifier();

        if (!$this->scanner->scanChar('(')) {
            return new PseudoSelector($name, $this->spanFrom($start), $element);
        }
        $this->whitespace();

        $unvendored = Util::unvendor($name);
        $argument = null;
        $selector = null;

        if ($element) {
            if (\in_array($unvendored, self::SELECTOR_PSEUDO_ELEMENTS, true)) {
                $selector = $this->selectorList();
            } else {
                $argument = $this->declarationValue(true);
            }
        } elseif (\in_array($unvendored, self::SELECTOR_PSEUDO_CLASSES, true)) {
            $selector = $this->selectorList();
        } elseif ($unvendored === 'nth-child' || $unvendored === 'nth-last-child') {
            $argument = $this->aNPlusB();
            $this->whitespace();

            if (Character::isWhitespace($this->scanner->peekChar(-1)) && $this->scanner->peekChar() !== ')') {
                $this->expectIdentifier('of');
                $argument .= ' of';
                $this->whitespace();

                $selector = $this->selectorList();
            }
        } else {
            $argument = rtrim($this->declarationValue(true));
        }

        $this->scanner->expectChar(')');

        return new PseudoSelector($name, $this->spanFrom($start), $element, $argument, $selector);
    }

    /**
     * Consumes an [`An+B` production][An+B] and returns its text.
     *
     * [An+B]: https://drafts.csswg.org/css-syntax-3/#anb-microsyntax
     */
    private function aNPlusB(): string
    {
        $buffer = '';

        switch ($this->scanner->peekChar()) {
            case 'e':
            case 'E':
                $this->expectIdentifier('even');
                return 'even';

            case 'o':
            case 'O':
                $this->expectIdentifier('odd');
                return 'odd';

            case '+':
            case '-':
                $buffer .= $this->scanner->readChar();
                break;
        }

        $first = $this->scanner->peekChar();

        if ($first !== null && Character::isDigit($first)) {
            while (Character::isDigit($this->scanner->peekChar())) {
                $buffer .= $this->scanner->readChar();
            }
            $this->whitespace();

            if (!$this->scanIdentChar('n')) {
                return $buffer;
            }
        } else {
            $this->expectIdentChar('n');
        }
        $buffer .= 'n';
        $this->whitespace();

        $next = $this->scanner->peekChar();
        if ($next !== '+' && $next !== '-') {
            return $buffer;
        }
        $buffer .= $this->scanner->readChar();
        $this->whitespace();

        $last = $this->scanner->peekChar();
        if ($last === null || !Character::isDigit($last)) {
            $this->scanner->error('Expected a number.');
        }
        while (Character::isDigit($this->scanner->peekChar())) {
            $buffer .= $this->scanner->readChar();
        }

        return $buffer;
    }

    /**
     * Consumes a type selector or a universal selector.
     *
     * These are combined because either one could start with `*`.
     */
    private function typeOrUniversalSelector(): SimpleSelector
    {
        $start = $this->scanner->getPosition();
        $first = $this->scanner->peekChar();

        if ($first === '*') {
            $this->scanner->readChar();

            if (!$this->scanner->scanChar('|')) {
                return new UniversalSelector($this->spanFrom($start));
            }

            if ($this->scanner->scanChar('*')) {
                return new UniversalSelector($this->spanFrom($start), '*');
            }

            return new TypeSelector(new QualifiedName($this->identifier(), '*'), $this->spanFrom($start));
        }

        if ($first === '|') {
            $this->scanner->readChar();

            if ($this->scanner->scanChar('*')) {
                return new UniversalSelector($this->spanFrom($start), '');
            }

            return new TypeSelector(new QualifiedName($this->identifier(), ''), $this->spanFrom($start));
        }

        $nameOrNamespace = $this->identifier();

        if (!$this->scanner->scanChar('|')) {
            return new TypeSelector(new QualifiedName($nameOrNamespace), $this->spanFrom($start));
        }

        if ($this->scanner->scanChar('*')) {
            return new UniversalSelector($this->spanFrom($start), $nameOrNamespace);
        }

        return new TypeSelector(new QualifiedName($this->identifier(), $nameOrNamespace), $this->spanFrom($start));
    }

    /**
     *  Returns whether $character can start a simple selector in the middle of a compound selector.
     */
    private function isSimpleSelectorStart(?string $character): bool
    {
        return match ($character) {
            '*', '[', '.', '#', '%', ':' => true,
            '&' => $this->plainCss,
            default => false,
        };
    }
}
