<?php

/**
 * SCSSPHP
 *
 * @copyright 2018-2020 Anthon Pang
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 * @link http://scssphp.github.io/scssphp
 */

namespace ScssPhp\ScssPhp\Serializer;

use ScssPhp\ScssPhp\Ast\AstNode;
use ScssPhp\ScssPhp\Ast\Css\CssAtRule;
use ScssPhp\ScssPhp\Ast\Css\CssComment;
use ScssPhp\ScssPhp\Ast\Css\CssDeclaration;
use ScssPhp\ScssPhp\Ast\Css\CssImport;
use ScssPhp\ScssPhp\Ast\Css\CssKeyframeBlock;
use ScssPhp\ScssPhp\Ast\Css\CssMediaQuery;
use ScssPhp\ScssPhp\Ast\Css\CssMediaRule;
use ScssPhp\ScssPhp\Ast\Css\CssNode;
use ScssPhp\ScssPhp\Ast\Css\CssParentNode;
use ScssPhp\ScssPhp\Ast\Css\CssStyleRule;
use ScssPhp\ScssPhp\Ast\Css\CssStylesheet;
use ScssPhp\ScssPhp\Ast\Css\CssSupportsRule;
use ScssPhp\ScssPhp\Ast\Css\CssValue;
use ScssPhp\ScssPhp\Ast\Selector\AttributeSelector;
use ScssPhp\ScssPhp\Ast\Selector\ClassSelector;
use ScssPhp\ScssPhp\Ast\Selector\Combinator;
use ScssPhp\ScssPhp\Ast\Selector\ComplexSelector;
use ScssPhp\ScssPhp\Ast\Selector\CompoundSelector;
use ScssPhp\ScssPhp\Ast\Selector\IDSelector;
use ScssPhp\ScssPhp\Ast\Selector\ParentSelector;
use ScssPhp\ScssPhp\Ast\Selector\PlaceholderSelector;
use ScssPhp\ScssPhp\Ast\Selector\PseudoSelector;
use ScssPhp\ScssPhp\Ast\Selector\SelectorList;
use ScssPhp\ScssPhp\Ast\Selector\TypeSelector;
use ScssPhp\ScssPhp\Ast\Selector\UniversalSelector;
use ScssPhp\ScssPhp\Colors;
use ScssPhp\ScssPhp\Deprecation;
use ScssPhp\ScssPhp\Exception\SassScriptException;
use ScssPhp\ScssPhp\Logger\LoggerInterface;
use ScssPhp\ScssPhp\Logger\QuietLogger;
use ScssPhp\ScssPhp\OutputStyle;
use ScssPhp\ScssPhp\Parser\LineScanner;
use ScssPhp\ScssPhp\Parser\Parser;
use ScssPhp\ScssPhp\Parser\StringScanner;
use ScssPhp\ScssPhp\SourceSpan\MultiSpan;
use ScssPhp\ScssPhp\Util\Character;
use ScssPhp\ScssPhp\Util\IterableUtil;
use ScssPhp\ScssPhp\Util\LoggerUtil;
use ScssPhp\ScssPhp\Util\NumberUtil;
use ScssPhp\ScssPhp\Util\SpanUtil;
use ScssPhp\ScssPhp\Util\StringUtil;
use ScssPhp\ScssPhp\Value\CalculationOperation;
use ScssPhp\ScssPhp\Value\CalculationOperator;
use ScssPhp\ScssPhp\Value\ColorFormatEnum;
use ScssPhp\ScssPhp\Value\ListSeparator;
use ScssPhp\ScssPhp\Value\SassBoolean;
use ScssPhp\ScssPhp\Value\SassCalculation;
use ScssPhp\ScssPhp\Value\SassColor;
use ScssPhp\ScssPhp\Value\SassFunction;
use ScssPhp\ScssPhp\Value\SassList;
use ScssPhp\ScssPhp\Value\SassMap;
use ScssPhp\ScssPhp\Value\SassMixin;
use ScssPhp\ScssPhp\Value\SassNumber;
use ScssPhp\ScssPhp\Value\SassString;
use ScssPhp\ScssPhp\Value\SpanColorFormat;
use ScssPhp\ScssPhp\Value\Value;
use ScssPhp\ScssPhp\Visitor\CssVisitor;
use ScssPhp\ScssPhp\Visitor\SelectorVisitor;
use ScssPhp\ScssPhp\Visitor\ValueVisitor;

/**
 * @internal
 *
 * @template-implements CssVisitor<void>
 * @template-implements ValueVisitor<void>
 * @template-implements SelectorVisitor<void>
 */
final class SerializeVisitor implements CssVisitor, ValueVisitor, SelectorVisitor
{
    private readonly SourceMapBuffer $buffer;

    /**
     * The current indentation of the CSS output.
     *
     * @var int
     */
    private int $indentation = 0;

    /**
     * Whether we're emitting an unambiguous representation of the source
     * structure, as opposed to valid CSS.
     */
    private readonly bool $inspect;

    /**
     * Whether quoted strings should be emitted with quotes.
     */
    private readonly bool $quote;

    private readonly LoggerInterface $logger;

    private readonly bool $compressed;

    public function __construct(bool $inspect = false, bool $quote = true, OutputStyle $style = OutputStyle::EXPANDED, bool $sourceMap = false, ?LoggerInterface $logger = null)
    {
        $this->buffer = $sourceMap ? new TrackingSourceMapBuffer() : new SimpleStringBuffer();
        $this->inspect = $inspect;
        $this->quote = $quote;
        $this->logger = $logger ?? new QuietLogger();
        $this->compressed = $style === OutputStyle::COMPRESSED;
    }

    public function getBuffer(): SourceMapBuffer
    {
        return $this->buffer;
    }

    public function visitCssStylesheet(CssStylesheet $node): void
    {
        $previous = null;

        foreach ($node->getChildren() as $child) {
            if ($this->isInvisible($child)) {
                continue;
            }

            if ($previous !== null) {
                if ($this->requiresSemicolon($previous)) {
                    $this->buffer->writeChar(';');
                }

                if ($this->isTrailingComment($child, $previous)) {
                    $this->writeOptionalSpace();
                } else {
                    $this->writeLineFeed();

                    if ($previous->isGroupEnd()) {
                        $this->writeLineFeed();
                    }
                }
            }

            $previous = $child;
            $child->accept($this);
        }

        if ($previous !== null && $this->requiresSemicolon($previous) && !$this->compressed) {
            $this->buffer->writeChar(';');
        }
    }

    public function visitCssComment(CssComment $node): void
    {
        $this->for($node, function () use ($node) {
            // Preserve comments that start with `/*!`.
            if ($this->compressed && !$node->isPreserved()) {
                return;
            }

            // Ignore sourceMappingURL and sourceURL comments.
            if (preg_match('{^/\*# source(Mapping)?URL=}', $node->getText())) {
                return;
            }

            $minimumIndentation = $this->minimumIndentation($node->getText());
            assert($minimumIndentation !== -1);

            if ($minimumIndentation === null) {
                $this->writeIndentation();
                $this->buffer->write($node->getText());
                return;
            }

            $minimumIndentation = min($minimumIndentation, $node->getSpan()->getStart()->getColumn());
            $this->writeIndentation();
            $this->writeWithIndent($node->getText(), $minimumIndentation);
        });
    }

    public function visitCssAtRule(CssAtRule $node): void
    {
        $this->writeIndentation();

        $this->for($node, function () use ($node) {
            $this->buffer->writeChar('@');
            $this->write($node->getName());

            $value = $node->getValue();

            if ($value !== null) {
                $this->buffer->writeChar(' ');
                $this->write($value);
            }

            if (!$node->isChildless()) {
                $this->writeOptionalSpace();
                $this->visitChildren($node);
            }
        });
    }

    public function visitCssMediaRule(CssMediaRule $node): void
    {
        $this->writeIndentation();

        $this->for($node, function () use ($node) {
            $this->buffer->write('@media');

            $firstQuery = $node->getQueries()[0];

            if (!$this->compressed || $firstQuery->getModifier() !== null || $firstQuery->getType() !== null || (\count($firstQuery->getConditions()) === 1) && str_starts_with($firstQuery->getConditions()[0], '(not ')) {
                $this->buffer->writeChar(' ');
            }

            $this->writeBetween($node->getQueries(), $this->getCommaSeparator(), $this->visitMediaQuery(...));
        });

        $this->writeOptionalSpace();
        $this->visitChildren($node);
    }

    public function visitCssImport(CssImport $node): void
    {
        $this->writeIndentation();

        $this->for($node, function () use ($node) {
            $this->buffer->write('@import');
            $this->writeOptionalSpace();
            $this->for($node->getUrl(), function () use ($node) {
                $this->writeImportUrl($node->getUrl()->getValue());
            });

            if ($node->getModifiers() !== null) {
                $this->writeOptionalSpace();
                $this->write($node->getModifiers());
            }
        });
    }

    /**
     * Writes $url, which is an import's URL, to the buffer.
     */
    private function writeImportUrl(string $url): void
    {
        if (!$this->compressed || $url[0] !== 'u') {
            $this->buffer->write($url);
            return;
        }

        // If this is url(...), remove the surrounding function. This is terser and
        // it allows us to remove whitespace between `@import` and the URL.
        $urlContents = substr($url, 4, \strlen($url) - 5);

        $maybeQuote = $urlContents[0];
        if ($maybeQuote === "'" || $maybeQuote === '"') {
            $this->buffer->write($urlContents);
        } else {
            // If the URL didn't contain quotes, write them manually.
            $this->visitQuotedString($urlContents);
        }
    }

    public function visitCssKeyframeBlock(CssKeyframeBlock $node): void
    {
        $this->writeIndentation();

        $this->for($node->getSelector(), function () use ($node) {
            $this->writeBetween($node->getSelector()->getValue(), $this->getCommaSeparator(), $this->buffer->write(...));
        });
        $this->writeOptionalSpace();
        $this->visitChildren($node);
    }

    private function visitMediaQuery(CssMediaQuery $query): void
    {
        if ($query->getModifier() !== null) {
            $this->buffer->write($query->getModifier());
            $this->buffer->writeChar(' ');
        }

        if ($query->getType() !== null) {
            $this->buffer->write($query->getType());

            if (\count($query->getConditions())) {
                $this->buffer->write(' and ');
            }
        }

        if (\count($query->getConditions()) === 1 && str_starts_with($query->getConditions()[0], '(not ')) {
            $this->buffer->write('not ');
            $condition = $query->getConditions()[0];
            $this->buffer->write(substr($condition, \strlen('(not '), \strlen($condition) - (\strlen('(not ') + 1)));
        } else {
            $operator = $query->isConjunction() ? 'and' : 'or';

            $this->writeBetween($query->getConditions(), $this->compressed ? "$operator " : " $operator ", $this->buffer->write(...));
        }
    }

    public function visitCssStyleRule(CssStyleRule $node): void
    {
        $this->writeIndentation();

        $this->for($node->getSelector(), function () use ($node) {
            $node->getSelector()->accept($this);
        });
        $this->writeOptionalSpace();
        $this->visitChildren($node);
    }

    public function visitCssSupportsRule(CssSupportsRule $node): void
    {
        $this->writeIndentation();

        $this->for($node, function () use ($node) {
            $this->buffer->write('@supports');

            if (!($this->compressed && $node->getCondition()->getValue()[0] === '(')) {
                $this->buffer->writeChar(' ');
            }

            $this->write($node->getCondition());
        });
        $this->writeOptionalSpace();
        $this->visitChildren($node);
    }

    public function visitCssDeclaration(CssDeclaration $node): void
    {
        if ($node->getInterleavedRules() !== []) {
            \assert($node->getParent() !== null);
            $declSpecificities = $this->specificities($node->getParent());

            foreach ($node->getInterleavedRules() as $rule) {
                $ruleSpecificities = $this->specificities($rule);

                // If the declaration can never match with the same specificity as one
                // of its sibling rules, then ordering will never matter and there's no
                // need to warn about the declaration being re-ordered.
                if (!IterableUtil::any($declSpecificities, fn ($s) => \in_array($s, $ruleSpecificities, true))) {
                    continue;
                }

                LoggerUtil::warnForDeprecation(
                    $this->logger,
                    Deprecation::mixedDecls,
                    <<<'MESSAGE'
                    Sass's behavior for declarations that appear after nested
                    rules will be changing to match the behavior specified by CSS in an upcoming
                    version. To keep the existing behavior, move the declaration above the nested
                    rule. To opt into the new behavior, wrap the declaration in `& {}`.

                    More info: https://sass-lang.com/d/mixed-decls
                    MESSAGE,
                    new MultiSpan($node->getSpan(), 'declaration', [
                        'nested rule' => $rule->getSpan(),
                    ]),
                    $node->getTrace()
                );
            }
        }

        $this->writeIndentation();
        $this->write($node->getName());
        $this->buffer->writeChar(':');

        // If `node` is a custom property that was parsed as a normal Sass-syntax
        // property (such as `#{--foo}: ...`), we serialize its value using the
        // normal Sass property logic as well.
        if ($node->isCustomProperty() && $node->isParsedAsCustomProperty()) {
            $this->for($node->getValue(), function () use ($node) {
                if ($this->compressed) {
                    $this->writeFoldedValue($node);
                } else {
                    $this->writeReindentedValue($node);
                }
            });
        } else {
            $this->writeOptionalSpace();

            try {
                $this->buffer->forSpan($node->getValueSpanForMap(), fn () => $node->getValue()->getValue()->accept($this));
            } catch (SassScriptException $error) {
                throw $error->withSpan($node->getValue()->getSpan());
            }
        }
    }

    /**
     * Returns the set of possible specificities with which $node might match.
     *
     * @return non-empty-array<int>
     */
    private function specificities(CssParentNode $node): array
    {
        if ($node instanceof CssStyleRule) {
            // Plain CSS style rule nesting implicitly wraps parent selectors in
            // `:is()`, so they all match with the highest specificity among any of
            // them.
            if ($node->getParent() !== null) {
                $parent = max($this->specificities($node->getParent()));
            } else {
                $parent = 0;
            }

            return array_map(fn (ComplexSelector $selector) => $parent + $selector->getSpecificity(), $node->getSelector()->getComponents());
        }

        if ($node->getParent() !== null) {
            return $this->specificities($node->getParent());
        }

        return [0];
    }

    /**
     * Emits the value of $node, with all newlines followed by whitespace
     */
    private function writeFoldedValue(CssDeclaration $node): void
    {
        $value = $node->getValue()->getValue();
        assert($value instanceof SassString);
        $scannner = new StringScanner($value->getText());

        while (!$scannner->isDone()) {
            $next = $scannner->readUtf8Char();
            if ($next !== "\n") {
                $this->buffer->writeChar($next);
                continue;
            }

            $this->buffer->writeChar(' ');
            while (Character::isWhitespace($scannner->peekChar())) {
                $scannner->readChar();
            }
        }
    }

    /**
     * Emits the value of $node, re-indented relative to the current indentation.
     */
    private function writeReindentedValue(CssDeclaration $node): void
    {
        $nodeValue = $node->getValue()->getValue();
        assert($nodeValue instanceof SassString);
        $value = $nodeValue->getText();

        $minimumIndentation = $this->minimumIndentation($value);
        if ($minimumIndentation === null) {
            $this->buffer->write($value);
            return;
        }

        if ($minimumIndentation === -1) {
            $this->buffer->write(StringUtil::trimAsciiRight($value, true));
            $this->buffer->writeChar(' ');
            return;
        }

        $minimumIndentation = min($minimumIndentation, $node->getName()->getSpan()->getStart()->getColumn());
        $this->writeWithIndent($value, $minimumIndentation);
    }

    /**
     * Returns the indentation level of the least-indented non-empty line in
     * $text after the first.
     *
     * Returns `null` if $text contains no newlines, and -1 if it contains
     * newlines but no lines are indented.
     */
    private function minimumIndentation(string $text): ?int
    {
        $scanner = new LineScanner($text);
        while (!$scanner->isDone() && $scanner->readChar() !== "\n") {
        }

        if ($scanner->isDone()) {
            return $scanner->peekChar(-1) === "\n" ? -1 : null;
        }

        $min = null;
        while (!$scanner->isDone()) {
            while (!$scanner->isDone()) {
                $next = $scanner->peekChar();
                if ($next !== ' ' && $next !== "\t") {
                    break;
                }
                $scanner->readChar();
            }

            if ($scanner->isDone() || $scanner->scanChar("\n")) {
                continue;
            }

            $min = $min === null ? $scanner->getColumn() : min($min, $scanner->getColumn());

            while (!$scanner->isDone() && $scanner->readChar() !== "\n") {
            }
        }

        return $min ?? -1;
    }

    /**
     * Writes $text to {@see buffer}, replacing $minimumIndentation with
     * {@see indentation} for each non-empty line after the first.
     *
     * Compresses trailing empty lines of $text into a single trailing space.
     */
    private function writeWithIndent(string $text, int $minimumIndentation): void
    {
        $scanner = new LineScanner($text);

        while (!$scanner->isDone()) {
            $next = $scanner->readChar();

            if ($next === "\n") {
                break;
            }
            $this->buffer->writeChar($next);
        }

        while (true) {
            assert(Character::isWhitespace($scanner->peekChar(-1)));
            // Scan forward until we hit non-whitespace or the end of [text].
            $lineStart = $scanner->getPosition();
            $newlines = 1;

            while (true) {
                // If we hit the end of $text, we still need to preserve the fact that
                // whitespace exists because it could matter for custom properties.
                if ($scanner->isDone()) {
                    $this->buffer->writeChar(' ');
                    return;
                }

                $next = $scanner->readChar();

                if ($next === ' ' || $next === "\t") {
                    continue;
                }

                if ($next !== "\n") {
                    break;
                }

                $lineStart = $scanner->getPosition();
                $newlines++;
            }

            $this->writeTimes("\n", $newlines);
            $this->writeIndentation();
            $this->buffer->write($scanner->substring($lineStart + $minimumIndentation));

            // Scan and write until we hit a newline or the end of $text.
            while (true) {
                if ($scanner->isDone()) {
                    return;
                }
                $next = $scanner->readChar();
                if ($next === "\n") {
                    break;
                }
                $this->buffer->writeChar($next);
            }
        }
    }

    // ## Values

    public function visitBoolean(SassBoolean $value): void
    {
        $this->buffer->write($value->getValue() ? 'true' : 'false');
    }

    public function visitCalculation(SassCalculation $value): void
    {
        $this->buffer->write($value->getName());
        $this->buffer->writeChar('(');

        $isFirst = true;

        foreach ($value->getArguments() as $argument) {
            if ($isFirst) {
                $isFirst = false;
            } else {
                $this->buffer->write($this->getCommaSeparator());
            }

            $this->writeCalculationValue($argument);
        }
        $this->buffer->writeChar(')');
    }

    private function writeCalculationValue(object $value): void
    {
        if ($value instanceof SassNumber && $value->hasComplexUnits() && !$this->inspect) {
            throw new SassScriptException("$value is not a valid CSS value.");
        }
        if ($value instanceof SassNumber && !is_finite($value->getValue())) {
            if (is_nan($value->getValue())) {
                $this->buffer->write('NaN');
            } elseif ($value->getValue() > 0) {
                $this->buffer->write('infinity');
            } else {
                $this->buffer->write('-infinity');
            }

            $this->writeCalculationUnits($value->getNumeratorUnits(), $value->getDenominatorUnits());
        } elseif ($value instanceof SassNumber && $value->hasComplexUnits()) {
            $this->writeNumber($value->getValue());

            $firstUnit = $value->getNumeratorUnits()[0] ?? null;

            if ($firstUnit !== null) {
                $this->buffer->write($firstUnit);
                $this->writeCalculationUnits(array_slice($value->getNumeratorUnits(), 1), $value->getDenominatorUnits());
            } else {
                $this->writeCalculationUnits([], $value->getDenominatorUnits());
            }
        } elseif ($value instanceof Value) {
            $value->accept($this);
        } elseif ($value instanceof CalculationOperation) {
            $left = $value->getLeft();
            $parenthesizeLeft = $left instanceof CalculationOperation && $left->getOperator()->getPrecedence() < $value->getOperator()->getPrecedence();

            if ($parenthesizeLeft) {
                $this->buffer->writeChar('(');
            }
            $this->writeCalculationValue($left);
            if ($parenthesizeLeft) {
                $this->buffer->writeChar(')');
            }

            $operatorWhitespace = !$this->compressed || $value->getOperator()->getPrecedence() === 1;
            if ($operatorWhitespace) {
                $this->buffer->writeChar(' ');
            }
            $this->buffer->write($value->getOperator()->getOperator());
            if ($operatorWhitespace) {
                $this->buffer->writeChar(' ');
            }

            $right = $value->getRight();
            $parenthesizeRight = ($right instanceof CalculationOperation && $this->parenthesizeCalculationRhs($value->getOperator(), $right->getOperator()))
                || ($value->getOperator() === CalculationOperator::DIVIDED_BY && $right instanceof SassNumber && (is_finite($right->getValue()) ? $right->hasComplexUnits() : $right->hasUnits()));

            if ($parenthesizeRight) {
                $this->buffer->writeChar('(');
            }
            $this->writeCalculationValue($right);
            if ($parenthesizeRight) {
                $this->buffer->writeChar(')');
            }
        }
    }

    /**
     * Writes the complex numerator and denominator units beyond the first
     * numerator unit for a number as they appear in a calculation.
     *
     * @param list<string> $numeratorUnits
     * @param list<string> $denominatorUnits
     */
    private function writeCalculationUnits(array $numeratorUnits, array $denominatorUnits): void
    {
        foreach ($numeratorUnits as $unit) {
            $this->writeOptionalSpace();
            $this->buffer->writeChar('*');
            $this->writeOptionalSpace();
            $this->buffer->writeChar('1');
            $this->buffer->write($unit);
        }

        foreach ($denominatorUnits as $unit) {
            $this->writeOptionalSpace();
            $this->buffer->writeChar('/');
            $this->writeOptionalSpace();
            $this->buffer->writeChar('1');
            $this->buffer->write($unit);
        }
    }

    /**
     * Returns whether the right-hand operation of a calculation should be
     * parenthesized.
     *
     * In `a ? (b # c)`, `outer` is `?` and `right` is `#`.
     */
    private function parenthesizeCalculationRhs(CalculationOperator $outer, CalculationOperator $right): bool
    {
        if ($outer === CalculationOperator::DIVIDED_BY) {
            return true;
        }

        if ($outer === CalculationOperator::PLUS) {
            return false;
        }

        return $right === CalculationOperator::PLUS || $right === CalculationOperator::MINUS;
    }

    public function visitColor(SassColor $value): void
    {
        $name = Colors::RGBaToColorName($value->getRed(), $value->getGreen(), $value->getBlue(), $value->getAlpha());

        // In compressed mode, emit colors in the shortest representation possible.
        if ($this->compressed) {
            if (!NumberUtil::fuzzyEquals($value->getAlpha(), 1)) {
                $this->writeRgb($value);
            } else {
                $canUseShortHex = $this->canUseShortHex($value);
                $hexLength = $canUseShortHex ? 4 : 7;

                if ($name !== null && \strlen($name) <= $hexLength) {
                    $this->buffer->write($name);
                } elseif ($canUseShortHex) {
                    $this->buffer->writeChar('#');
                    $this->buffer->writeChar(dechex($value->getRed() & 0xF));
                    $this->buffer->writeChar(dechex($value->getGreen() & 0xF));
                    $this->buffer->writeChar(dechex($value->getBlue() & 0xF));
                } else {
                    $this->buffer->writeChar('#');
                    $this->writeHexComponent($value->getRed());
                    $this->writeHexComponent($value->getGreen());
                    $this->writeHexComponent($value->getBlue());
                }
            }

            return;
        }

        $format = $value->getFormat();

        if ($format !== null) {
            if ($format === ColorFormatEnum::rgbFunction) {
                $this->writeRgb($value);
            } elseif ($format === ColorFormatEnum::hslFunction) {
                $this->writeHsl($value);
            } elseif ($format instanceof SpanColorFormat) {
                $this->buffer->write($format->getOriginal());
            } else {
                // should not happen as our interface is sealed.
                \assert(false, 'unknown format');
            }
        } elseif (
            $name !== null &&
            // Always emit generated transparent colors in rgba format. This works
            // around an IE bug. See https://github.com/sass/sass/issues/1782.
            !NumberUtil::fuzzyEquals($value->getAlpha(), 0)
        ) {
            $this->buffer->write($name);
        } elseif (NumberUtil::fuzzyEquals($value->getAlpha(), 1)) {
            $this->buffer->writeChar('#');
            $this->writeHexComponent($value->getRed());
            $this->writeHexComponent($value->getGreen());
            $this->writeHexComponent($value->getBlue());
        } else {
            $this->writeRgb($value);
        }
    }

    /**
     * Writes $value as an `rgb` or `rgba` function.
     */
    private function writeRgb(SassColor $value): void
    {
        $opaque = NumberUtil::fuzzyEquals($value->getAlpha(), 1);
        $this->buffer->write($opaque ? 'rgb(' : 'rgba(');
        $this->buffer->write((string) $value->getRed());
        $this->buffer->write($this->getCommaSeparator());
        $this->buffer->write((string) $value->getGreen());
        $this->buffer->write($this->getCommaSeparator());
        $this->buffer->write((string) $value->getBlue());

        if (!$opaque) {
            $this->buffer->write($this->getCommaSeparator());
            $this->writeNumber($value->getAlpha());
        }

        $this->buffer->writeChar(')');
    }

    /**
     * Writes $value as an `hsl` or `hsla` function.
     */
    private function writeHsl(SassColor $value): void
    {
        $opaque = NumberUtil::fuzzyEquals($value->getAlpha(), 1);
        $this->buffer->write($opaque ? 'hsl(' : 'hsla(');
        $this->writeNumber($value->getHue());
        $this->buffer->write($this->getCommaSeparator());
        $this->writeNumber($value->getSaturation());
        $this->buffer->writeChar('%');
        $this->buffer->write($this->getCommaSeparator());
        $this->writeNumber($value->getLightness());
        $this->buffer->writeChar('%');

        if (!$opaque) {
            $this->buffer->write($this->getCommaSeparator());
            $this->writeNumber($value->getAlpha());
        }

        $this->buffer->writeChar(')');
    }

    /**
     * Returns whether $color's hex pair representation is symmetrical (e.g. `FF`).
     */
    private function isSymmetricalHex(int $color): bool
    {
        return ($color & 0xF) === $color >> 4;
    }

    /**
     * Returns whether $color can be represented as a short hexadecimal color
     * (e.g. `#fff`).
     */
    private function canUseShortHex(SassColor $color): bool
    {
        return $this->isSymmetricalHex($color->getRed()) && $this->isSymmetricalHex($color->getGreen()) && $this->isSymmetricalHex($color->getBlue());
    }

    /**
     * Emits $color as a hex character pair.
     */
    private function writeHexComponent(int $color): void
    {
        $this->buffer->write(str_pad(dechex($color), 2, '0', STR_PAD_LEFT));
    }

    public function visitFunction(SassFunction $value): void
    {
        if (!$this->inspect) {
            throw new SassScriptException("$value is not a valid CSS value.");
        }

        $this->buffer->write('get-function(');
        $this->visitQuotedString($value->getCallable()->getName());
        $this->buffer->writeChar(')');
    }

    public function visitMixin(SassMixin $value): void
    {
        if (!$this->inspect) {
            throw new SassScriptException("$value is not a valid CSS value.");
        }

        $this->buffer->write('get-mixin(');
        $this->visitQuotedString($value->getCallable()->getName());
        $this->buffer->writeChar(')');
    }

    public function visitList(SassList $value): void
    {
        if ($value->hasBrackets()) {
            $this->buffer->writeChar('[');
        } elseif (\count($value->asList()) === 0) {
            if (!$this->inspect) {
                throw new SassScriptException("() is not a valid CSS value.");
            }

            $this->buffer->write('()');
            return;
        }

        $singleton = $this->inspect && \count($value->asList()) === 1 && ($value->getSeparator() === ListSeparator::COMMA || $value->getSeparator() === ListSeparator::SLASH);

        if ($singleton && !$value->hasBrackets()) {
            $this->buffer->writeChar('(');
        }

        $separator = $this->separatorString($value->getSeparator());

        $isFirst = true;

        foreach ($value->asList() as $element) {
            if (!$this->inspect && $element->isBlank()) {
                continue;
            }

            if ($isFirst) {
                $isFirst = false;
            } else {
                $this->buffer->write($separator);
            }

            $needsParens = $this->inspect && self::elementNeedsParens($value->getSeparator(), $element);

            if ($needsParens) {
                $this->buffer->writeChar('(');
            }

            $element->accept($this);

            if ($needsParens) {
                $this->buffer->writeChar(')');
            }
        }

        if ($singleton) {
            \assert($value->getSeparator()->getSeparator() !== null, 'The list separator is not undecided at that point.');
            $this->buffer->write($value->getSeparator()->getSeparator());

            if (!$value->hasBrackets()) {
                $this->buffer->writeChar(')');
            }
        }

        if ($value->hasBrackets()) {
            $this->buffer->writeChar(']');
        }
    }

    private function separatorString(ListSeparator $separator): string
    {
        return match ($separator) {
            ListSeparator::COMMA => $this->getCommaSeparator(),
            ListSeparator::SLASH => $this->compressed ? '/' : ' / ',
            ListSeparator::SPACE => ' ',
            /**
             * This should never be used, but it may still be returned since
             * {@see separatorString} is invoked eagerly by {@see writeList} even for lists
             * with only one element.
             */
            default => '',
        };
    }

    /**
     * Returns whether the value needs parentheses as an element in a list with the given separator.
     */
    private static function elementNeedsParens(ListSeparator $separator, Value $value): bool
    {
        if (!$value instanceof SassList) {
            return false;
        }

        if (count($value->asList()) < 2) {
            return false;
        }

        if ($value->hasBrackets()) {
            return false;
        }

        return match ($separator) {
            ListSeparator::COMMA => $value->getSeparator() === ListSeparator::COMMA,
            ListSeparator::SLASH => $value->getSeparator() === ListSeparator::COMMA || $value->getSeparator() === ListSeparator::SLASH,
            default => $value->getSeparator() !== ListSeparator::UNDECIDED,
        };
    }

    public function visitMap(SassMap $value): void
    {
        if (!$this->inspect) {
            throw new SassScriptException("$value is not a valid CSS value.");
        }

        $this->buffer->writeChar('(');

        $isFirst = true;

        foreach ($value->getContents() as $key => $element) {
            if ($isFirst) {
                $isFirst = false;
            } else {
                $this->buffer->write(', ');
            }

            $this->writeMapElement($key);
            $this->buffer->write(': ');
            $this->writeMapElement($element);
        }
        $this->buffer->writeChar(')');
    }

    private function writeMapElement(Value $value): void
    {
        $needsParens = $value instanceof SassList
            && ListSeparator::COMMA === $value->getSeparator()
            && !$value->hasBrackets();

        if ($needsParens) {
            $this->buffer->writeChar('(');
        }

        $value->accept($this);

        if ($needsParens) {
            $this->buffer->writeChar(')');
        }
    }

    public function visitNull(): void
    {
        if ($this->inspect) {
            $this->buffer->write('null');
        }
    }

    public function visitNumber(SassNumber $value): void
    {
        $asSlash = $value->getAsSlash();

        if ($asSlash !== null) {
            $this->visitNumber($asSlash[0]);
            $this->buffer->writeChar('/');
            $this->visitNumber($asSlash[1]);

            return;
        }

        if (!is_finite($value->getValue())) {
            $this->visitCalculation(SassCalculation::unsimplified('calc', [$value]));
            return;
        }

        if ($value->hasComplexUnits()) {
            if (!$this->inspect) {
                throw new SassScriptException("$value is not a valid CSS value.");
            }

            $this->visitCalculation(SassCalculation::unsimplified('calc', [$value]));
        } else {
            $this->writeNumber($value->getValue());

            if (\count($value->getNumeratorUnits()) > 0) {
                $this->buffer->write($value->getNumeratorUnits()[0]);
            }
        }
    }

    /**
     * Writes $number without exponent notation and with at most
     * {@see SassNumber::PRECISION} digits after the decimal point.
     */
    private function writeNumber(float $number): void
    {
        if (is_nan($number)) {
            $this->buffer->write('NaN');
            return;
        }

        if ($number === INF) {
            $this->buffer->write('Infinity');
            return;
        }

        if ($number === -INF) {
            $this->buffer->write('-Infinity');
            return;
        }

        $int = NumberUtil::fuzzyAsInt($number);

        if ($int !== null) {
            $this->buffer->write((string) $int);
            return;
        }


        $text = $this->removeExponent((string) $number);

        // Any double that's less than `SassNumber.precision + 2` digits long is
        // guaranteed to be safe to emit directly, since it'll contain at most `0.`
        // followed by [SassNumber.precision] digits.
        $canWriteDirectly = \strlen($text) < SassNumber::PRECISION + 2;

        if ($canWriteDirectly) {
            if ($this->compressed && $text[0] === '0') {
                $text = substr($text, 1);
            }

            $this->buffer->write($text);
            return;
        }

        $this->writeRounded($text);
    }

    /**
     * If $text is written in exponent notation, returns a string representation
     * of it without exponent notation.
     *
     * Otherwise, returns $text as-is.
     */
    private function removeExponent(string $text): string
    {
        $exponentDelimiterPosition = strpos($text, 'E');

        if ($exponentDelimiterPosition === false) {
            return $text;
        }

        $negative = $text[0] === '-';

        $buffer = $text[0];

        // If the number has more than one significant digit, the second
        // character will be a decimal point that we don't want to include in
        // the generated number.
        if ($negative) {
            $buffer .= $text[1];

            if ($exponentDelimiterPosition > 3) {
                $buffer .= substr($text, 3, $exponentDelimiterPosition - 3);
            }
        } elseif ($exponentDelimiterPosition > 2) {
            $buffer .= substr($text, 2, $exponentDelimiterPosition - 2);
        }

        $exponent = intval(substr($text, $exponentDelimiterPosition + 1));

        if ($exponent > 0) {
            // Write an additional zero for each exponent digits other than those
            // already written to the buffer. We subtract 1 from `buffer.length`
            // because the first digit doesn't count towards the exponent. Subtract 1
            // more for negative numbers because of the `-` written to the buffer.
            $additionalZeroes = $exponent - (\strlen($buffer) - 1 - ($negative ? 1 : 0));
            $buffer .= str_repeat('0', $additionalZeroes);

            return $buffer;
        }

        $result = '';
        if ($negative) {
            $result .= '-';
        }
        $result .= '0.';
        for ($i = -1; $i > $exponent; --$i) {
            $result .= '0';
        }

        $result .= $negative ? substr($buffer, 1) : $buffer;

        return $result;
    }

    /**
     * Assuming $text is a number written without exponent notation, rounds it
     * to {@see SassNumber::PRECISION} digits after the decimal and writes the result
     * to {@see $buffer}.
     */
    private function writeRounded(string $text): void
    {
        \assert(preg_match('/^-?\d+(\.\d+)?$/D', $text) === 1, "\"$text\" should be a number written without exponent notation.");

        // We need to ensure that we write at most [SassNumber.precision] digits
        // after the decimal point, and that we round appropriately if necessary. To
        // do this, we maintain an intermediate buffer of digits (both before and
        // after the decimal point), which we then write to [_buffer] as text. We
        // start writing after the first digit to give us room to round up to a
        // higher decimal place than was represented in the original number.
        $digits = array_fill(0, \strlen($text) + 1, 0);
        $digitsIndex = 1;

        // Write the digits before the decimal to $digits.
        $textIndex = 0;
        $negative = $text[0] === '-';
        if ($negative) {
            $textIndex++;
        }

        while (true) {
            if ($textIndex === \strlen($text)) {
                // If we get here, $text has no decimal point. It definitely doesn't
                // need to be rounded; we can write it as-is.
                $this->buffer->write($text);
                return;
            }

            $codeUnit = $text[$textIndex++];
            if ($codeUnit === '.') {
                break;
            }

            $digits[$digitsIndex++] = intval($codeUnit);
        }

        $firstFractionalDigit = $digitsIndex;

        // Only write at most PRECISION digits after the decimal. If there aren't
        // that many digits left in the number, write it as-is since no rounding or
        // truncation is needed.
        $indexAfterPrecision = $textIndex + SassNumber::PRECISION;
        if ($indexAfterPrecision >= \strlen($text)) {
            $this->buffer->write($text);
            return;
        }

        // Write the digits after the decimal to $digits.
        while ($textIndex < $indexAfterPrecision) {
            $digits[$digitsIndex++] = intval($text[$textIndex++]);
        }

        // Round the trailing digits in $digits up if necessary.
        if (intval($text[$textIndex]) >= 5) {
            while (true) {
                // $digitsIndex is guaranteed to be >0 here because we added a leading
                // 0 to $digits when we constructed it, so even if we round everything
                // up $newDigit will always be 1 when $digitsIndex is 1.
                $newDigit = ++$digits[$digitsIndex - 1];

                if ($newDigit !== 10) {
                    break;
                }
                $digitsIndex--;
            }
        }

        // At most one of the following loops will actually execute. If we rounded
        // digits up before the decimal point, the first loop will set those digits
        // to 0 (rather than 10, which is not a valid decimal digit). On the other
        // hand, if we have trailing zeros left after the decimal point, the second
        // loop will move $digitsIndex before them and cause them not to be
        // written. Either way, $digitsIndex will end up >= $firstFractionalDigit.
        for (; $digitsIndex < $firstFractionalDigit; $digitsIndex++) {
            $digits[$digitsIndex] = 0;
        }
        while ($digitsIndex > $firstFractionalDigit && $digits[$digitsIndex - 1] === 0) {
            $digitsIndex--;
        }

        // Omit the minus sign if the number ended up being rounded to exactly zero,
        // write "0" explicit to avoid adding a minus sign or omitting the number
        // entirely in compressed mode.
        if ($digitsIndex === 2 && $digits[0] === 0 && $digits[1] == 0) {
            $this->buffer->writeChar('0');
            return;
        }

        if ($negative) {
            $this->buffer->writeChar('-');
        }

        // Write the digits before the decimal point to $buffer. Omit the leading
        // 0 that's added to $digits to accommodate rounding, and in compressed
        // mode omit the 0 before the decimal point as well.
        $writtenIndex = 0;

        if ($digits[0] === 0) {
            $writtenIndex++;
            if ($this->compressed && $digits[1] === 0) {
                $writtenIndex++;
            }
        }

        for (; $writtenIndex < $firstFractionalDigit; $writtenIndex++) {
            $this->buffer->writeChar((string) $digits[$writtenIndex]);
        }

        if ($digitsIndex > $firstFractionalDigit) {
            $this->buffer->writeChar('.');

            for (; $writtenIndex < $digitsIndex; $writtenIndex++) {
                $this->buffer->writeChar((string) $digits[$writtenIndex]);
            }
        }
    }

    public function visitString(SassString $value): void
    {
        if ($this->quote && $value->hasQuotes()) {
            $this->visitQuotedString($value->getText());
        } else {
            $this->visitUnquotedString($value->getText());
        }
    }

    private function visitQuotedString(string $string): void
    {
        $includesDoubleQuote = str_contains($string, '"');
        $includesSingleQuote = str_contains($string, '\'');
        $forceDoubleQuotes = $includesSingleQuote && $includesDoubleQuote;
        $quote = $forceDoubleQuotes || !$includesDoubleQuote ? '"' : "'";

        $this->buffer->writeChar($quote);

        $length = \strlen($string);

        for ($i = 0; $i < $length; $i++) {
            $char = $string[$i];

            switch ($char) {
                case "'":
                    $this->buffer->writeChar("'"); // such string is always rendered double-quoted
                    break;

                case '"':
                    if ($forceDoubleQuotes) {
                        $this->buffer->writeChar('\\');
                    }
                    $this->buffer->writeChar('"');
                    break;

                case "\0":
                case "\x1":
                case "\x2":
                case "\x3":
                case "\x4":
                case "\x5":
                case "\x6":
                case "\x7":
                case "\x8":
                case "\xA":
                case "\xB":
                case "\xC":
                case "\xD":
                case "\xE":
                case "\xF":
                case "\x10":
                case "\x11":
                case "\x12":
                case "\x13":
                case "\x14":
                case "\x15":
                case "\x16":
                case "\x17":
                case "\x18":
                case "\x19":
                case "\x1A":
                case "\x1B":
                case "\x1C":
                case "\x1D":
                case "\x1E":
                case "\x1F":
                case "\x7F":
                    $this->writeEscape($this->buffer, $char, $string, $i);
                    break;

                case '\\':
                    $this->buffer->writeChar('\\');
                    $this->buffer->writeChar('\\');
                    break;

                default:
                    $newIndex = $this->tryPrivateUseCharacter($this->buffer, $char, $string, $i);

                    if ($newIndex !== null) {
                        $i = $newIndex;
                        break;
                    }

                    $this->buffer->writeChar($char);
                    break;
            }
        }

        $this->buffer->writeChar($quote);
    }

    private function visitUnquotedString(string $string): void
    {
        $afterNewline = false;
        $length = \strlen($string);

        for ($i = 0; $i < $length; ++$i) {
            $char = $string[$i];

            switch ($char) {
                case "\n":
                    $this->buffer->writeChar(' ');
                    $afterNewline = true;
                    break;

                case ' ':
                    if (!$afterNewline) {
                        $this->buffer->writeChar(' ');
                    }
                    break;

                default:
                    $afterNewline = false;
                    $newIndex = $this->tryPrivateUseCharacter($this->buffer, $char, $string, $i);

                    if ($newIndex !== null) {
                        $i = $newIndex;
                        break;
                    }

                    $this->buffer->writeChar($char);
                    break;
            }
        }
    }

    /**
     * If $char is the beginning of a private-use character and Sass isn't
     * emitting compressed CSS, writes that character as an escape to $buffer.
     *
     * The $string is the string from which $char was read, and $i is the
     * index it was read from. If this successfully writes the character, returns
     * the index of the *last* byte that was consumed for it. Otherwise,
     * returns `null`.
     *
     * In expanded mode, we print all characters in Private Use Areas as escape
     * codes since there's no useful way to render them directly. These
     * characters are often used for glyph fonts, where it's useful for readers
     * to be able to distinguish between them in the rendered stylesheet.
     */
    private function tryPrivateUseCharacter(SourceMapBuffer $buffer, string $char, string $string, int $i): ?int
    {
        if ($this->compressed) {
            return null;
        }

        $firstByteCode = \ord($char);
        if ($firstByteCode >= 0xF0) {
            $extraBytes = 3; // 4-bytes chars
        } elseif ($firstByteCode >= 0xE0) {
            $extraBytes = 2; // 3-bytes chars
        } elseif ($firstByteCode >= 0xC2) {
            $extraBytes = 1; // 2-bytes chars
        } elseif ($firstByteCode >= 0x80 && $firstByteCode <= 0x8F) {
            return null; // Continuation of a UTF-8 char started in a previous byte
        } else {
            $extraBytes = 0;
        }

        if (\strlen($string) <= $i + $extraBytes) {
            return null; // Invalid UTF-8 chars
        }

        if ($extraBytes) {
            $fullChar = substr($string, $i, $extraBytes + 1);
            $charCode = mb_ord($fullChar, 'UTF-8');
        } else {
            $fullChar = $char;
            $charCode = $firstByteCode;
        }

        if (
            $charCode >= 0xE000 && $charCode <= 0xF8FF || // PUA of the BMP
            $charCode >= 0xF0000 && $charCode <= 0x10FFFF // Supplementary PUAs of the planes 15 and 16
        ) {
            $this->writeEscape($buffer, $fullChar, $string, $i + $extraBytes);

            return $i + $extraBytes;
        }

        return null;
    }

    /**
     * Writes $character as a hexadecimal escape sequence to $buffer.
     *
     * The $string is the string from which the escape is being written, and $i
     * is the index of the last byte of $character in that string. These
     * are used to write a trailing space after the escape if necessary to
     * disambiguate it from the next character.
     */
    private function writeEscape(SourceMapBuffer $buffer, string $character, string $string, int $i): void
    {
        $buffer->writeChar('\\');
        $buffer->write(dechex(mb_ord($character, 'UTF-8')));

        if (\strlen($string) === $i + 1) {
            return;
        }

        $next = $string[$i + 1];

        if ($next === ' ' || $next === "\t" || Character::isHex($next)) {
            $buffer->writeChar(' ');
        }
    }

    // ## Selectors

    public function visitAttributeSelector(AttributeSelector $attribute): void
    {
        $this->buffer->writeChar('[');
        $this->buffer->write($attribute->getName());

        $value = $attribute->getValue();

        if ($value !== null) {
            assert($attribute->getOp() !== null);
            $this->buffer->write($attribute->getOp()->getText());

            // Emit identifiers that start with `--` with quotes, because IE11
            // doesn't consider them to be valid identifiers.
            if (Parser::isIdentifier($value) && !str_starts_with($value, '--')) {
                $this->buffer->write($value);

                if ($attribute->getModifier() !== null) {
                    $this->buffer->writeChar(' ');
                }
            } else {
                $this->visitQuotedString($value);

                if ($attribute->getModifier() !== null) {
                    $this->writeOptionalSpace();
                }
            }

            if ($attribute->getModifier() !== null) {
                $this->buffer->write($attribute->getModifier());
            }
        }

        $this->buffer->writeChar(']');
    }

    public function visitClassSelector(ClassSelector $klass): void
    {
        $this->buffer->writeChar('.');
        $this->buffer->write($klass->getName());
    }

    public function visitComplexSelector(ComplexSelector $complex): void
    {
        $this->writeCombinators($complex->getLeadingCombinators());

        if (\count($complex->getLeadingCombinators()) !== 0 && \count($complex->getComponents()) !== 0) {
            $this->writeOptionalSpace();
        }

        foreach ($complex->getComponents() as $i => $component) {
            $this->visitCompoundSelector($component->getSelector());

            if (\count($component->getCombinators()) !== 0) {
                $this->writeOptionalSpace();
            }

            $this->writeCombinators($component->getCombinators());

            if ($i !== \count($complex->getComponents()) - 1 && (!$this->compressed || \count($component->getCombinators()) === 0)) {
                $this->buffer->writeChar(' ');
            }
        }
    }

    /**
     * Writes $combinators to {@see buffer}, with spaces in between in expanded
     * mode.
     *
     * @param list<CssValue<Combinator>> $combinators
     */
    private function writeCombinators(array $combinators): void
    {
        $this->writeBetween($combinators, $this->compressed ? '' : ' ', function ($text) {
            $this->buffer->write($text);
        });
    }

    public function visitCompoundSelector(CompoundSelector $compound): void
    {
        $start = $this->buffer->getLength();

        foreach ($compound->getComponents() as $simple) {
            $simple->accept($this);
        }

        // If we emit an empty compound, it's because all of the components got
        // optimized out because they match all selectors, so we just emit the
        // universal selector.
        if ($this->buffer->getLength() === $start) {
            $this->buffer->writeChar('*');
        }
    }

    public function visitIDSelector(IDSelector $id): void
    {
        $this->buffer->writeChar('#');
        $this->buffer->write($id->getName());
    }

    public function visitSelectorList(SelectorList $list): void
    {
        $first = true;

        foreach ($list->getComponents() as $complex) {
            if (!$this->inspect && $complex->isInvisible()) {
                continue;
            }

            if ($first) {
                $first = false;
            } else {
                $this->buffer->writeChar(',');

                if ($complex->getLineBreak()) {
                    $this->writeLineFeed();
                    $this->writeIndentation();
                } else {
                    $this->writeOptionalSpace();
                }
            }

            $this->visitComplexSelector($complex);
        }
    }

    public function visitParentSelector(ParentSelector $parent): void
    {
        $this->buffer->writeChar('&');

        if ($parent->getSuffix() !== null) {
            $this->buffer->write($parent->getSuffix());
        }
    }

    public function visitPlaceholderSelector(PlaceholderSelector $placeholder): void
    {
        $this->buffer->writeChar('%');
        $this->buffer->write($placeholder->getName());
    }

    public function visitPseudoSelector(PseudoSelector $pseudo): void
    {
        $innerSelector = $pseudo->getSelector();

        // `:not(%a)` is semantically identical to `*`.
        if ($innerSelector !== null && $pseudo->getName() === 'not' && $innerSelector->isInvisible()) {
            return;
        }

        $this->buffer->writeChar(':');
        if ($pseudo->isSyntacticElement()) {
            $this->buffer->writeChar(':');
        }
        $this->buffer->write($pseudo->getName());

        if ($pseudo->getArgument() === null && $pseudo->getSelector() === null) {
            return;
        }

        $this->buffer->writeChar('(');

        if ($pseudo->getArgument() !== null) {
            $this->buffer->write($pseudo->getArgument());

            if ($pseudo->getSelector() !== null) {
                $this->buffer->writeChar(' ');
            }
        }

        if ($innerSelector !== null) {
            $this->visitSelectorList($innerSelector);
        }

        $this->buffer->writeChar(')');
    }

    public function visitTypeSelector(TypeSelector $type): void
    {
        $this->buffer->write($type->getName());
    }

    public function visitUniversalSelector(UniversalSelector $universal): void
    {
        if ($universal->getNamespace() !== null) {
            $this->buffer->write($universal->getNamespace());
            $this->buffer->writeChar('|');
        }
        $this->buffer->writeChar('*');
    }

    // ## Utilities

    /**
     * Runs $callback and associates all text written within it with the span of $node
     *
     * @template T
     *
     * @param callable(): T $callback
     *
     * @return T
     *
     * @param-immediately-invoked-callable $callback
     */
    private function for(AstNode $node, callable $callback)
    {
        return $this->buffer->forSpan($node->getSpan(), $callback);
    }

    /**
     * @param CssValue<string> $value
     */
    private function write(CssValue $value): void
    {
        $this->for($value, function () use ($value) {
            $this->buffer->write($value->getValue());
        });
    }

    /**
     * Emits `$parent->getChildren()` in a block
     */
    private function visitChildren(CssParentNode $parent): void
    {
        $this->buffer->writeChar('{');

        $prePrevious = null;
        $previous = null;

        foreach ($parent->getChildren() as $child) {
            if ($this->isInvisible($child)) {
                continue;
            }

            if ($previous !== null && $this->requiresSemicolon($previous)) {
                $this->buffer->writeChar(';');
            }

            if ($this->isTrailingComment($child, $previous ?? $parent)) {
                $this->writeOptionalSpace();
                $this->withoutIndentation(function () use ($child) {
                    $child->accept($this);
                });
            } else {
                $this->writeLineFeed();
                $this->indent(function () use ($child) {
                    $child->accept($this);
                });
            }

            $prePrevious = $previous;
            $previous = $child;
        }

        if ($previous !== null) {
            if ($this->requiresSemicolon($previous) && !$this->compressed) {
                $this->buffer->writeChar(';');
            }

            if ($prePrevious === null && $this->isTrailingComment($previous, $parent)) {
                $this->writeOptionalSpace();
            } else {
                $this->writeLineFeed();
                $this->writeIndentation();
            }
        }

        $this->buffer->writeChar('}');
    }

    /**
     * Whether $node requires a semicolon to be written after it.
     */
    private function requiresSemicolon(CssNode $node): bool
    {
        if ($node instanceof CssParentNode) {
            return $node->isChildless();
        }

        return !$node instanceof CssComment;
    }

    private function isTrailingComment(CssNode $node, CssNode $previous): bool
    {
        // Short-circuit in compressed mode to avoid expensive span shenanigans
        // (shespanigans?), since we're compressing all whitespace anyway.
        if ($this->compressed) {
            return false;
        }

        if (!$node instanceof CssComment) {
            return false;
        }

        if ($node->getSpan()->getSourceUrl() !== $previous->getSpan()->getSourceUrl()) {
            return false;
        }

        if (!SpanUtil::contains($previous->getSpan(), $node->getSpan())) {
            return $node->getSpan()->getStart()->getLine() === $previous->getSpan()->getEnd()->getLine();
        }

        // Walk back from just before the current node starts looking for the
        // parent's left brace (to open the child block). This is safer than a
        // simple forward search of the previous.span.text as that might contain
        // other left braces.
        $searchFrom = $node->getSpan()->getStart()->getOffset() - $previous->getSpan()->getStart()->getOffset() - 1;

        // Imports can cause a node to be "contained" by another node when they are
        // actually the same node twice in a row.
        if ($searchFrom < 0) {
            return false;
        }

        $previousSpanText = $previous->getSpan()->getText();
        $endOffset = strrpos($previousSpanText, '{', $searchFrom - \strlen($previousSpanText));
        if ($endOffset === false) {
            $endOffset = 0;
        }

        $span = $previous->getSpan()->getFile()->span($previous->getSpan()->getStart()->getOffset(), $previous->getSpan()->getStart()->getOffset() + $endOffset);

        return $node->getSpan()->getStart()->getLine() === $span->getEnd()->getLine();
    }

    /**
     * Writes a line feed, unless this emitting compressed CSS.
     */
    private function writeLineFeed(): void
    {
        if (!$this->compressed) {
            $this->buffer->writeChar("\n");
        }
    }

    private function writeOptionalSpace(): void
    {
        if (!$this->compressed) {
            $this->buffer->writeChar(' ');
        }
    }

    private function writeIndentation(): void
    {
        if (!$this->compressed) {
            $this->writeTimes(' ', $this->indentation * 2);
        }
    }

    /**
     * Writes $char to {@see buffer} with $times repetitions.
     */
    private function writeTimes(string $char, int $times): void
    {
        for ($i = 0; $i < $times; $i++) {
            $this->buffer->writeChar($char);
        }
    }

    /**
     * Calls $callback to write each value in $iterable, and writes $text
     * between each one.
     *
     * @template T
     *
     * @param iterable<T>       $iterable
     * @param callable(T): void $callback
     *
     * @param-immediately-invoked-callable $callback
     */
    private function writeBetween(iterable $iterable, string $text, callable $callback): void
    {
        $first = true;

        foreach ($iterable as $value) {
            if ($first) {
                $first = false;
            } else {
                $this->buffer->write($text);
            }

            $callback($value);
        }
    }

    /**
     * Returns a comma used to separate values in lists.
     */
    private function getCommaSeparator(): string
    {
        return $this->compressed ? ',' : ', ';
    }

    /**
     * Runs $callback with indentation increased one level.
     *
     * @param callable(): void $callback
     *
     * @param-immediately-invoked-callable $callback
     */
    private function indent(callable $callback): void
    {
        $this->indentation++;
        $callback();
        $this->indentation--;
    }

    /**
     * Runs $callback without any indentation.
     *
     * @param callable(): void $callback
     *
     * @param-immediately-invoked-callable $callback
     */
    private function withoutIndentation(callable $callback): void
    {
        $savedIndentation = $this->indentation;
        $this->indentation = 0;
        $callback();
        $this->indentation = $savedIndentation;
    }

    /**
     * Returns whether $node is invisible.
     */
    private function isInvisible(CssNode $node): bool
    {
        return !$this->inspect && ($this->compressed ? $node->isInvisibleHidingComments() : $node->isInvisible());
    }
}
