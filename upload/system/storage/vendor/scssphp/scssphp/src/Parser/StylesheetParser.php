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
use League\Uri\Exceptions\SyntaxError;
use League\Uri\Uri;
use ScssPhp\ScssPhp\Ast\Sass\Argument;
use ScssPhp\ScssPhp\Ast\Sass\ArgumentDeclaration;
use ScssPhp\ScssPhp\Ast\Sass\ArgumentInvocation;
use ScssPhp\ScssPhp\Ast\Sass\Expression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\BinaryOperationExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\BinaryOperator;
use ScssPhp\ScssPhp\Ast\Sass\Expression\BooleanExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\ColorExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\FunctionExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\IfExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\InterpolatedFunctionExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\ListExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\MapExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\NullExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\NumberExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\ParenthesizedExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\SelectorExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\StringExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\SupportsExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\UnaryOperationExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\UnaryOperator;
use ScssPhp\ScssPhp\Ast\Sass\Expression\VariableExpression;
use ScssPhp\ScssPhp\Ast\Sass\Import;
use ScssPhp\ScssPhp\Ast\Sass\Import\DynamicImport;
use ScssPhp\ScssPhp\Ast\Sass\Import\StaticImport;
use ScssPhp\ScssPhp\Ast\Sass\Interpolation;
use ScssPhp\ScssPhp\Ast\Sass\Statement;
use ScssPhp\ScssPhp\Ast\Sass\Statement\AtRootRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\AtRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\ContentBlock;
use ScssPhp\ScssPhp\Ast\Sass\Statement\ContentRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\DebugRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\Declaration;
use ScssPhp\ScssPhp\Ast\Sass\Statement\EachRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\ElseClause;
use ScssPhp\ScssPhp\Ast\Sass\Statement\ErrorRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\ExtendRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\ForRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\FunctionRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\IfClause;
use ScssPhp\ScssPhp\Ast\Sass\Statement\IfRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\ImportRule;
use ScssPhp\ScssPhp\Ast\Sass\Statement\IncludeRule;
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
use ScssPhp\ScssPhp\Ast\Sass\SupportsCondition;
use ScssPhp\ScssPhp\Ast\Sass\SupportsCondition\SupportsAnything;
use ScssPhp\ScssPhp\Ast\Sass\SupportsCondition\SupportsDeclaration;
use ScssPhp\ScssPhp\Ast\Sass\SupportsCondition\SupportsFunction;
use ScssPhp\ScssPhp\Ast\Sass\SupportsCondition\SupportsInterpolation;
use ScssPhp\ScssPhp\Ast\Sass\SupportsCondition\SupportsNegation;
use ScssPhp\ScssPhp\Ast\Sass\SupportsCondition\SupportsOperation;
use ScssPhp\ScssPhp\Colors;
use ScssPhp\ScssPhp\Deprecation;
use ScssPhp\ScssPhp\Exception\SassFormatException;
use ScssPhp\ScssPhp\Logger\LoggerInterface;
use ScssPhp\ScssPhp\Util;
use ScssPhp\ScssPhp\Util\Character;
use ScssPhp\ScssPhp\Util\LoggerUtil;
use ScssPhp\ScssPhp\Util\Path;
use ScssPhp\ScssPhp\Util\StringUtil;
use ScssPhp\ScssPhp\Value\ListSeparator;
use ScssPhp\ScssPhp\Value\SassColor;
use ScssPhp\ScssPhp\Value\SpanColorFormat;
use SourceSpan\FileSpan;

/**
 * @internal
 */
abstract class StylesheetParser extends Parser
{
    /**
     * The silent comment this parser encountered previously.
     */
    protected ?SilentComment $lastSilentComment = null;

    /**
     * Whether we've consumed a rule other than `@charset`, `@forward`, or `@use`.
     */
    private bool $isUseAllowed = true;

    /**
     * Whether the parser is currently parsing the contents of a mixin declaration.
     */
    private bool $inMixin = false;

    /**
     * Whether the parser is currently parsing a content block passed to a mixin.
     */
    private bool $inContentBlock = false;

    /**
     * Whether the parser is currently parsing a control directive such as `@if`
     * or `@each`.
     */
    private bool $inControlDirective = false;

    /**
     * Whether the parser is currently parsing an unknown rule.
     */
    private bool $inUnknownAtRule = false;

    /**
     * Whether the parser is currently parsing a style rule.
     */
    private bool $inStyleRule = false;

    /**
     * Whether the parser is currently within a parenthesized expression.
     */
    private bool $inParentheses = false;

    /**
     * Whether the parser is currently within an expression.
     */
    private bool $inExpression = false;

    /**
     * A map from all variable names that are assigned with `!global` in the
     * current stylesheet to the nodes where they're defined.
     *
     * These are collected at parse time because they affect the variables
     * exposed by the module generated for this stylesheet, *even if they aren't
     * evaluated*. This allows us to ensure that the stylesheet always exposes
     * the same set of variable names no matter how it's evaluated.
     *
     * @var array<string, VariableDeclaration>
     */
    private array $globalVariables = [];

    public function __construct(string $contents, ?LoggerInterface $logger = null, ?UriInterface $sourceUrl = null)
    {
        parent::__construct($contents, $logger, $sourceUrl);
    }

    protected function inExpression(): bool
    {
        return $this->inExpression;
    }

    /**
     * @throws SassFormatException when parsing fails
     */
    public function parse(): Stylesheet
    {
        return $this->wrapSpanFormatException(function () {
            $start = $this->scanner->getPosition();

            // Allow a byte-order mark at the beginning of the document.
            $this->scanner->scan("\u{FEFF}");

            $statements = $this->statements(function () {
                // Handle this specially so that {@see atRule} always returns a non-nullable Statement.
                if ($this->scanner->scan('@charset')) {
                    $this->whitespace();
                    $this->string();

                    return null;
                }

                return $this->statement(true);
            });

            $this->scanner->expectDone();

            // Ensure that all global variable assignments produce a variable in this
            // stylesheet, even if they aren't evaluated. See sass/language#50.
            foreach ($this->globalVariables as $declaration) {
                $statements[] = new VariableDeclaration($declaration->getName(), new NullExpression($declaration->getExpression()->getSpan()), $declaration->getSpan(), null, true);
            }

            return new Stylesheet($statements, $this->scanner->spanFrom($start), $this->isPlainCss());
        });
    }

    public function parseArgumentDeclaration(): ArgumentDeclaration
    {
        return $this->wrapSpanFormatException(function () {
            $this->scanner->expectChar('@', '@-rule');
            $this->identifier();
            $this->whitespace();
            $this->identifier();
            $arguments = $this->argumentDeclaration();
            $this->whitespace();
            $this->scanner->expectChar('{');

            $this->scanner->expectDone();

            return $arguments;
        });
    }

    /**
     * Consumes a statement that's allowed at the top level of the stylesheet or
     * within nested style and at rules.
     *
     * If $root is `true`, this parses at-rules that are allowed only at the
     * root of the stylesheet.
     */
    private function statement(bool $root = false): Statement
    {
        switch ($this->scanner->peekChar()) {
            case '@':
                return $this->atRule($this->statement(...), $root);

            case '+':
                if (!$this->isIndented() || !$this->lookingAtIdentifier(1)) {
                    return $this->styleRule();
                }

                $this->isUseAllowed = false;
                $start = $this->scanner->getPosition();
                $this->scanner->readChar();

                return $this->includeRule($start);

            case '=':
                if (!$this->isIndented()) {
                    return $this->styleRule();
                }

                $this->isUseAllowed = false;
                $start = $this->scanner->getPosition();
                $this->scanner->readChar();
                $this->whitespace();

                return $this->mixinRule($start);

            case '}':
                $this->scanner->error('unmatched "}".');

            default:
                if ($this->inStyleRule || $this->inUnknownAtRule || $this->inMixin || $this->inContentBlock) {
                    return $this->declarationOrStyleRule();
                }

                return $this->variableDeclarationOrStyleRule();
        }
    }

    /**
     * Consumes a namespaced variable declaration.
     *
     * @throws FormatException
     */
    private function variableDeclarationWithNamespace(): VariableDeclaration
    {
        $start = $this->scanner->getPosition();
        $namespace = $this->identifier();
        $this->scanner->expectChar('.');

        return $this->variableDeclarationWithoutNamespace($namespace, $start);
    }

    /**
     * Consumes a variable declaration.
     */
    protected function variableDeclarationWithoutNamespace(?string $namespace = null, ?int $start = null): VariableDeclaration
    {
        $precedingComment = $this->lastSilentComment;
        $this->lastSilentComment = null;
        $start = $start ?? $this->scanner->getPosition();

        $name = $this->variableName();

        if ($namespace !== null) {
            $this->assertPublic($name, fn() => $this->scanner->spanFrom($start));
        }

        if ($this->isPlainCss()) {
            $this->error('Sass variables aren\'t allowed in plain CSS.', $this->scanner->spanFrom($start));
        }

        $this->whitespace();
        $this->scanner->expectChar(':');
        $this->whitespace();

        $value = $this->expression();

        $guarded = false;
        $global = false;
        $flagStart = $this->scanner->getPosition();

        while ($this->scanner->scanChar('!')) {
            $flag = $this->identifier();
            if ($flag === 'default') {
                if ($guarded) {
                    LoggerUtil::warnForDeprecation($this->logger, Deprecation::duplicateVarFlags, "!default should only be written once for each variable.\nThis will be an error in Dart Sass 2.0.0.", $this->scanner->spanFrom($flagStart));
                }

                $guarded = true;
            } elseif ($flag === 'global') {
                if ($namespace !== null) {
                    $this->error("!global isn't allowed for variables in other modules.", $this->scanner->spanFrom($flagStart));
                } elseif ($global) {
                    LoggerUtil::warnForDeprecation($this->logger, Deprecation::duplicateVarFlags, "!global should only be written once for each variable.\nThis will be an error in Dart Sass 2.0.0.", $this->scanner->spanFrom($flagStart));
                }

                $global = true;
            } else {
                $this->error('Invalid flag name.', $this->scanner->spanFrom($flagStart));
            }

            $this->whitespace();
            $flagStart = $this->scanner->getPosition();
        }

        $this->expectStatementSeparator('variable declaration');

        // TODO remove this when implementing modules
        if ($namespace !== null) {
            $this->error('Sass modules are not implemented yet.', $this->scanner->spanFrom($start));
        }

        $declaration = new VariableDeclaration($name, $value, $this->scanner->spanFrom($start), $namespace, $guarded, $global, $precedingComment);

        if ($global && !isset($this->globalVariables[$name])) {
            $this->globalVariables[$name] = $declaration;
        }

        return $declaration;
    }

    private function variableDeclarationOrStyleRule(): Statement
    {
        if ($this->isPlainCss()) {
            return $this->styleRule();
        }

        // The indented syntax allows a single backslash to distinguish a style rule
        // from old-style property syntax. We don't support old property syntax, but
        // we do support the backslash because it's easy to do.
        if ($this->isIndented() && $this->scanner->scanChar('\\')) {
            return $this->styleRule();
        }

        if (!$this->lookingAtIdentifier()) {
            return $this->styleRule();
        }

        $start = $this->scanner->getPosition();
        $variableOrInterpolation = $this->variableDeclarationOrInterpolation();

        if ($variableOrInterpolation instanceof VariableDeclaration) {
            return $variableOrInterpolation;
        }

        $buffer = new InterpolationBuffer();
        $buffer->addInterpolation($variableOrInterpolation);

        return $this->styleRule($buffer, $start);
    }

    /**
     * Consumes a {@see VariableDeclaration}, a {@see Declaration}, or a {@see StyleRule}.
     *
     * @throws FormatException
     */
    private function declarationOrStyleRule(): Statement
    {
        // The indented syntax allows a single backslash to distinguish a style rule
        // from old-style property syntax. We don't support old property syntax, but
        // we do support the backslash because it's easy to do.
        if ($this->isIndented() && $this->scanner->scanChar('\\')) {
            return $this->styleRule();
        }

        $start = $this->scanner->getPosition();

        $declarationBuffer = $this->declarationOrBuffer();

        if ($declarationBuffer instanceof Statement) {
            return $declarationBuffer;
        }

        return $this->styleRule($declarationBuffer, $start);
    }

    /**
     * Tries to parse a variable or property declaration, and returns the value
     * parsed so far if it fails.
     *
     * This can return either an {@see InterpolationBuffer}, indicating that it
     * couldn't consume a declaration and that selector parsing should be
     * attempted; or it can return a {@see Declaration} or a {@see VariableDeclaration},
     * indicating that it successfully consumed a declaration.
     */
    private function declarationOrBuffer(): Statement|InterpolationBuffer
    {
        $start = $this->scanner->getPosition();
        $nameBuffer = new InterpolationBuffer();
        $first = $this->scanner->peekChar();
        $startsWithPunctuation = false;

        // Allow the "*prop: val", ":prop: val", "#prop: val", and ".prop: val"
        // hacks.
        if ($first === ':' || $first === '*' || $first === '.' || ($first === '#' && $this->scanner->peekChar(1) !== '{')) {
            $startsWithPunctuation = true;
            $nameBuffer->write($this->scanner->readChar());
            $nameBuffer->write($this->rawText($this->whitespace(...)));
        }

        if (!$this->lookingAtInterpolatedIdentifier()) {
            return $nameBuffer;
        }

        $variableOrInterpolation = $startsWithPunctuation ? $this->interpolatedIdentifier() : $this->variableDeclarationOrInterpolation();

        if ($variableOrInterpolation instanceof VariableDeclaration) {
            return $variableOrInterpolation;
        }

        $nameBuffer->addInterpolation($variableOrInterpolation);

        $this->isUseAllowed = false;

        if ($this->scanner->matches('/*')) {
            $nameBuffer->write($this->rawText($this->loudComment(...)));
        }

        $midBuffer = $this->rawText($this->whitespace(...));
        $beforeColon = $this->scanner->getPosition();

        if (!$this->scanner->scanChar(':')) {
            if ($midBuffer !== '') {
                $nameBuffer->write(' ');
            }

            return $nameBuffer;
        }

        $midBuffer .= ':';

        // Parse custom properties as declarations no matter what.
        $name = $nameBuffer->buildInterpolation($this->scanner->spanFrom($start, $beforeColon));

        if (str_starts_with($name->getInitialPlain(), '--')) {
            $value = new StringExpression($this->interpolatedDeclarationValue(silentComments: false));
            $this->expectStatementSeparator('custom property');

            return Declaration::create($name, $value, $this->scanner->spanFrom($start));
        }

        if ($this->scanner->scanChar(':')) {
            $nameBuffer->write($midBuffer);
            $nameBuffer->write(':');

            return $nameBuffer;
        }

        if ($this->isIndented() && $this->lookingAtInterpolatedIdentifier()) {
            // In the indented syntax, `foo:bar` is always considered a selector
            // rather than a property.
            $nameBuffer->write($midBuffer);

            return $nameBuffer;
        }

        $postColonWhitespace = $this->rawText($this->whitespace(...));

        $nested = $this->tryDeclarationChildren($name, $start);
        if ($nested !== null) {
            return $nested;
        }

        $midBuffer .= $postColonWhitespace;
        $couldBeSelector = $postColonWhitespace === '' && $this->lookingAtInterpolatedIdentifier();

        $beforeDeclaration = $this->scanner->getPosition();

        try {
            $value = $this->expression();

            if ($this->lookingAtChildren()) {
                // Properties that are ambiguous with selectors can't have additional
                // properties nested beneath them, so we force an error. This will be
                // caught below and cause the text to be reparsed as a selector.
                if ($couldBeSelector) {
                    $this->expectStatementSeparator();
                }
            } elseif (!$this->atEndOfStatement()) {
                // Force an exception if there isn't a valid end-of-property character
                // but don't consume that character. This will also cause the text to be
                // reparsed.
                $this->expectStatementSeparator();
            }
        } catch (FormatException $e) {
            if (!$couldBeSelector) {
                throw $e;
            }

            // If the value would be followed by a semicolon, it's definitely supposed
            // to be a property, not a selector.
            $this->scanner->setPosition($beforeDeclaration);

            $additional = $this->almostAnyValue();

            if (!$this->isIndented() && $this->scanner->peekChar() === ';') {
                throw $e;
            }

            $nameBuffer->write($midBuffer);
            $nameBuffer->addInterpolation($additional);

            return $nameBuffer;
        }

        $nested = $this->tryDeclarationChildren($name, $start, $value);
        if ($nested !== null) {
            return $nested;
        }

        $this->expectStatementSeparator();

        return Declaration::create($name, $value, $this->scanner->spanFrom($start));
    }

    /**
     * Tries to parse a namespaced {@see VariableDeclaration}, and returns the value
     * parsed so far if it fails.
     *
     * This can return either an {@see Interpolation}, indicating that it couldn't
     * consume a variable declaration and that property declaration or selector
     * parsing should be attempted; or it can return a {@see VariableDeclaration},
     * indicating that it successfully consumed a variable declaration.
     */
    private function variableDeclarationOrInterpolation(): Interpolation|VariableDeclaration
    {
        if (!$this->lookingAtIdentifier()) {
            return $this->interpolatedIdentifier();
        }

        $start = $this->scanner->getPosition();
        $identifier = $this->identifier();

        if ($this->scanner->matches('.$')) {
            $this->scanner->readChar();

            return $this->variableDeclarationWithoutNamespace($identifier, $start);
        }

        $buffer = new InterpolationBuffer();
        $buffer->write($identifier);

        // Parse the rest of an interpolated identifier if one exists, so callers
        // don't have to.
        if ($this->lookingAtInterpolatedIdentifierBody()) {
            $buffer->addInterpolation($this->interpolatedIdentifier());
        }

        return $buffer->buildInterpolation($this->scanner->spanFrom($start));
    }

    /**
     * Consumes a StyleRule
     */
    private function styleRule(?InterpolationBuffer $buffer = null, ?int $start = null): StyleRule
    {
        $start = $start ?? $this->scanner->getPosition();
        $interpolation = $this->styleRuleSelector();

        if ($buffer !== null) {
            $buffer->addInterpolation($interpolation);
            $interpolation = $buffer->buildInterpolation($this->scanner->spanFrom($start));
        }

        if (!$interpolation->getContents()) {
            $this->scanner->error('expected "}".');
        }

        $wasInStyleRule = $this->inStyleRule;
        $this->inStyleRule = true;

        return $this->withChildren($this->statement(...), $start, function (array $children) use ($wasInStyleRule, $start, $interpolation) {
            if ($this->isIndented() && $children === []) {
                $this->warn("This selector doesn't have any properties and won't be rendered.", $interpolation->getSpan());
            }

            $this->inStyleRule = $wasInStyleRule;

            return new StyleRule($interpolation, $children, $this->scanner->spanFrom($start));
        });
    }

    /**
     * Consumes either a property declaration or a namespaced variable declaration.
     *
     * This is only used in contexts where declarations are allowed but style
     * rules are not, such as nested declarations. Otherwise,
     * {@see declarationOrStyleRule} is used instead.
     *
     * If $parseCustomProperties is `true`, properties that begin with `--` will
     * be parsed using custom property parsing rules.
     */
    private function propertyOrVariableDeclaration(bool $parseCustomProperties = true): Statement
    {
        $start = $this->scanner->getPosition();

        // Allow the "*prop: val", ":prop: val", "#prop: val", and ".prop: val"
        // hacks.
        $first = $this->scanner->peekChar();
        if ($first === ':' || $first === '*' || $first === '.' || ($first === '#' && $this->scanner->peekChar(1) !== '{')) {
            $nameBuffer = new InterpolationBuffer();
            $nameBuffer->write($this->scanner->readChar());
            $nameBuffer->write($this->rawText($this->whitespace(...)));
            $nameBuffer->addInterpolation($this->interpolatedIdentifier());
            $name = $nameBuffer->buildInterpolation($this->scanner->spanFrom($start));
        } elseif (!$this->isPlainCss()) {
            $variableOrInterpolation = $this->variableDeclarationOrInterpolation();

            if ($variableOrInterpolation instanceof VariableDeclaration) {
                return $variableOrInterpolation;
            }

            $name = $variableOrInterpolation;
        } else {
            $name = $this->interpolatedIdentifier();
        }

        $this->whitespace();
        $this->scanner->expectChar(':');

        if ($parseCustomProperties && str_starts_with($name->getInitialPlain(), '--')) {
            $value = new StringExpression($this->interpolatedDeclarationValue(silentComments: false));
            $this->expectStatementSeparator('custom property');

            return Declaration::create($name, $value, $this->scanner->spanFrom($start));
        }

        $this->whitespace();

        $nested = $this->tryDeclarationChildren($name, $start);
        if ($nested !== null) {
            return $nested;
        }

        $value = $this->expression();

        $nested = $this->tryDeclarationChildren($name, $start, $value);
        if ($nested !== null) {
            return $nested;
        }

        $this->expectStatementSeparator();

        return Declaration::create($name, $value, $this->scanner->spanFrom($start));
    }

    /**
     * Tries parsing nested children of a declaration whose $name has already
     * been parsed, and returns `null` if it doesn't have any.
     *
     * If $value is passed, it's used as the value of the property without
     * nesting.
     */
    private function tryDeclarationChildren(Interpolation $name, int $start, ?Expression $value = null): ?Declaration
    {
        if (!$this->lookingAtChildren()) {
            return null;
        }

        if ($this->isPlainCss()) {
            $this->scanner->error("Nested declarations aren't allowed in plain CSS.");
        }

        return $this->withChildren($this->declarationChild(...), $start, fn(array $children, FileSpan $span) => Declaration::nested($name, $children, $span, $value));
    }

    /**
     * Consumes a statement that's allowed within a declaration.
     */
    private function declarationChild(): Statement
    {
        if ($this->scanner->peekChar() === '@') {
            return $this->declarationAtRule();
        }

        return $this->propertyOrVariableDeclaration(false);
    }

    /**
     * Consumes an at-rule.
     *
     * This consumes at-rules that are allowed at all levels of the document; the
     * $child parameter is called to consume any at-rules that are specifically
     * allowed in the caller's context.
     *
     * If $root is `true`, this parses at-rules that are allowed only at the
     * root of the stylesheet.
     *
     * @param callable(): Statement $child
     *
     * @param-immediately-invoked-callable $child
     */
    protected function atRule(callable $child, bool $root = false): Statement
    {
        $start = $this->scanner->getPosition();
        $this->scanner->expectChar('@', '@-rule');
        $name = $this->interpolatedIdentifier();
        $this->whitespace();

        $wasUseAllowed = $this->isUseAllowed;
        $this->isUseAllowed = false;

        switch ($name->getAsPlain()) {
            case 'at-root':
                return $this->atRootRule($start);
            case 'content':
                return $this->contentRule($start);
            case 'debug':
                return $this->debugRule($start);
            case 'each':
                return $this->eachRule($start, $child);
            case 'else':
                $this->disallowedAtRule($start);
            case 'error':
                return $this->errorRule($start);
            case 'extend':
                return $this->extendRule($start);
            case 'for':
                return $this->forRule($start, $child);
            case 'forward':
                $this->isUseAllowed = $wasUseAllowed;

                if (!$root) {
                    $this->disallowedAtRule($start);
                }

                // TODO remove this when implementing modules
                $this->error('Sass modules are not implemented yet.', $this->scanner->spanFrom($start));
            case 'function':
                return $this->functionRule($start);
            case 'if':
                return $this->ifRule($start, $child);
            case 'import':
                return $this->importRule($start);
            case 'include':
                return $this->includeRule($start);
            case 'media':
                return $this->mediaRule($start);
            case 'mixin':
                return $this->mixinRule($start);
            case '-moz-document':
                return $this->mozDocumentRule($start, $name);
            case 'return':
                $this->disallowedAtRule($start);
            case 'supports':
                return $this->supportsRule($start);
            case 'use':
                $this->isUseAllowed = $wasUseAllowed;

                if (!$root) {
                    $this->disallowedAtRule($start);
                }

                // TODO remove this when implementing modules
                $this->error('Sass modules are not implemented yet.', $this->scanner->spanFrom($start));
            case 'warn':
                return $this->warnRule($start);
            case 'while':
                return $this->whileRule($start, $child);
            default:
                return $this->unknownAtRule($start, $name);
        }
    }

    /**
     * Consumes an at-rule allowed within a property declaration.
     */
    private function declarationAtRule(): Statement
    {
        $start = $this->scanner->getPosition();
        $name = $this->plainAtRuleName();

        switch ($name) {
            case 'content':
                return $this->contentRule($start);
            case 'debug':
                return $this->debugRule($start);
            case 'each':
                return $this->eachRule($start, $this->declarationChild(...));
            case 'else':
                $this->disallowedAtRule($start);
            case 'error':
                return $this->errorRule($start);
            case 'for':
                return $this->forRule($start, $this->declarationChild(...));
            case 'if':
                return $this->ifRule($start, $this->declarationChild(...));
            case 'include':
                return $this->includeRule($start);
            case 'warn':
                return $this->warnRule($start);
            case 'while':
                return $this->whileRule($start, $this->declarationChild(...));
            default:
                $this->disallowedAtRule($start);
        }
    }

    /**
     * Consumes a statement allowed within a function.
     */
    private function functionChild(): Statement
    {
        if ($this->scanner->peekChar() !== '@') {
            $start = $this->scanner->getPosition();

            try {
                return $this->variableDeclarationWithNamespace();
            } catch (FormatException $variableDeclarationError) {
                // TODO remove this when implementing modules
                if ($variableDeclarationError->getMessage() === 'Sass modules are not implemented yet.') {
                    throw $variableDeclarationError;
                }

                $this->scanner->setPosition($start);

                // If a variable declaration failed to parse, it's possible the user
                // thought they could write a style rule or property declaration in a
                // function. If so, throw a more helpful error message.
                try {
                    $statement = $this->declarationOrStyleRule();
                } catch (FormatException) {
                    throw $variableDeclarationError;
                }

                $this->error('@function rules may not contain ' . ($statement instanceof StyleRule ? 'style rules.' : 'declarations.'), $statement->getSpan());
            }
        }

        $start = $this->scanner->getPosition();

        switch ($this->plainAtRuleName()) {
            case 'debug':
                return $this->debugRule($start);
            case 'each':
                return $this->eachRule($start, $this->functionChild(...));
            case 'else':
                $this->disallowedAtRule($start);
            case 'error':
                return $this->errorRule($start);
            case 'for':
                return $this->forRule($start, $this->functionChild(...));
            case 'if':
                return $this->ifRule($start, $this->functionChild(...));
            case 'return':
                return $this->returnRule($start);
            case 'warn':
                return $this->warnRule($start);
            case 'while':
                return $this->whileRule($start, $this->functionChild(...));
            default:
                $this->disallowedAtRule($start);
        }
    }

    /**
     * Consumes an at-rule's name, with interpolation disallowed.
     */
    private function plainAtRuleName(): string
    {
        $this->scanner->expectChar('@', '@-rule');

        $name = $this->identifier();
        $this->whitespace();

        return $name;
    }

    /**
     * Consumes an `@at-root` rule.
     *
     * $start should point before the `@`.
     */
    private function atRootRule(int $start): AtRootRule
    {
        if ($this->scanner->peekChar() === '(') {
            $query = $this->atRootQuery();
            $this->whitespace();

            return $this->withChildren($this->statement(...), $start, fn(array $children, FileSpan $span) => new AtRootRule($children, $span, $query));
        }

        if ($this->lookingAtChildren() || ($this->isIndented() && $this->atEndOfStatement())) {
            return $this->withChildren($this->statement(...), $start, fn(array $children, FileSpan $span) => new AtRootRule($children, $span));
        }

        $child = $this->styleRule();

        return new AtRootRule([$child], $this->scanner->spanFrom($start));
    }

    /**
     * Consumes a query expression of the form `(foo: bar)`.
     */
    private function atRootQuery(): Interpolation
    {
        $start = $this->scanner->getPosition();
        $buffer = new InterpolationBuffer();
        $this->scanner->expectChar('(');
        $buffer->write('(');
        $this->whitespace();

        $this->addOrInject($buffer, $this->expression());

        if ($this->scanner->scanChar(':')) {
            $this->whitespace();
            $buffer->write(': ');
            $this->addOrInject($buffer, $this->expression());
        }

        $this->scanner->expectChar(')');
        $this->whitespace();
        $buffer->write(')');

        return $buffer->buildInterpolation($this->scanner->spanFrom($start));
    }

    /**
     * Consumes a `@content` rule.
     *
     * $start should point before the `@`.
     */
    private function contentRule(int $start): ContentRule
    {
        if (!$this->inMixin) {
            $this->error('@content is only allowed within mixin declarations.', $this->scanner->spanFrom($start));
        }

        $beforeWhitespace = $this->scanner->getLocation();
        $this->whitespace();

        if ($this->scanner->peekChar() === '(') {
            $arguments = $this->argumentInvocation(true);
            $this->whitespace();
        } else {
            $arguments = ArgumentInvocation::createEmpty($beforeWhitespace->pointSpan());
        }

        $this->expectStatementSeparator('@content rule');

        return new ContentRule($arguments, $this->scanner->spanFrom($start));
    }

    /**
     * Consumes a `@debug` rule.
     *
     * $start should point before the `@`.
     */
    private function debugRule(int $start): DebugRule
    {
        $value = $this->expression();
        $this->expectStatementSeparator('@debug rule');

        return new DebugRule($value, $this->scanner->spanFrom($start));
    }

    /**
     * Consumes a `@each` rule.
     *
     * $start should point before the `@`. $child is called to consume any
     * children that are specifically allowed in the caller's context.
     *
     * @param callable(): Statement $child
     *
     * @param-immediately-invoked-callable $child
     */
    private function eachRule(int $start, callable $child): EachRule
    {
        $wasInControlDirective = $this->inControlDirective;
        $this->inControlDirective = true;

        $variables = [$this->variableName()];
        $this->whitespace();

        while ($this->scanner->scanChar(',')) {
            $this->whitespace();
            $variables[] = $this->variableName();
            $this->whitespace();
        }

        $this->expectIdentifier('in');
        $this->whitespace();

        $list = $this->expression();

        return $this->withChildren($child, $start, function (array $children, FileSpan $span) use ($variables, $wasInControlDirective, $list) {
            $this->inControlDirective = $wasInControlDirective;

            return new EachRule($variables, $list, $children, $span);
        });
    }

    /**
     * Consumes a `@error` rule.
     *
     * $start should point before the `@`.
     */
    private function errorRule(int $start): ErrorRule
    {
        $value = $this->expression();
        $this->expectStatementSeparator('@error rule');

        return new ErrorRule($value, $this->scanner->spanFrom($start));
    }

    /**
     * Consumes a `@extend` rule.
     *
     * $start should point before the `@`.
     */
    private function extendRule(int $start): ExtendRule
    {
        if (!$this->inStyleRule && !$this->inMixin && !$this->inContentBlock) {
            $this->error('@extend may only be used within style rules.', $this->scanner->spanFrom($start));
        }

        $value = $this->almostAnyValue();
        $optional = $this->scanner->scanChar('!');

        if ($optional) {
            $this->expectIdentifier('optional');
            $this->whitespace();
        }

        $this->expectStatementSeparator('@extend rule');

        return new ExtendRule($value, $this->scanner->spanFrom($start), $optional);
    }

    /**
     * Consumes a function declaration.
     *
     * $start should point before the `@`.
     */
    private function functionRule(int $start): FunctionRule
    {
        $precedingComment = $this->lastSilentComment;
        $this->lastSilentComment = null;

        $beforeName = $this->scanner->getPosition();
        $name = $this->identifier();

        if (str_starts_with($name, '--')) {
            LoggerUtil::warnForDeprecation(
                $this->logger,
                Deprecation::cssFunctionMixin,
                "Sass @function names beginning with -- are deprecated for forward-compatibility with plain CSS mixins.\n\nFor details, see https://sass-lang.com/d/css-function-mixin",
                $this->scanner->spanFrom($beforeName)
            );
        }

        $this->whitespace();
        $arguments = $this->argumentDeclaration();

        if ($this->inMixin || $this->inContentBlock) {
            $this->error('Mixins may not contain function declarations.', $this->scanner->spanFrom($start));
        }

        if ($this->inControlDirective) {
            $this->error('Functions may not be declared in control directives.', $this->scanner->spanFrom($start));
        }

        switch (Util::unvendor($name)) {
            case 'calc':
            case 'element':
            case 'expression':
            case 'url':
            case 'and':
            case 'or':
            case 'not':
            case 'clamp':
                $this->error('Invalid function name.', $this->scanner->spanFrom($start));
        }

        $this->whitespace();

        return $this->withChildren(
            $this->functionChild(...),
            $start,
            fn(array $children, FileSpan $span) => new FunctionRule($name, $arguments, $span, $children, $precedingComment)
        );
    }

    /**
     * Consumes a `@for` rule.
     *
     * $start should point before the `@`. $child is called to consume any
     * children that are specifically allowed in the caller's context.
     *
     * @param callable(): Statement $child
     *
     * @param-immediately-invoked-callable $child
     */
    private function forRule(int $start, callable $child): ForRule
    {
        $wasInControlDirective = $this->inControlDirective;
        $this->inControlDirective = true;

        $variable = $this->variableName();
        $this->whitespace();

        $this->expectIdentifier('from');
        $this->whitespace();

        $exclusive = null;
        $from = $this->expression(function () use (&$exclusive) {
            if (!$this->lookingAtIdentifier()) {
                return false;
            }

            if ($this->scanIdentifier('to')) {
                $exclusive = true;

                return true;
            }

            if ($this->scanIdentifier('through')) {
                $exclusive = false;

                return true;
            }

            return false;
        });

        if ($exclusive === null) {
            $this->scanner->error('Expected "to" or "through".');
        }

        $this->whitespace();
        $to = $this->expression();

        return $this->withChildren($child, $start, function (array $children, FileSpan $span) use ($variable, $from, $to, $exclusive, $wasInControlDirective) {
            $this->inControlDirective = $wasInControlDirective;

            return new ForRule($variable, $from, $to, $children, $span, $exclusive);
        });
    }

    /**
     * Consumes a `@if` rule.
     *
     * $start should point before the `@`. $child is called to consume any
     * children that are specifically allowed in the caller's context.
     *
     * @param callable(): Statement $child
     *
     * @param-immediately-invoked-callable $child
     */
    private function ifRule(int $start, callable $child): IfRule
    {
        $ifIndentation = $this->getCurrentIndentation();
        $wasInControlDirective = $this->inControlDirective;
        $this->inControlDirective = true;

        $condition = $this->expression();
        $children = $this->children($child);
        $this->whitespaceWithoutComments();

        $clauses = [new IfClause($condition, $children)];
        $lastClause = null;

        while ($this->scanElse($ifIndentation)) {
            $this->whitespace();

            if ($this->scanIdentifier('if')) {
                $this->whitespace();
                $clauses[] = new IfClause($this->expression(), $this->children($child));
            } else {
                $lastClause = new ElseClause($this->children($child));
                break;
            }
        }

        $this->inControlDirective = $wasInControlDirective;
        $span = $this->scanner->spanFrom($start);
        $this->whitespaceWithoutComments();

        return new IfRule($clauses, $span, $lastClause);
    }

    /**
     * Consumes an `@import` rule.
     *
     * $start should point before the `@`.
     */
    private function importRule(int $start): ImportRule
    {
        $imports = [];

        do {
            $this->whitespace();
            $argument = $this->importArgument();

            if (($this->inControlDirective || $this->inMixin) && $argument instanceof DynamicImport) {
                $this->disallowedAtRule($start);
            }

            $imports[] = $argument;
            $this->whitespace();
        } while ($this->scanner->scanChar(','));

        $this->expectStatementSeparator('@import rule');

        return new ImportRule($imports, $this->scanner->spanFrom($start));
    }

    /**
     * Consumes an argument to an `@import` rule.
     */
    protected function importArgument(): Import
    {
        $start = $this->scanner->getPosition();
        $next = $this->scanner->peekChar();

        if ($next === 'u' || $next === 'U') {
            $url = $this->dynamicUrl();
            $this->whitespace();
            $modifiers = $this->tryImportModifiers();

            return new StaticImport(new Interpolation([$url], $this->scanner->spanFrom($start)), $this->scanner->spanFrom($start), $modifiers);
        }

        $url = $this->string();
        $urlSpan = $this->scanner->spanFrom($start);
        $this->whitespace();
        $modifiers = $this->tryImportModifiers();

        if ($this->isPlainImportUrl($url) || $modifiers !== null) {
            return new StaticImport(new Interpolation([$urlSpan->getText()], $urlSpan), $this->scanner->spanFrom($start), $modifiers);
        }

        try {
            return new DynamicImport($this->parseImportUrl($url), $urlSpan);
        } catch (SyntaxError $e) {
            $this->error('Invalid URL: ' . $e->getMessage(), $urlSpan, $e);
        }
    }

    /**
     * Parses $url as an import URL.
     *
     * @throws SyntaxError
     */
    protected function parseImportUrl(string $url): string
    {
        // Backwards-compatibility for implementations that allow absolute Windows
        // paths in imports.
        if (Path::isWindowsAbsolute($url) && !self::isRootRelativeUrl($url)) {
            return (string) Uri::fromWindowsPath($url);
        }

        Uri::new($url);
        return $url;
    }

    private static function isRootRelativeUrl(string $path): bool
    {
        return $path !== '' && $path[0] === '/';
    }

    /**
     * Returns whether $url indicates that an `@import` is a plain CSS import.
     */
    protected function isPlainImportUrl(string $url): bool
    {
        if (\strlen($url) < 5) {
            return false;
        }

        if (str_ends_with($url, '.css')) {
            return true;
        }

        if ($url[0] === '/') {
            return $url[1] === '/';
        }

        if ($url[0] !== 'h') {
            return false;
        }

        return str_starts_with($url, 'http://') || str_starts_with($url, 'https://');
    }

    /**
     * Returns `null` if there are no modifiers.
     */
    protected function tryImportModifiers(): ?Interpolation
    {
        // Exit before allocating anything if we're not looking at any modifiers, as
        // is the most common case.
        if (!$this->lookingAtInterpolatedIdentifier() && $this->scanner->peekChar() !== '(') {
            return null;
        }

        $start = $this->scanner->getPosition();
        $buffer = new InterpolationBuffer();

        while (true) {
            if ($this->lookingAtInterpolatedIdentifier()) {
                if (!$buffer->isEmpty()) {
                    $buffer->write(' ');
                }

                $identifier = $this->interpolatedIdentifier();
                $buffer->addInterpolation($identifier);

                $name = $identifier->getAsPlain() !== null ? strtolower($identifier->getAsPlain()) : null;

                if ($name !== 'and' && $this->scanner->scanChar('(')) {
                    if ($name === 'supports') {
                        $query = $this->importSupportsQuery();

                        if (!$query instanceof SupportsDeclaration) {
                            $buffer->write('(');
                        }

                        $buffer->add(new SupportsExpression($query));

                        if (!$query instanceof SupportsDeclaration) {
                            $buffer->write(')');
                        }
                    } else {
                        $buffer->write('(');
                        $buffer->addInterpolation($this->interpolatedDeclarationValue(true, true));
                        $buffer->write(')');
                    }

                    $this->scanner->expectChar(')');
                    $this->whitespace();
                } else {
                    $this->whitespace();
                    if ($this->scanner->scanChar(',')) {
                        $buffer->write(', ');
                        $buffer->addInterpolation($this->mediaQueryList());

                        return $buffer->buildInterpolation($this->scanner->spanFrom($start));
                    }
                }
            } elseif ($this->scanner->peekChar() === '(') {
                if (!$buffer->isEmpty()) {
                    $buffer->write(' ');
                }
                $buffer->addInterpolation($this->mediaQueryList());

                return $buffer->buildInterpolation($this->scanner->spanFrom($start));
            } else {
                return $buffer->buildInterpolation($this->scanner->spanFrom($start));
            }
        }
    }

    /**
     * Consumes the contents of a `supports()` function after an `@import` rule
     * (but not the function name or parentheses).
     */
    private function importSupportsQuery(): SupportsCondition
    {
        if ($this->scanIdentifier('not')) {
            $this->whitespace();
            $start = $this->scanner->getPosition();

            return new SupportsNegation($this->supportsConditionInParens(), $this->scanner->spanFrom($start));
        }

        if ($this->scanner->peekChar() === '(') {
            return $this->supportsCondition();
        }

        $function = $this->tryImportSupportsFunction();

        if ($function !== null) {
            return $function;
        }

        $start = $this->scanner->getPosition();
        $name = $this->expression();
        $this->scanner->expectChar(':');

        return $this->supportsDeclarationValue($name, $start);
    }

    /**
     * Consumes a function call within a `supports()` function after an
     * `@import` if available.
     */
    private function tryImportSupportsFunction(): ?SupportsCondition
    {
        if (!$this->lookingAtInterpolatedIdentifier()) {
            return null;
        }

        $start = $this->scanner->getPosition();
        $name = $this->interpolatedIdentifier();
        assert($name->getAsPlain() !== 'not');

        if (!$this->scanner->scanChar('(')) {
            $this->scanner->setPosition($start);

            return null;
        }

        $value = $this->interpolatedDeclarationValue(true, true);
        $this->scanner->expectChar(')');

        return new SupportsFunction($name, $value, $this->scanner->spanFrom($start));
    }

    /**
     * Consumes a `@include` rule.
     *
     * $start should point before the `@`.
     */
    private function includeRule(int $start): IncludeRule
    {
        $namespace = null;
        $name = $this->identifier();

        if ($this->scanner->scanChar('.')) {
            $namespace = $name;
            $name = $this->publicIdentifier();
        }

        $this->whitespace();

        $arguments = $this->scanner->peekChar() === '(' ? $this->argumentInvocation(true) : ArgumentInvocation::createEmpty($this->scanner->getEmptySpan());
        $this->whitespace();

        $contentArguments = null;
        if ($this->scanIdentifier('using')) {
            $this->whitespace();
            $contentArguments = $this->argumentDeclaration();
            $this->whitespace();
        }

        $content = null;
        if ($contentArguments !== null || $this->lookingAtChildren()) {
            $contentArguments = $contentArguments ?? ArgumentDeclaration::createEmpty($this->scanner->getEmptySpan());
            $wasInContentBlock = $this->inContentBlock;
            $this->inContentBlock = true;

            $content = $this->withChildren($this->statement(...), $start, fn(array $children, FileSpan $span) => new ContentBlock($contentArguments, $children, $span));

            $this->inContentBlock = $wasInContentBlock;
        } else {
            $this->expectStatementSeparator();
        }

        $span = $this->scanner->spanFrom($start, $start)->expand(($content ?? $arguments)->getSpan());

        // TODO remove this when implementing modules
        if ($namespace !== null) {
            $this->error('Sass modules are not implemented yet.', $this->scanner->spanFrom($start));
        }

        return new IncludeRule($name, $arguments, $span, $namespace, $content);
    }

    /**
     * Consumes a `@media` rule.
     *
     * $start should point before the `@`.
     */
    protected function mediaRule(int $start): MediaRule
    {
        $query = $this->mediaQueryList();

        return $this->withChildren($this->statement(...), $start, fn(array $children, FileSpan $span) => new MediaRule($query, $children, $span));
    }

    /**
     * Consumes a mixin declaration.
     *
     * $start should point before the `@`.
     */
    private function mixinRule(int $start): MixinRule
    {
        $precedingComment = $this->lastSilentComment;
        $this->lastSilentComment = null;

        $beforeName = $this->scanner->getPosition();
        $name = $this->identifier();

        if (str_starts_with($name, '--')) {
            LoggerUtil::warnForDeprecation(
                $this->logger,
                Deprecation::cssFunctionMixin,
                "Sass @mixin names beginning with -- are deprecated for forward-compatibility with plain CSS mixins.\n\nFor details, see https://sass-lang.com/d/css-function-mixin",
                $this->scanner->spanFrom($beforeName)
            );
        }

        $this->whitespace();

        $arguments = $this->scanner->peekChar() === '(' ? $this->argumentDeclaration() : ArgumentDeclaration::createEmpty($this->scanner->getEmptySpan());

        if ($this->inMixin || $this->inContentBlock) {
            $this->error('Mixins may not contain mixin declarations.', $this->scanner->spanFrom($start));
        }

        if ($this->inControlDirective) {
            $this->error('Mixins may not be declared in control directives.', $this->scanner->spanFrom($start));
        }

        $this->whitespace();
        $this->inMixin = true;

        return $this->withChildren($this->statement(...), $start, function (array $children, FileSpan $span) use ($name, $arguments, $precedingComment) {
            $this->inMixin = false;

            return new MixinRule($name, $arguments, $span, $children, $precedingComment);
        });
    }

    /**
     * Consumes a `@moz-document` rule.
     *
     * Gecko's `@-moz-document` diverges from [the specification][] allows the
     * `url-prefix` and `domain` functions to omit quotation marks, contrary to
     * the standard.
     *
     * [the specification]: https://www.w3.org/TR/css3-conditional/
     */
    protected function mozDocumentRule(int $start, Interpolation $name): AtRule
    {
        $valueStart = $this->scanner->getPosition();
        $buffer = new InterpolationBuffer();
        $needsDeprecationWarning = false;

        while (true) {
            if ($this->scanner->peekChar() === '#') {
                $buffer->add($this->singleInterpolation());
                $needsDeprecationWarning = true;
            } else {
                $identifierStart = $this->scanner->getPosition();
                $identifier = $this->identifier();

                switch ($identifier) {
                    case 'url':
                    case 'url-prefix':
                    case 'domain':
                        $contents = $this->tryUrlContents($identifierStart, $identifier);

                        if ($contents !== null) {
                            $buffer->addInterpolation($contents);
                        } else {
                            $this->scanner->expectChar('(');
                            $this->whitespace();
                            $argument = $this->interpolatedString();
                            $this->scanner->expectChar(')');

                            $buffer->write($identifier);
                            $buffer->write('(');
                            $buffer->addInterpolation($argument->asInterpolation());
                            $buffer->write(')');
                        }

                        // A url-prefix with no argument, or with an empty string as an
                        // argument, is not (yet) deprecated.
                        $trailing = $buffer->getTrailingString();
                        if (!str_ends_with($trailing, 'url-prefix()') && !str_ends_with($trailing, "url-prefix('')") && !str_ends_with($trailing, 'url-prefix("")')) {
                            $needsDeprecationWarning = true;
                        }
                        break;

                    case 'regexp':
                        $buffer->write('regexp(');
                        $this->scanner->expectChar('(');
                        $buffer->addInterpolation($this->interpolatedString()->asInterpolation());
                        $this->scanner->expectChar(')');
                        $buffer->write(')');
                        $needsDeprecationWarning = true;
                        break;

                    default:
                        $this->error('Invalid function name.', $this->scanner->spanFrom($identifierStart));
                }
            }

            $this->whitespace();

            if (!$this->scanner->scanChar(',')) {
                break;
            }

            $buffer->write(',');
            $buffer->write($this->rawText($this->whitespace(...)));
        }

        $value = $buffer->buildInterpolation($this->scanner->spanFrom($valueStart));

        return $this->withChildren($this->statement(...), $start, function (array $children, FileSpan $span) use ($name, $value, $needsDeprecationWarning) {
            if ($needsDeprecationWarning) {
                LoggerUtil::warnForDeprecation($this->logger, Deprecation::mozDocument, "@-moz-document is deprecated and support will be removed in Dart Sass 2.0.0.\n\nFor details, see https://sass-lang.com/d/moz-document.", $span);
            }

            return new AtRule($name, $span, $value, $children);
        });
    }

    /**
     * Consumes a `@return` rule.
     *
     * $start should point before the `@`.
     */
    private function returnRule(int $start): ReturnRule
    {
        $value = $this->expression();
        $this->expectStatementSeparator('@return rule');

        return new ReturnRule($value, $this->scanner->spanFrom($start));
    }

    /**
     * Consumes a `@supports` rule.
     *
     * $start should point before the `@`.
     */
    protected function supportsRule(int $start): SupportsRule
    {
        $condition = $this->supportsCondition();
        $this->whitespace();

        return $this->withChildren($this->statement(...), $start, fn(array $children, FileSpan $span) => new SupportsRule($condition, $children, $span));
    }

    /**
     * Consumes a `@warn` rule.
     *
     * $start should point before the `@`.
     */
    private function warnRule(int $start): WarnRule
    {
        $value = $this->expression();
        $this->expectStatementSeparator('@warn rule');

        return new WarnRule($value, $this->scanner->spanFrom($start));
    }

    /**
     * Consumes a `@while` rule.
     *
     * $start should point before the `@`. $child is called to consume any
     * children that are specifically allowed in the caller's context.
     *
     * @param callable(): Statement $child
     *
     * @param-immediately-invoked-callable $child
     */
    private function whileRule(int $start, callable $child): WhileRule
    {
        $wasInControlDirective = $this->inControlDirective;
        $this->inControlDirective = true;

        $condition = $this->expression();

        return $this->withChildren($child, $start, function (array $children, FileSpan $span) use ($condition, $wasInControlDirective) {
            $this->inControlDirective = $wasInControlDirective;

            return new WhileRule($condition, $children, $span);
        });
    }

    /**
     * Consumes an at-rule that's not explicitly supported by Sass.
     *
     * $start should point before the `@`. $name is the name of the at-rule.
     */
    protected function unknownAtRule(int $start, Interpolation $name): AtRule
    {
        $wasInUnknownAtRule = $this->inUnknownAtRule;
        $this->inUnknownAtRule = true;

        $value = null;
        $next = $this->scanner->peekChar();
        if ($next !== '!' && !$this->atEndOfStatement()) {
            $value = $this->interpolatedDeclarationValue(allowOpenBrace: false);
        }

        if ($this->lookingAtChildren()) {
            $rule = $this->withChildren($this->statement(...), $start, fn(array $children, FileSpan $span) => new AtRule($name, $span, $value, $children));
        } else {
            $this->expectStatementSeparator();
            $rule = new AtRule($name, $this->scanner->spanFrom($start), $value);
        }

        $this->inUnknownAtRule = $wasInUnknownAtRule;

        return $rule;
    }

    /**
     * Throws an exception indicating that the at-rule starting at $start is
     * not allowed in the current context.
     */
    private function disallowedAtRule(int $start): never
    {
        $this->interpolatedDeclarationValue(allowEmpty: true, allowOpenBrace: false);
        $this->error('This at-rule is not allowed here.', $this->scanner->spanFrom($start));
    }

    /**
     * Consumes an argument declaration.
     */
    private function argumentDeclaration(): ArgumentDeclaration
    {
        $start = $this->scanner->getPosition();
        $this->scanner->expectChar('(');
        $this->whitespace();

        $arguments = [];
        $named = [];
        $restArgument = null;

        while ($this->scanner->peekChar() === '$') {
            $variableStart = $this->scanner->getPosition();
            $name = $this->variableName();
            $this->whitespace();

            $defaultValue = null;

            if ($this->scanner->scanChar(':')) {
                $this->whitespace();
                $defaultValue = $this->expressionUntilComma();
            } elseif ($this->scanner->scanChar('.')) {
                $this->scanner->expectChar('.');
                $this->scanner->expectChar('.');
                $this->whitespace();
                $restArgument = $name;
                break;
            }

            $argument = new Argument($name, $this->scanner->spanFrom($variableStart), $defaultValue);
            $arguments[] = $argument;

            if (isset($named[$name])) {
                $this->error('Duplicate argument.', $argument->getSpan());
            }
            $named[$name] = true;

            if (!$this->scanner->scanChar(',')) {
                break;
            }
            $this->whitespace();
        }

        $this->scanner->expectChar(')');

        return new ArgumentDeclaration($arguments, $this->scanner->spanFrom($start), $restArgument);
    }

    /**
     * Consumes an argument invocation.
     *
     * If $mixin is `true`, this is parsed as a mixin invocation. Mixin
     * invocations don't allow the Microsoft-style `=` operator at the top level,
     * but function invocations do.
     *
     * If $allowEmptySecondArg is `true`, this allows the second argument to be
     * omitted, in which case an unquoted empty string will be passed in its
     * place.
     */
    private function argumentInvocation(bool $mixin = false, bool $allowEmptySecondArg = false): ArgumentInvocation
    {
        $start = $this->scanner->getPosition();
        $this->scanner->expectChar('(');
        $this->whitespace();

        $positional = [];
        $named = [];
        $rest = null;
        $keywordRest = null;

        while ($this->lookingAtExpression()) {
            $expression = $this->expressionUntilComma(!$mixin);
            $this->whitespace();

            if ($expression instanceof VariableExpression && $this->scanner->scanChar(':')) {
                $this->whitespace();

                if (isset($named[$expression->getName()])) {
                    $this->error('Duplicate argument.', $expression->getSpan());
                }

                $named[$expression->getName()] = $this->expressionUntilComma(!$mixin);
            } elseif ($this->scanner->scanChar('.')) {
                $this->scanner->expectChar('.');
                $this->scanner->expectChar('.');

                if ($rest === null) {
                    $rest = $expression;
                } else {
                    $keywordRest = $expression;
                    $this->whitespace();
                    break;
                }
            } elseif ($named) {
                $this->error('Positional arguments must come before keyword arguments.', $expression->getSpan());
            } else {
                $positional[] = $expression;
            }

            $this->whitespace();

            if (!$this->scanner->scanChar(',')) {
                break;
            }
            $this->whitespace();

            if ($allowEmptySecondArg && \count($positional) === 1 && \count($named) === 0 && $rest === null && $this->scanner->peekChar() === ')') {
                $positional[] = StringExpression::plain('', $this->scanner->getEmptySpan());
                break;
            }
        }

        $this->scanner->expectChar(')');

        return new ArgumentInvocation($positional, $named, $this->scanner->spanFrom($start), $rest, $keywordRest);
    }

    /**
     * Consumes an expression.
     *
     * @param (callable(): bool)|null $until
     * @phpstan-impure
     */
    private function expression(?callable $until = null, bool $singleEquals = false, bool $bracketList = false): Expression
    {
        if ($until !== null && $until()) {
            $this->scanner->error('Expected expression.');
        }

        $beforeBracket = null;

        if ($bracketList) {
            $beforeBracket = $this->scanner->getPosition();
            $this->scanner->expectChar('[');
            $this->whitespace();

            if ($this->scanner->scanChar(']')) {
                return new ListExpression([], ListSeparator::UNDECIDED, $this->scanner->spanFrom($beforeBracket), true);
            }
        }

        $start = $this->scanner->getPosition();
        $wasInExpression = $this->inExpression;
        $wasInParentheses = $this->inParentheses;
        $this->inExpression = true;

        /**
         * @var list<Expression>|null $commaExpressions
         */
        $commaExpressions = null;
        /**
         * @var list<Expression>|null $spaceExpressions
         */
        $spaceExpressions = null;
        /**
         * Operators whose right-hand $operands are not fully parsed yet, in order of
         * appearance in the document. Because a low-precedence operator will cause
         * parsing to finish for all preceding higher-precedence $operators, this is
         * naturally ordered from lowest to highest precedence.
         *
         * @var list<BinaryOperator>|null $operators
         */
        $operators = null;
        /**
         * The left-hand sides of $operators. `$operands[n]` is the left-hand side
         * of `$operators[n]`.
         *
         * @var list<Expression>|null $operands
         */
        $operands = null;

        /**
         * Whether the single expression parsed so far may be interpreted as
         * slash-separated numbers.
         */
        $allowSlash = true;

        /**
         * The leftmost expression that's been fully-parsed. This can be null in
         * special cases where the expression begins with a sub-expression but has
         * a later character that indicates that the outer expression isn't done,
         * as here:
         *
         *     foo, bar
         *         ^
         *
         * @var Expression|null $singleExpression
         */
        $singleExpression = $this->singleExpression();

        /**
         * Resets the scanner state to the state it was at the beginning of the
         * expression, except for {@see $inParentheses}.
         */
        $resetState = function () use (&$commaExpressions, &$spaceExpressions, &$operators, &$operands, &$allowSlash, &$singleExpression, $start): void {
            $commaExpressions = null;
            $spaceExpressions = null;
            $operators = null;
            $operands = null;
            $this->scanner->setPosition($start);
            $allowSlash = true;
            $singleExpression = $this->singleExpression();
        };

        $resolveOneOperation = function () use (&$operands, &$operators, &$singleExpression, &$allowSlash): void {
            assert($operands !== null);
            assert($operators !== null);
            $operator = array_pop($operators);
            assert($operator !== null, 'The list of operators must not be empty');

            $left = array_pop($operands);
            assert($left !== null, 'The list of operands must not be empty');

            $right = $singleExpression;

            if ($right === null) {
                $this->scanner->error('Expected expression.', $this->scanner->getPosition() - \strlen($operator->getOperator()), \strlen($operator->getOperator()));
            }

            if ($allowSlash && !$this->inParentheses && $operator === BinaryOperator::DIVIDED_BY && self::isSlashOperand($left) && self::isSlashOperand($right)) {
                $singleExpression = BinaryOperationExpression::slash($left, $right);
            } else {
                $singleExpression = new BinaryOperationExpression($operator, $left, $right);
                $allowSlash = false;

                if ($operator === BinaryOperator::PLUS || $operator === BinaryOperator::MINUS) {
                    if (
                        $this->scanner->substring($right->getSpan()->getStart()->getOffset() - 1, $right->getSpan()->getStart()->getOffset()) === $operator->getOperator()
                        && Character::isWhitespace($this->scanner->getString()[$left->getSpan()->getEnd()->getOffset()])
                    ) {
                        $operatorText = $operator->getOperator();
                        $message = <<<WARNING
This operation is parsed as:

    $left $operatorText $right

but you may have intended it to mean:

    $left ($operatorText$right)

Add a space after $operatorText to clarify that it's meant to be a binary operation, or wrap
it in parentheses to make it a unary operation. This will be an error in future
versions of Sass.

More info and automated migrator: https://sass-lang.com/d/strict-unary
WARNING;

                        LoggerUtil::warnForDeprecation($this->logger, Deprecation::strictUnary, $message, $singleExpression->getSpan());
                    }
                }
            }
        };

        $resolveOperations = function () use (&$operators, $resolveOneOperation): void {
            if ($operators === null) {
                return;
            }

            while ($operators) {
                $resolveOneOperation();
            }
        };

        $addSingleExpression = function (Expression $expression) use (&$singleExpression, &$allowSlash, &$spaceExpressions, $resetState, $resolveOperations): void {
            if ($singleExpression !== null) {
                // If we discover we're parsing a list whose first element is a division
                // operation, and we're in parentheses, reparse outside of a paren
                // context. This ensures that `(1/2 1)` doesn't perform division on its
                // first element.
                if ($this->inParentheses) {
                    $this->inParentheses = false;

                    if ($allowSlash) {
                        $resetState();
                        return;
                    }
                }

                $spaceExpressions = $spaceExpressions ?? [];
                $resolveOperations();

                $spaceExpressions[] = $singleExpression;
                $allowSlash = true;
            }

            $singleExpression = $expression;
        };

        $addOperator = function (BinaryOperator $operator) use (&$allowSlash, &$operators, &$operands, &$singleExpression, $resolveOneOperation): void {
            if (
                $this->isPlainCss()
                && $operator !== BinaryOperator::SINGLE_EQUALS
                // These are allowed in calculations, so we have to check them at
                // evaluation time.
                && $operator !== BinaryOperator::PLUS
                && $operator !== BinaryOperator::MINUS
                && $operator !== BinaryOperator::TIMES
                && $operator !== BinaryOperator::DIVIDED_BY
            ) {
                $this->scanner->error("Operators aren't allowed in plain CSS.", $this->scanner->getPosition() - \strlen($operator->getOperator()), \strlen($operator->getOperator()));
            }

            $allowSlash = $allowSlash && $operator === BinaryOperator::DIVIDED_BY;

            $operators = $operators ?? [];
            $operands = $operands ?? [];

            $precedence = $operator->getPrecedence();

            while ($operators && $operators[\count($operators) - 1]->getPrecedence() >= $precedence) {
                $resolveOneOperation();
            }

            $operators[] = $operator;

            if ($singleExpression === null) {
                $this->scanner->error('Expected expression.', $this->scanner->getPosition() - \strlen($operator->getOperator()), \strlen($operator->getOperator()));
            }

            $operands[] = $singleExpression;

            $this->whitespace();
            $singleExpression = $this->singleExpression();
        };

        $resolveSpaceExpressions = function () use (&$spaceExpressions, &$singleExpression, $resolveOperations): void {
            $resolveOperations();

            if ($spaceExpressions !== null) {
                if ($singleExpression === null) {
                    $this->scanner->error('Expected expression.');
                }

                $spaceExpressions[] = $singleExpression;
                $singleExpression = new ListExpression(
                    $spaceExpressions,
                    ListSeparator::SPACE,
                    $spaceExpressions[0]->getSpan()->expand($spaceExpressions[\count($spaceExpressions) - 1]->getSpan())
                );
                $spaceExpressions = null;
            }
        };

        while (true) {
            $this->whitespace();

            if ($until !== null && $until()) {
                break;
            }

            $first = $this->scanner->peekChar();

            switch ($first) {
                case '(':
                    // Parenthesized numbers can't be slash-separated.
                    $addSingleExpression($this->parentheses());
                    break;

                case '[':
                    $addSingleExpression($this->expression(null, false, true));
                    break;

                case '$':
                    $addSingleExpression($this->variable());
                    break;

                case '&':
                    $addSingleExpression($this->selector());
                    break;

                case "'":
                case '"':
                    $addSingleExpression($this->interpolatedString());
                    break;

                case '#':
                    $addSingleExpression($this->hashExpression());
                    break;

                case '=':
                    $this->scanner->readChar();
                    if ($singleEquals && $this->scanner->peekChar() !== '=') {
                        $addOperator(BinaryOperator::SINGLE_EQUALS);
                    } else {
                        $this->scanner->expectChar('=');
                        $addOperator(BinaryOperator::EQUALS);
                    }
                    break;

                case '!':
                    $next = $this->scanner->peekChar(1);

                    if ($next === '=') {
                        $this->scanner->readChar();
                        $this->scanner->readChar();
                        $addOperator(BinaryOperator::NOT_EQUALS);
                    } elseif ($next === null || $next === 'i' || $next === 'I' || Character::isWhitespace($next)) {
                        $addSingleExpression($this->importantExpression());
                    } else {
                        break 2;
                    }
                    break;

                case '<':
                    $this->scanner->readChar();
                    $addOperator($this->scanner->scanChar('=') ? BinaryOperator::LESS_THAN_OR_EQUALS : BinaryOperator::LESS_THAN);
                    break;

                case '>':
                    $this->scanner->readChar();
                    $addOperator($this->scanner->scanChar('=') ? BinaryOperator::GREATER_THAN_OR_EQUALS : BinaryOperator::GREATER_THAN);
                    break;

                case '*':
                    $this->scanner->readChar();
                    $addOperator(BinaryOperator::TIMES);
                    break;

                case '+':
                    if ($singleExpression === null) {
                        $addSingleExpression($this->unaryOperation());
                    } else {
                        $this->scanner->readChar();
                        $addOperator(BinaryOperator::PLUS);
                    }
                    break;

                case '-':
                    $next = $this->scanner->peekChar(1);
                    // Make sure `1-2` parses as `1 - 2`, not `1 (-2)`.
                    if ((Character::isDigit($next) || $next === '.') && ($singleExpression === null || Character::isWhitespace($this->scanner->peekChar(-1)))) {
                        $addSingleExpression($this->number());
                    } elseif ($this->lookingAtInterpolatedIdentifier()) {
                        $addSingleExpression($this->identifierLike());
                    } elseif ($singleExpression === null) {
                        $addSingleExpression($this->unaryOperation());
                    } else {
                        $this->scanner->readChar();
                        $addOperator(BinaryOperator::MINUS);
                    }
                    break;

                case '/':
                    if ($singleExpression === null) {
                        $addSingleExpression($this->unaryOperation());
                    } else {
                        $this->scanner->readChar();
                        $addOperator(BinaryOperator::DIVIDED_BY);
                    }
                    break;

                case '%':
                    $this->scanner->readChar();
                    $addOperator(BinaryOperator::MODULO);
                    break;

                case '0':
                case '1':
                case '2':
                case '3':
                case '4':
                case '5':
                case '6':
                case '7':
                case '8':
                case '9':
                    $addSingleExpression($this->number());
                    break;

                case '.':
                    if ($this->scanner->peekChar(1) === '.') {
                        break 2;
                    }

                    $addSingleExpression($this->number());
                    break;

                case 'a':
                    if (!$this->isPlainCss() && $this->scanIdentifier('and')) {
                        $addOperator(BinaryOperator::AND);
                    } else {
                        $addSingleExpression($this->identifierLike());
                    }
                    break;

                case 'o':
                    if (!$this->isPlainCss() && $this->scanIdentifier('or')) {
                        $addOperator(BinaryOperator::OR);
                    } else {
                        $addSingleExpression($this->identifierLike());
                    }
                    break;

                case 'u':
                case 'U':
                    if ($this->scanner->peekChar(1) === '+') {
                        $addSingleExpression($this->unicodeRange());
                    } else {
                        $addSingleExpression($this->identifierLike());
                    }
                    break;

                case 'b':
                case 'c':
                case 'd':
                case 'e':
                case 'f':
                case 'g':
                case 'h':
                case 'i':
                case 'j':
                case 'k':
                case 'l':
                case 'm':
                case 'n':
                case 'p':
                case 'q':
                case 'r':
                case 's':
                case 't':
                case 'v':
                case 'w':
                case 'x':
                case 'y':
                case 'z':
                case 'A':
                case 'B':
                case 'C':
                case 'D':
                case 'E':
                case 'F':
                case 'G':
                case 'H':
                case 'I':
                case 'J':
                case 'K':
                case 'L':
                case 'M':
                case 'N':
                case 'O':
                case 'P':
                case 'Q':
                case 'R':
                case 'S':
                case 'T':
                case 'V':
                case 'W':
                case 'X':
                case 'Y':
                case 'Z':
                case '_':
                case '\\':
                    $addSingleExpression($this->identifierLike());
                    break;

                case ',':
                    // If we discover we're parsing a list whose first element is a
                    // division operation, and we're in parentheses, reparse outside of a
                    // paren context. This ensures that `(1/2, 1)` doesn't perform division
                    // on its first element.
                    if ($this->inParentheses) {
                        $this->inParentheses = false;

                        if ($allowSlash) {
                            $resetState();
                            break;
                        }
                    }

                    $commaExpressions = $commaExpressions ?? [];

                    if ($singleExpression === null) {
                        $this->scanner->error('Expected expression.');
                    }
                    $resolveSpaceExpressions();

                    $commaExpressions[] = $singleExpression;

                    $this->scanner->readChar();
                    $allowSlash = true;
                    $singleExpression = null;
                    break;

                default:
                    if ($first !== null && \ord($first) >= 0x80) {
                        $addSingleExpression($this->identifierLike());
                        break;
                    }

                    break 2;
            }
        }

        if ($bracketList) {
            $this->scanner->expectChar(']');
        }

        if ($commaExpressions !== null) {
            $resolveSpaceExpressions();
            $this->inParentheses = $wasInParentheses;

            if ($singleExpression !== null) {
                $commaExpressions[] = $singleExpression;
            }

            $this->inExpression = $wasInExpression;

            return new ListExpression($commaExpressions, ListSeparator::COMMA, $this->scanner->spanFrom($beforeBracket ?? $start), $bracketList);
        }

        if ($bracketList && $spaceExpressions !== null) {
            $resolveOperations();
            $this->inExpression = $wasInExpression;
            assert($singleExpression !== null);
            $spaceExpressions[] = $singleExpression;

            return new ListExpression($spaceExpressions, ListSeparator::SPACE, $this->scanner->spanFrom($beforeBracket), true);
        }

        $resolveSpaceExpressions();
        assert($singleExpression !== null);

        if ($bracketList) {
            assert($beforeBracket !== null);
            $singleExpression = new ListExpression([$singleExpression], ListSeparator::UNDECIDED, $this->scanner->spanFrom($beforeBracket), true);
        }
        $this->inExpression = $wasInExpression;

        return $singleExpression;
    }

    /**
     * Consumes an expression until it reaches a top-level comma.
     *
     * If $singleEquals is true, this will allow the Microsoft-style `=`
     * operator at the top level.
     *
     * @phpstan-impure
     */
    protected function expressionUntilComma(bool $singleEquals = false): Expression
    {
        return $this->expression(fn() => $this->scanner->peekChar() === ',', $singleEquals);
    }

    /**
     * Whether $expression is allowed as an operand of a `/` expression that
     * produces a potentially slash-separated number.
     */
    private static function isSlashOperand(Expression $expression): bool
    {
        return $expression instanceof NumberExpression || $expression instanceof FunctionExpression || ($expression instanceof BinaryOperationExpression && $expression->allowsSlash());
    }

    /**
     * Consumes an expression that doesn't contain any top-level whitespace.
     */
    private function singleExpression(): Expression
    {
        $first = $this->scanner->peekChar();

        switch ($first) {
            case '(':
                return $this->parentheses();
            case '/':
                return $this->unaryOperation();
            case '.':
                return $this->number();
            case '[':
                return $this->expression(null, false, true);
            case '$':
                return $this->variable();
            case '&':
                return $this->selector();

            case "'":
            case '"':
                return $this->interpolatedString();

            case '#':
                return $this->hashExpression();

            case '+':
                return $this->plusExpression();

            case '-':
                return $this->minusExpression();

            case '!':
                return $this->importantExpression();

            case 'u':
            case 'U':
                if ($this->scanner->peekChar(1) === '+') {
                    return $this->unicodeRange();
                }

                return $this->identifierLike();

            case '0':
            case '1':
            case '2':
            case '3':
            case '4':
            case '5':
            case '6':
            case '7':
            case '8':
            case '9':
                return $this->number();

            case 'a':
            case 'b':
            case 'c':
            case 'd':
            case 'e':
            case 'f':
            case 'g':
            case 'h':
            case 'i':
            case 'j':
            case 'k':
            case 'l':
            case 'm':
            case 'n':
            case 'o':
            case 'p':
            case 'q':
            case 'r':
            case 's':
            case 't':
            case 'v':
            case 'w':
            case 'x':
            case 'y':
            case 'z':
            case 'A':
            case 'B':
            case 'C':
            case 'D':
            case 'E':
            case 'F':
            case 'G':
            case 'H':
            case 'I':
            case 'J':
            case 'K':
            case 'L':
            case 'M':
            case 'N':
            case 'O':
            case 'P':
            case 'Q':
            case 'R':
            case 'S':
            case 'T':
            case 'V':
            case 'W':
            case 'X':
            case 'Y':
            case 'Z':
            case '_':
            case '\\':
                return $this->identifierLike();

            default:
                if ($first !== null && \ord($first) >= 0x80) {
                    return $this->identifierLike();
                }

                $this->scanner->error('Expected expression.');
        }
    }

    /**
     * Consumes a parenthesized expression.
     */
    protected function parentheses(): Expression
    {
        if ($this->isPlainCss()) {
            $this->scanner->error("Parentheses aren't allowed in plain CSS.");
        }

        $wasInParentheses = $this->inParentheses;
        $this->inParentheses = true;

        try {
            $start = $this->scanner->getPosition();
            $this->scanner->expectChar('(');
            $this->whitespace();

            if (!$this->lookingAtExpression()) {
                $this->scanner->expectChar(')');

                return new ListExpression([], ListSeparator::UNDECIDED, $this->scanner->spanFrom($start));
            }

            $first = $this->expressionUntilComma();

            if ($this->scanner->scanChar(':')) {
                $this->whitespace();

                return $this->map($first, $start);
            }

            if (!$this->scanner->scanChar(',')) {
                $this->scanner->expectChar(')');

                return new ParenthesizedExpression($first, $this->scanner->spanFrom($start));
            }

            $this->whitespace();

            $expressions = [$first];

            while (true) {
                if (!$this->lookingAtExpression()) {
                    break;
                }

                $expressions[] = $this->expressionUntilComma();

                if (!$this->scanner->scanChar(',')) {
                    break;
                }

                $this->whitespace();
            }

            $this->scanner->expectChar(')');

            return new ListExpression($expressions, ListSeparator::COMMA, $this->scanner->spanFrom($start));
        } finally {
            $this->inParentheses = $wasInParentheses;
        }
    }

    /**
     * Consumes a map expression.
     *
     * This expects to be called after the first colon in the map, with $first
     * as the expression before the colon and $start the point before the
     * opening parenthesis.
     */
    private function map(Expression $first, int $start): MapExpression
    {
        $pairs = [
            [$first, $this->expressionUntilComma()],
        ];

        while ($this->scanner->scanChar(',')) {
            $this->whitespace();
            if (!$this->lookingAtExpression()) {
                break;
            }

            $key = $this->expressionUntilComma();
            $this->scanner->expectChar(':');
            $this->whitespace();
            $value = $this->expressionUntilComma();

            $pairs[] = [$key, $value];
        }

        $this->scanner->expectChar(')');

        return new MapExpression($pairs, $this->scanner->spanFrom($start));
    }

    /**
     * Consumes an expression that starts with a `#`.
     */
    private function hashExpression(): Expression
    {
        assert($this->scanner->peekChar() === '#');
        if ($this->scanner->peekChar(1) === '{') {
            return $this->identifierLike();
        }

        $start = $this->scanner->getPosition();
        $this->scanner->expectChar('#');

        $first = $this->scanner->peekChar();
        if ($first !== null && Character::isDigit($first)) {
            return new ColorExpression($this->hexColorContents($start), $this->scanner->spanFrom($start));
        }

        $afterHash = $this->scanner->getPosition();
        $identifier = $this->interpolatedIdentifier();
        if ($this->isHexColor($identifier)) {
            $this->scanner->setPosition($afterHash);

            return new ColorExpression($this->hexColorContents($start), $this->scanner->spanFrom($start));
        }

        $buffer = new InterpolationBuffer();
        $buffer->write('#');
        $buffer->addInterpolation($identifier);

        return new StringExpression($buffer->buildInterpolation($this->scanner->spanFrom($start)));
    }

    /**
     * Consumes the contents of a hex color, after the `#`.
     */
    private function hexColorContents(int $start): SassColor
    {
        $digit1 = $this->hexDigit();
        $digit2 = $this->hexDigit();
        $digit3 = $this->hexDigit();

        $alpha = null;

        if (!Character::isHex($this->scanner->peekChar())) {
            // #abc
            $red = ($digit1 << 4) + $digit1;
            $green = ($digit2 << 4) + $digit2;
            $blue = ($digit3 << 4) + $digit3;
        } else {
            $digit4 = $this->hexDigit();

            if (!Character::isHex($this->scanner->peekChar())) {
                #abcd
                $red = ($digit1 << 4) + $digit1;
                $green = ($digit2 << 4) + $digit2;
                $blue = ($digit3 << 4) + $digit3;
                $alpha = (($digit4 << 4) + $digit4) / 0xff;
            } else {
                $red = ($digit1 << 4) + $digit2;
                $green = ($digit3 << 4) + $digit4;
                $blue = ($this->hexDigit() << 4) + $this->hexDigit();

                if (Character::isHex($this->scanner->peekChar())) {
                    $alpha = (($this->hexDigit() << 4) + $this->hexDigit()) / 0xff;
                }
            }
        }

        // Don't emit four- or eight-digit hex colors as hex, since that's not
        // yet well-supported in browsers.
        return SassColor::rgbInternal($red, $green, $blue, $alpha ?? 1.0, $alpha === null ? new SpanColorFormat($this->scanner->spanFrom($start)) : null);
    }

    private function isHexColor(Interpolation $interpolation): bool
    {
        $plain = $interpolation->getAsPlain();

        if ($plain === null) {
            return false;
        }

        $length = \strlen($plain);

        if ($length !== 3 && $length !== 4 && $length !== 6 && $length !== 8) {
            return false;
        }

        for ($i = 0; $i < $length; $i++) {
            if (!Character::isHex($plain[$i])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Consumes a single hexadecimal digit.
     *
     * @phpstan-impure
     */
    private function hexDigit(): int
    {
        $char = $this->scanner->peekChar();

        if ($char === null || !Character::isHex($char)) {
            $this->scanner->error('Expected hex digit.');
        }

        return (int) hexdec($this->scanner->readChar());
    }

    /**
     * Consumes an expression that starts with a `+`.
     */
    private function plusExpression(): Expression
    {
        assert($this->scanner->peekChar() === '+');
        $next = $this->scanner->peekChar(1);

        if (Character::isDigit($next) || $next === '.') {
            return $this->number();
        }

        return $this->unaryOperation();
    }

    /**
     * Consumes an expression that starts with a `-`.
     */
    private function minusExpression(): Expression
    {
        assert($this->scanner->peekChar() === '-');
        $next = $this->scanner->peekChar(1);

        if (Character::isDigit($next) || $next === '.') {
            return $this->number();
        }

        if ($this->lookingAtInterpolatedIdentifier()) {
            return $this->identifierLike();
        }

        return $this->unaryOperation();
    }

    /**
     * Consumes an `!important` expression.
     */
    private function importantExpression(): Expression
    {
        assert($this->scanner->peekChar() === '!');

        $start = $this->scanner->getPosition();
        $this->scanner->readChar();
        $this->whitespace();
        $this->expectIdentifier('important');

        return StringExpression::plain('!important', $this->scanner->spanFrom($start));
    }

    /**
     * Consumes a unary operation expression.
     */
    private function unaryOperation(): UnaryOperationExpression
    {
        $start = $this->scanner->getPosition();
        $operator = $this->unaryOperatorFor($this->scanner->readChar());

        if ($operator === null) {
            $this->scanner->error('Expected unary operator.', $this->scanner->getPosition() - 1);
        }

        if ($this->isPlainCss() && $operator !== UnaryOperator::DIVIDE) {
            $this->scanner->error("Operators aren't allowed in plain CSS.", $this->scanner->getPosition() - 1, 1);
        }

        $this->whitespace();
        $operand = $this->singleExpression();

        return new UnaryOperationExpression($operator, $operand, $this->scanner->spanFrom($start));
    }

    /**
     * Returns the unary operator corresponding to $character, or `null` if
     * the character is not a unary operator.
     */
    private function unaryOperatorFor(string $character): ?UnaryOperator
    {
        return match ($character) {
            '+' => UnaryOperator::PLUS,
            '-' => UnaryOperator::MINUS,
            '/' => UnaryOperator::DIVIDE,
            default => null,
        };
    }

    /**
     * Consumes a number expression.
     */
    private function number(): NumberExpression
    {
        $start = $this->scanner->getPosition();
        $first = $this->scanner->peekChar();

        if ($first === '+' || $first === '-') {
            $this->scanner->readChar();
        }

        if ($this->scanner->peekChar() !== '.') {
            $this->consumeNaturalNumber();
        }

        // Don't complain about a dot after a number unless the number starts with a
        // dot. We don't allow a plain ".", but we need to allow "1." so that
        // "1..." will work as a rest argument.
        $this->tryDecimal($this->scanner->getPosition() !== $start && $first !== '+' && $first !== '-');
        $this->tryExponent();

        // Use PHP's built-in double parsing so that we don't accumulate
        // floating-point errors for numbers with lots of digits.
        $number = floatval($this->scanner->substring($start));

        $unit = null;
        if ($this->scanner->scanChar('%')) {
            $unit = '%';
        } elseif ($this->lookingAtIdentifier() && ($this->scanner->peekChar() !== '-' || $this->scanner->peekChar(1) !== '-')) {
            $unit = $this->identifier(false, true);
        }

        return new NumberExpression($number, $this->scanner->spanFrom($start), $unit);
    }

    /**
     * Consumes a natural number (that is, a non-negative integer).
     *
     * Doesn't support scientific notation.
     */
    private function consumeNaturalNumber(): void
    {
        if (!Character::isDigit($this->scanner->readChar())) {
            $this->scanner->error('Expected digit.', $this->scanner->getPosition() - 1);
        }

        while (Character::isDigit($this->scanner->peekChar())) {
            $this->scanner->readChar();
        }
    }

    /**
     * Consumes the decimal component of a number if it exists.
     *
     * If $allowTrailingDot is `false`, this will throw an error if there's a
     * dot without any numbers following it. Otherwise, it will ignore the dot
     * without consuming it.
     */
    private function tryDecimal(bool $allowTrailingDot = false): void
    {
        if ($this->scanner->peekChar() !== '.') {
            return;
        }

        if (!Character::isDigit($this->scanner->peekChar(1))) {
            if ($allowTrailingDot) {
                return;
            }

            $this->scanner->error('Expected digit.', $this->scanner->getPosition() + 1);
        }

        $this->scanner->readChar();
        while (Character::isDigit($this->scanner->peekChar())) {
            $this->scanner->readChar();
        }
    }

    /**
     * Consumes the exponent component of a number if it exists.
     */
    private function tryExponent(): void
    {
        $first = $this->scanner->peekChar();

        if ($first !== 'e' && $first !== 'E') {
            return;
        }

        $next = $this->scanner->peekChar(1);

        if (!Character::isDigit($next) && $next !== '-' && $next !== '+') {
            return;
        }

        $this->scanner->readChar();
        if ($next === '+' || $next === '-') {
            $this->scanner->readChar();
        }

        if (!Character::isDigit($this->scanner->peekChar())) {
            $this->scanner->error('Expected digit.');
        }

        while (Character::isDigit($this->scanner->peekChar())) {
            $this->scanner->readChar();
        }
    }

    /**
     * Consumes a unicode range expression.
     */
    private function unicodeRange(): StringExpression
    {
        $start = $this->scanner->getPosition();
        $this->expectIdentChar('u');
        $this->scanner->expectChar('+');

        $firstRangeLength = 0;
        while ($this->scanCharIf(Character::isHex(...))) {
            $firstRangeLength++;
        }

        $hasQuestionMark = false;

        while ($this->scanner->scanChar('?')) {
            $hasQuestionMark = true;
            $firstRangeLength++;
        }

        if ($firstRangeLength === 0) {
            $this->scanner->error('Expected hex digit or "?".');
        } elseif ($firstRangeLength > 6) {
            $this->error('Expected at most 6 digits.', $this->scanner->spanFrom($start));
        } elseif ($hasQuestionMark) {
            return StringExpression::plain($this->scanner->substring($start), $this->scanner->spanFrom($start));
        }

        if ($this->scanner->scanChar('-')) {
            $secondRangeStart = $this->scanner->getPosition();
            $secondRangeLength = 0;
            while ($this->scanCharIf(Character::isHex(...))) {
                $secondRangeLength++;
            }

            if ($secondRangeLength === 0) {
                $this->scanner->error('Expected hex digit.');
            } elseif ($secondRangeLength > 6) {
                $this->error('Expected at most 6 digits.', $this->scanner->spanFrom($secondRangeStart));
            }
        }

        if ($this->lookingAtInterpolatedIdentifierBody()) {
            $this->scanner->error('Expected end of identifier.');
        }

        return StringExpression::plain($this->scanner->substring($start), $this->scanner->spanFrom($start));
    }

    /**
     * Consumes a variable expression.
     */
    private function variable(): VariableExpression
    {
        $start = $this->scanner->getPosition();
        $name = $this->variableName();

        if ($this->isPlainCss()) {
            $this->error('Sass variables aren\'t allowed in plain CSS.', $this->scanner->spanFrom($start));
        }

        return new VariableExpression($name, $this->scanner->spanFrom($start));
    }

    /**
     * Consumes a selector expression.
     */
    private function selector(): SelectorExpression
    {
        if ($this->isPlainCss()) {
            $this->scanner->error("The parent selector isn't allowed in plain CSS.", null, 1);
        }

        $start = $this->scanner->getPosition();
        $this->scanner->expectChar('&');

        if ($this->scanner->scanChar('&')) {
            $this->warn('In Sass, "&&" means two copies of the parent selector. You probably want to use "and" instead.', $this->scanner->spanFrom($start));
            $this->scanner->setPosition($this->scanner->getPosition() - 1);
        }

        return new SelectorExpression($this->scanner->spanFrom($start));
    }

    /**
     * Consumes a quoted string expression.
     */
    protected function interpolatedString(): StringExpression
    {
        $start = $this->scanner->getPosition();
        $quote = $this->scanner->readChar();

        if ($quote !== "'" && $quote !== '"') {
            $this->scanner->error('Expected string.', $start);
        }

        $buffer = new InterpolationBuffer();

        while (true) {
            $next = $this->scanner->peekChar();

            if ($next === $quote) {
                $this->scanner->readChar();
                break;
            }

            if ($next === null || Character::isNewline($next)) {
                $this->scanner->error("Expected $quote.");
            }

            if ($next === '\\') {
                $second = $this->scanner->peekChar(1);

                if (Character::isNewline($second)) {
                    $this->scanner->readChar();
                    $this->scanner->readChar();

                    if ($second === "\r") {
                        $this->scanner->scanChar("\n");
                    }
                } else {
                    $buffer->write($this->escapeCharacter());
                }
            } elseif ($next === '#') {
                if ($this->scanner->peekChar(1) === '{') {
                    $buffer->add($this->singleInterpolation());
                } else {
                    $buffer->write($this->scanner->readChar());
                }
            } else {
                $buffer->write($this->scanner->readUtf8Char());
            }
        }

        return new StringExpression($buffer->buildInterpolation($this->scanner->spanFrom($start)), true);
    }

    /**
     * Consumes an expression that starts like an identifier.
     */
    protected function identifierLike(): Expression
    {
        $start = $this->scanner->getPosition();
        $identifier = $this->interpolatedIdentifier();
        $plain = $identifier->getAsPlain();

        if ($plain !== null) {
            if ($plain === 'if' && $this->scanner->peekChar() === '(') {
                $invocation = $this->argumentInvocation();

                return new IfExpression($invocation, $identifier->getSpan()->expand($invocation->getSpan()));
            }

            if ($plain === 'not') {
                $this->whitespace();

                $expression = $this->singleExpression();

                return new UnaryOperationExpression(UnaryOperator::NOT, $expression, $identifier->getSpan()->expand($expression->getSpan()));
            }

            $lower = strtolower($plain);

            if ($this->scanner->peekChar() !== '(') {
                switch ($plain) {
                    case 'false':
                        return new BooleanExpression(false, $identifier->getSpan());
                    case 'null':
                        return new NullExpression($identifier->getSpan());
                    case 'true':
                        return new BooleanExpression(true, $identifier->getSpan());
                }

                $color = Colors::colorNameToColor($lower);

                if ($color !== null) {
                    return new ColorExpression(
                        SassColor::rgbInternal($color->getRed(), $color->getGreen(), $color->getBlue(), $color->getAlpha(), new SpanColorFormat($identifier->getSpan())),
                        $identifier->getSpan()
                    );
                }
            }

            $specialFunction = $this->trySpecialFunction($lower, $start);

            if ($specialFunction !== null) {
                return $specialFunction;
            }
        }

        switch ($this->scanner->peekChar()) {
            case '.':
                if ($this->scanner->peekChar(1) === '.') {
                    return new StringExpression($identifier);
                }

                $this->scanner->readChar();

                if ($plain !== null) {
                    return $this->namespacedExpression($plain, $start);
                }

                $this->error("Interpolation isn't allowed in namespaces.", $identifier->getSpan());

            case '(':
                if ($plain === null) {
                    return new InterpolatedFunctionExpression($identifier, $this->argumentInvocation(), $this->scanner->spanFrom($start));
                }

                return new FunctionExpression($plain, $this->argumentInvocation(false, $lower === 'var'), $this->scanner->spanFrom($start));

            default:
                return new StringExpression($identifier);
        }
    }

    /**
     * Consumes an expression after a namespace.
     *
     * This assumes the scanner is positioned immediately after the `.`. The
     * $start should refer to the state at the beginning of the namespace.
     */
    protected function namespacedExpression(string $namespace, int $start): Expression
    {
        if ($this->scanner->peekChar() === '$') {
            $name = $this->variableName();
            $this->assertPublic($name, fn() => $this->scanner->spanFrom($start));

            // TODO remove this when implementing modules
            $this->error('Sass modules are not implemented yet.', $this->scanner->spanFrom($start));
            // return new VariableExpression($name, $this->scanner->spanFrom($start), $plain);
        }

        // TODO remove this when implementing modules
        $this->publicIdentifier();
        $this->error('Sass modules are not implemented yet.', $this->scanner->spanFrom($start));
        // return new FunctionExpression($this->publicIdentifier(), $this->argumentInvocation(), $this->scanner->spanFrom($start), $plain);
    }

    /**
     * If $name is the name of a function with special syntax, consumes it.
     *
     * Otherwise, returns `null`. $start is the location before the beginning of $name.
     */
    protected function trySpecialFunction(string $name, int $start): ?Expression
    {
        $normalized = Util::unvendor($name);

        switch ($normalized) {
            case 'calc':
                if ($normalized === $name) {
                    return null;
                }

                // fall through
            case 'element':
            case 'expression':
                if (!$this->scanner->scanChar('(')) {
                    return null;
                }

                $buffer = new InterpolationBuffer();
                $buffer->write($name);
                $buffer->write('(');
                break;

            case 'progid':
                if (!$this->scanner->scanChar(':')) {
                    return null;
                }

                $buffer = new InterpolationBuffer();
                $buffer->write($name);
                $buffer->write(':');

                $next = $this->scanner->peekChar();

                while ($next !== null && (Character::isAlphabetic($next) || $next === '.')) {
                    $buffer->write($this->scanner->readChar());
                    $next = $this->scanner->peekChar();
                }

                $this->scanner->expectChar('(');
                $buffer->write('(');
                break;

            case 'url':
                $contents = $this->tryUrlContents($start);

                if ($contents === null) {
                    return null;
                }

                return new StringExpression($contents);

            default:
                return null;
        }

        $buffer->addInterpolation($this->interpolatedDeclarationValue(true));
        $this->scanner->expectChar(')');
        $buffer->write(')');

        return new StringExpression($buffer->buildInterpolation($this->scanner->spanFrom($start)));
    }

    private function tryUrlContents(int $start, ?string $name = null): ?Interpolation
    {
        $beginningOfContents = $this->scanner->getPosition();

        if (!$this->scanner->scanChar('(')) {
            return null;
        }
        $this->whitespaceWithoutComments();

        $buffer = new InterpolationBuffer();
        $buffer->write($name ?? 'url');
        $buffer->write('(');

        while (true) {
            $next = $this->scanner->peekChar();

            if ($next === null) {
                break;
            }

            if ($next === '\\') {
                $buffer->write($this->escape());
            } elseif ($next === '!' || $next === '%' || $next === '&' || (\ord($next) >= \ord('*') && \ord($next) <= \ord('~')) || \ord($next) >= 0x80) {
                $buffer->write($this->scanner->readUtf8Char());
            } elseif ($next === '#') {
                if ($this->scanner->peekChar(1) === '{') {
                    $buffer->add($this->singleInterpolation());
                } else {
                    $buffer->write($this->scanner->readChar());
                }
            } elseif (Character::isWhitespace($next)) {
                $this->whitespaceWithoutComments();

                if ($this->scanner->peekChar() !== ')') {
                    break;
                }
            } elseif ($next === ')') {
                $buffer->write($this->scanner->readChar());

                return $buffer->buildInterpolation($this->scanner->spanFrom($start));
            } else {
                break;
            }
        }

        $this->scanner->setPosition($beginningOfContents);

        return null;
    }

    /**
     * Consumes a `url` token that's allowed to contain SassScript.
     */
    protected function dynamicUrl(): Expression
    {
        $start = $this->scanner->getPosition();
        $this->expectIdentifier('url');

        $contents = $this->tryUrlContents($start);

        if ($contents !== null) {
            return new StringExpression($contents);
        }

        return new InterpolatedFunctionExpression(new Interpolation(['url'], $this->scanner->spanFrom($start)), $this->argumentInvocation(), $this->scanner->spanFrom($start));
    }

    /**
     * Consumes tokens up to "{", "}", ";", or "!".
     *
     * This respects string and comment boundaries and supports interpolation.
     * Once this interpolation is evaluated, it's expected to be re-parsed.
     *
     * If $omitComments is true, comments will still be consumed, but they will
     * not be included in the returned interpolation.
     *
     * Differences from {@see interpolatedDeclarationValue} include:
     *
     * - This always stops at curly braces.
     * - This does not interpret backslashes, since the text is expected to be
     *   re-parsed.
     * - This does not compress adjacent whitespace characters.
     */
    protected function almostAnyValue(bool $omitComments = false): Interpolation
    {
        $start = $this->scanner->getPosition();
        $buffer = new InterpolationBuffer();

        while (true) {
            $next = $this->scanner->peekChar();

            switch ($next) {
                case '\\':
                    // Write a literal backslash because this text will be re-parsed.
                    $buffer->write($this->scanner->readChar());
                    $buffer->write($this->scanner->readUtf8Char());
                    break;

                case '"':
                case "'":
                    $buffer->addInterpolation($this->interpolatedString()->asInterpolation());
                    break;

                case '/':
                    switch ($this->scanner->peekChar(1)) {
                        case '*':
                            if (!$omitComments) {
                                $buffer->write($this->rawText($this->loudComment(...)));
                            } else {
                                $this->loudComment();
                            }
                            break;

                        case '/':
                            if (!$omitComments) {
                                $buffer->write($this->rawText($this->silentComment(...)));
                            } else {
                                $this->silentComment();
                            }
                            break;

                        default:
                            $buffer->write($this->scanner->readChar());
                    }
                    break;

                case '#':
                    if ($this->scanner->peekChar(1) === '{') {
                        // Add a full interpolated identifier to handle cases like
                        // "#{...}--1", since "--1" isn't a valid identifier on its own.
                        $buffer->addInterpolation($this->interpolatedIdentifier());
                    } else {
                        $buffer->write($this->scanner->readChar());
                    }
                    break;

                case "\r":
                case "\n":
                case "\f":
                    if ($this->isIndented()) {
                        break 2;
                    }
                    $buffer->write($this->scanner->readChar());
                    break;

                case '!':
                case ';':
                case '{':
                case '}':
                    break 2;

                case 'u':
                case 'U':
                    $beforeUrl = $this->scanner->getPosition();
                    $identifier = $this->identifier();

                    if (
                        $identifier !== 'url'
                        // This isn't actually a standard CSS feature, but it was
                        // supported by the old `@document` rule, so we continue to support
                        // it for backwards-compatibility.
                        && $identifier !== 'url-prefix'
                    ) {
                        $buffer->write($identifier);
                        continue 2;
                    }

                    $contents = $this->tryUrlContents($beforeUrl, $identifier);

                    if ($contents === null) {
                        $this->scanner->setPosition($beforeUrl);
                        $buffer->write($this->scanner->readChar());
                    } else {
                        $buffer->addInterpolation($contents);
                    }
                    break;

                default:
                    if ($next === null) {
                        break 2;
                    }

                    if ($this->lookingAtIdentifier()) {
                        $buffer->write($this->identifier());
                    } else {
                        $buffer->write($this->scanner->readUtf8Char());
                    }
                    break;
            }
        }

        return $buffer->buildInterpolation($this->scanner->spanFrom($start));
    }

    /**
     * Consumes tokens until it reaches a top-level `";"`, `")"`, `"]"`,
     * or `"}"` and returns their contents as a string.
     *
     * If $allowEmpty is `false` (the default), this requires at least one token.
     *
     * If $allowSemicolon is `true`, this doesn't stop at semicolons and instead
     * includes them in the interpolated output.
     *
     * If $allowColon is `false`, this stops at top-level colons.
     *
     * If $allowOpenBrace is `false`, this stops at opening curly braces.
     *
     * If $silentComments is `true`, this will parse silent comments as
     * comments. Otherwise, it will preserve two adjacent slashes and emit them
     * to CSS.
     *
     * Unlike {@see declarationValue}, this allows interpolation.
     */
    private function interpolatedDeclarationValue(bool $allowEmpty = false, bool $allowSemicolon = false, bool $allowColon = true, bool $allowOpenBrace = true, bool $silentComments = true): Interpolation
    {
        $start = $this->scanner->getPosition();
        $buffer = new InterpolationBuffer();
        $brackets = [];
        $wroteNewline = false;

        while (true) {
            $next = $this->scanner->peekChar();

            if ($next === null) {
                break;
            }

            switch ($next) {
                case '\\':
                    $buffer->write($this->escape(true));
                    $wroteNewline = false;
                    break;

                case '"':
                case "'":
                    $buffer->addInterpolation($this->interpolatedString()->asInterpolation());
                    $wroteNewline = false;
                    break;

                case '/':
                    $peekedChar = $this->scanner->peekChar(1);

                    if ($peekedChar === '*') {
                        $buffer->write($this->rawText($this->loudComment(...)));
                    } elseif ($peekedChar === '/' && $silentComments) {
                        $this->silentComment();
                    } else {
                        $buffer->write($this->scanner->readChar());
                    }
                    $wroteNewline = false;
                    break;

                case '#':
                    if ($this->scanner->peekChar(1) === '{') {
                        // Add a full interpolated identifier to handle cases like
                        // "#{...}--1", since "--1" isn't a valid identifier on its own.
                        $buffer->addInterpolation($this->interpolatedIdentifier());
                    } else {
                        $buffer->write($this->scanner->readChar());
                    }
                    $wroteNewline = false;
                    break;

                case ' ':
                case "\t":
                    $second = $this->scanner->peekChar(1);
                    if ($wroteNewline || $second === null || !Character::isWhitespace($second)) {
                        $buffer->write($this->scanner->readChar());
                    } else {
                        $this->scanner->readChar();
                    }
                    break;

                case "\n":
                case "\r":
                case "\f":
                    if ($this->isIndented()) {
                        break 2;
                    }
                    $prev = $this->scanner->peekChar(-1);
                    if ($prev === null || !Character::isNewline($prev)) {
                        $buffer->write("\n");
                    }
                    $this->scanner->readChar();
                    $wroteNewline = true;
                    break;

                case '{':
                    if (!$allowOpenBrace) {
                        break 2;
                    }

                    // Fallthrough
                case '(':
                case '[':
                    $bracket = $this->scanner->readChar();
                    $buffer->write($bracket);
                    $brackets[] = Character::opposite($bracket);
                    $wroteNewline = false;
                    break;

                case ')':
                case '}':
                case ']':
                    if (empty($brackets)) {
                        break 2;
                    }

                    $bracket = array_pop($brackets);
                    $this->scanner->expectChar($bracket);
                    $buffer->write($bracket);
                    $wroteNewline = false;
                    break;

                case ';':
                    if (!$allowSemicolon && empty($brackets)) {
                        break 2;
                    }

                    $buffer->write($this->scanner->readChar());
                    $wroteNewline = false;
                    break;

                case ':':
                    if (!$allowColon && empty($brackets)) {
                        break 2;
                    }

                    $buffer->write($this->scanner->readChar());
                    $wroteNewline = false;
                    break;

                case 'u':
                case 'U':
                    $beforeUrl = $this->scanner->getPosition();
                    $identifier = $this->identifier();

                    if (
                        $identifier !== 'url'
                        // This isn't actually a standard CSS feature, but it was
                        // supported by the old `@document` rule, so we continue to support
                        // it for backwards-compatibility.
                        && $identifier !== 'url-prefix'
                    ) {
                        $buffer->write($identifier);
                        $wroteNewline = false;
                        continue 2;
                    }

                    $contents = $this->tryUrlContents($beforeUrl, $identifier);

                    if ($contents === null) {
                        $this->scanner->setPosition($beforeUrl);
                        $buffer->write($this->scanner->readChar());
                    } else {
                        $buffer->addInterpolation($contents);
                    }

                    $wroteNewline = false;
                    break;

                default:
                    if ($this->lookingAtIdentifier()) {
                        $buffer->write($this->identifier());
                    } else {
                        $buffer->write($this->scanner->readUtf8Char());
                    }
                    $wroteNewline = false;
                    break;
            }
        }

        if (!empty($brackets)) {
            $this->scanner->expectChar(array_pop($brackets));
        }

        if (!$allowEmpty && $buffer->isEmpty()) {
            $this->scanner->error('Expected token.');
        }

        return $buffer->buildInterpolation($this->scanner->spanFrom($start));
    }

    /**
     * Consumes an identifier that may contain interpolation.
     */
    protected function interpolatedIdentifier(): Interpolation
    {
        $start = $this->scanner->getPosition();
        $buffer = new InterpolationBuffer();

        if ($this->scanner->scanChar('-')) {
            $buffer->write('-');

            if ($this->scanner->scanChar('-')) {
                $buffer->write('-');
                $this->interpolatedIdentifierBody($buffer);

                return $buffer->buildInterpolation($this->scanner->spanFrom($start));
            }
        }

        $first = $this->scanner->peekChar();

        if ($first === null) {
            $this->scanner->error('Expected identifier.');
        }

        if (Character::isNameStart($first)) {
            $buffer->write($this->scanner->readUtf8Char());
        } elseif ($first === '\\') {
            $buffer->write($this->escape(true));
        } elseif ($first === '#' && $this->scanner->peekChar(1) === '{') {
            $buffer->add($this->singleInterpolation());
        } else {
            $this->scanner->error('Expected identifier.');
        }

        $this->interpolatedIdentifierBody($buffer);

        return $buffer->buildInterpolation($this->scanner->spanFrom($start));
    }

    /**
     * Consumes a chunk of a possibly-interpolated CSS identifier after the name
     * start, and adds the contents to the $buffer buffer.
     */
    private function interpolatedIdentifierBody(InterpolationBuffer $buffer): void
    {
        while (true) {
            $next = $this->scanner->peekChar();

            if ($next === null) {
                break;
            }

            if ($next === '_' || $next === '-' || Character::isAlphanumeric($next) || \ord($next) >= 0x80) {
                $buffer->write($this->scanner->readUtf8Char());
            } elseif ($next === '\\') {
                $buffer->write($this->escape());
            } elseif ($next === '#' && $this->scanner->peekChar(1) === '{') {
                $buffer->add($this->singleInterpolation());
            } else {
                break;
            }
        }
    }

    /**
     * Consumes interpolation.
     */
    protected function singleInterpolation(): Expression
    {
        $start = $this->scanner->getPosition();

        $this->scanner->expect('#{');

        $this->whitespace();

        $contents = $this->expression();

        $this->scanner->expectChar('}');

        if ($this->isPlainCss()) {
            $this->error('Interpolation isn\'t allowed in plain CSS.', $this->scanner->spanFrom($start));
        }

        return $contents;
    }

    /**
     * Consumes a list of media queries.
     */
    private function mediaQueryList(): Interpolation
    {
        $start = $this->scanner->getPosition();
        $buffer = new InterpolationBuffer();

        while (true) {
            $this->whitespace();
            $this->mediaQuery($buffer);
            $this->whitespace();

            if (!$this->scanner->scanChar(',')) {
                break;
            }

            $buffer->write(', ');
        }

        return $buffer->buildInterpolation($this->scanner->spanFrom($start));
    }

    /**
     * Consumes a single media query.
     */
    private function mediaQuery(InterpolationBuffer $buffer): void
    {
        if ($this->scanner->peekChar() === '(') {
            $this->mediaInParens($buffer);
            $this->whitespace();

            if ($this->scanIdentifier('and')) {
                $buffer->write(' and ');
                $this->expectWhitespace();
                $this->mediaLogicSequence($buffer, 'and');
            } elseif ($this->scanIdentifier('or')) {
                $buffer->write(' or ');
                $this->expectWhitespace();
                $this->mediaLogicSequence($buffer, 'or');
            }

            return;
        }

        $identifier1 = $this->interpolatedIdentifier();

        if (StringUtil::equalsIgnoreCase($identifier1->getAsPlain(), 'not')) {
            // For example, "@media not (...) {"
            $this->expectWhitespace();

            if (!$this->lookingAtInterpolatedIdentifier()) {
                $buffer->write('not ');
                $this->mediaOrInterp($buffer);

                return;
            }
        }

        $this->whitespace();
        $buffer->addInterpolation($identifier1);

        if (!$this->lookingAtInterpolatedIdentifier()) {
            // For example, "@media screen {".
            return;
        }

        $buffer->write(' ');

        $identifier2 = $this->interpolatedIdentifier();

        if (StringUtil::equalsIgnoreCase($identifier2->getAsPlain(), 'and')) {
            $this->expectWhitespace();
            // For example, "@media screen and ..."
            $buffer->write(' and ');
        } else {
            $this->whitespace();
            $buffer->addInterpolation($identifier2);

            if ($this->scanIdentifier('and')) {
                // For example, "@media only screen and ..."
                $this->expectWhitespace();
                $buffer->write(' and ');
            } else {
                // For example, "@media only screen {"
                return;
            }
        }

        // We've consumed either `IDENTIFIER "and"` or
        // `IDENTIFIER IDENTIFIER "and"`.

        if ($this->scanIdentifier('not')) {
            // For example, "@media screen and not (...) {"
            $this->expectWhitespace();
            $buffer->write('not ');
            $this->mediaOrInterp($buffer);
            return;
        }

        $this->mediaLogicSequence($buffer, 'and');
    }

    /**
     * Consumes one or more `MediaOrInterp` expressions separated by $operator
     * and writes them to $buffer.
     */
    private function mediaLogicSequence(InterpolationBuffer $buffer, string $operator): void
    {
        while (true) {
            $this->mediaOrInterp($buffer);
            $this->whitespace();

            if (!$this->scanIdentifier($operator)) {
                return;
            }
            $this->expectWhitespace();

            $buffer->write(' ');
            $buffer->write($operator);
            $buffer->write(' ');
        }
    }

    /**
     * Consumes a `MediaOrInterp` expression and writes it to $buffer.
     */
    private function mediaOrInterp(InterpolationBuffer $buffer): void
    {
        if ($this->scanner->peekChar() === '#') {
            $interpolation = $this->singleInterpolation();

            $buffer->addInterpolation(new Interpolation([$interpolation], $interpolation->getSpan()));
        } else {
            $this->mediaInParens($buffer);
        }
    }

    /**
     * Consumes a `MediaInParens` expression and writes it to $buffer.
     */
    private function mediaInParens(InterpolationBuffer $buffer): void
    {
        $this->scanner->expectChar('(', 'media condition in parentheses');
        $buffer->write('(');
        $this->whitespace();

        if ($this->scanner->peekChar() === '(') {
            $this->mediaInParens($buffer);
            $this->whitespace();

            if ($this->scanIdentifier('and')) {
                $buffer->write(' and ');
                $this->expectWhitespace();
                $this->mediaLogicSequence($buffer, 'and');
            } elseif ($this->scanIdentifier('or')) {
                $buffer->write(' or ');
                $this->expectWhitespace();
                $this->mediaLogicSequence($buffer, 'or');
            }
        } elseif ($this->scanIdentifier('not')) {
            $buffer->write('not ');
            $this->expectWhitespace();
            $this->mediaOrInterp($buffer);
        } else {
            $buffer->add($this->expressionUntilComparison());

            if ($this->scanner->scanChar(':')) {
                $this->whitespace();
                $buffer->write(': ');
                $buffer->add($this->expression());
            } else {
                $next = $this->scanner->peekChar();

                if ($next === '<' || $next === '>' || $next === '=') {
                    $buffer->write(' ');
                    $buffer->write($this->scanner->readChar());
                    if (($next === '<' || $next === '>') && $this->scanner->scanChar('=')) {
                        $buffer->write('=');
                    }
                    $buffer->write(' ');

                    $this->whitespace();
                    $buffer->add($this->expressionUntilComparison());

                    if (($next === '<' || $next === '>') && $this->scanner->scanChar($next)) {
                        $buffer->write(' ');
                        $buffer->write($next);
                        if ($this->scanner->scanChar('=')) {
                            $buffer->write('=');
                        }
                        $buffer->write(' ');

                        $this->whitespace();
                        $buffer->add($this->expressionUntilComparison());
                    }
                }
            }
        }

        $this->scanner->expectChar(')');
        $this->whitespace();
        $buffer->write(')');
    }

    /**
     * Consumes an expression until it reaches a top-level `<`, `>`, or a `=`
     * that's not `==`.
     */
    private function expressionUntilComparison(): Expression
    {
        return $this->expression(function () {
            $next = $this->scanner->peekChar();

            if ($next === '=') {
                return $this->scanner->peekChar(1) !== '=';
            }

            return $next === '<' || $next === '>';
        });
    }

    /**
     * Consumes a `@supports` condition.
     */
    private function supportsCondition(): SupportsCondition
    {
        $start = $this->scanner->getPosition();

        if ($this->scanIdentifier('not')) {
            $this->whitespace();

            return new SupportsNegation($this->supportsConditionInParens(), $this->scanner->spanFrom($start));
        }

        $condition = $this->supportsConditionInParens();
        $this->whitespace();
        $operator = null;

        while ($this->lookingAtIdentifier()) {
            if ($operator !== null) {
                $this->expectIdentifier($operator);
            } elseif ($this->scanIdentifier('or')) {
                $operator = 'or';
            } else {
                $this->expectIdentifier('and');
                $operator = 'and';
            }

            $this->whitespace();
            $right = $this->supportsConditionInParens();

            $condition = new SupportsOperation($condition, $right, $operator, $this->scanner->spanFrom($start));
            $this->whitespace();
        }

        return $condition;
    }

    /**
     * Consumes a parenthesized supports condition, or an interpolation.
     */
    private function supportsConditionInParens(): SupportsCondition
    {
        $start = $this->scanner->getPosition();

        if ($this->lookingAtInterpolatedIdentifier()) {
            $identifier = $this->interpolatedIdentifier();

            if ($identifier->getAsPlain() !== null && strtolower($identifier->getAsPlain()) === 'not') {
                $this->error('"not" is not a valid identifier here.', $identifier->getSpan());
            }

            if ($this->scanner->scanChar('(')) {
                $arguments = $this->interpolatedDeclarationValue(true, true);
                $this->scanner->expectChar(')');

                return new SupportsFunction($identifier, $arguments, $this->scanner->spanFrom($start));
            }

            if (\count($identifier->getContents()) !== 1 || !$identifier->getContents()[0] instanceof Expression) {
                $this->error('Expected @supports condition.', $identifier->getSpan());
            } else {
                return new SupportsInterpolation($identifier->getContents()[0], $identifier->getSpan());
            }
        }

        $this->scanner->expectChar('(');
        $this->whitespace();

        if ($this->scanIdentifier('not')) {
            $this->whitespace();
            $condition = $this->supportsConditionInParens();
            $this->scanner->expectChar(')');

            return new SupportsNegation($condition, $this->scanner->spanFrom($start));
        }

        if ($this->scanner->peekChar() === '(') {
            $condition = $this->supportsCondition();
            $this->scanner->expectChar(')');

            return $condition;
        }

        // Unfortunately, we may have to backtrack here. The grammar is:
        //
        //       Expression ":" Expression
        //     | InterpolatedIdentifier InterpolatedAnyValue?
        //
        // These aren't ambiguous because this `InterpolatedAnyValue` is forbidden
        // from containing a top-level colon, but we still have to parse the full
        // expression to figure out if there's a colon after it.
        //
        // We could avoid the overhead of a full expression parse by looking ahead
        // for a colon (outside of balanced brackets), but in practice we expect the
        // vast majority of real uses to be `Expression ":" Expression`, so it makes
        // sense to parse that case faster in exchange for less code complexity and
        // a slower backtracking case.

        $nameStart = $this->scanner->getPosition();
        $wasInParentheses = $this->inParentheses;

        try {
            $name = $this->expression();
            $this->scanner->expectChar(':');
        } catch (FormatException $e) {
            $this->scanner->setPosition($nameStart);
            $this->inParentheses = $wasInParentheses;

            $identifier = $this->interpolatedIdentifier();
            $operation = $this->trySupportsOperation($identifier, $nameStart);

            if ($operation !== null) {
                $this->scanner->expectChar(')');

                return $operation;
            }

            // If parsing an expression fails, try to parse an
            // `InterpolatedAnyValue` instead. But if that value runs into a
            // top-level colon, then this is probably intended to be a declaration
            // after all, so we rethrow the declaration-parsing error.
            $buffer = new InterpolationBuffer();
            $buffer->addInterpolation($identifier);
            $buffer->addInterpolation($this->interpolatedDeclarationValue(true, true, false));

            $contents = $buffer->buildInterpolation($this->scanner->spanFrom($nameStart));

            if ($this->scanner->peekChar() === ':') {
                throw $e;
            }

            $this->scanner->expectChar(')');

            return new SupportsAnything($contents, $this->scanner->spanFrom($start));
        }

        $declaration = $this->supportsDeclarationValue($name, $start);
        $this->scanner->expectChar(')');

        return $declaration;
    }

    private function supportsDeclarationValue(Expression $name, int $start): SupportsDeclaration
    {
        if ($name instanceof StringExpression && !$name->hasQuotes() && str_starts_with($name->getText()->getInitialPlain(), '--')) {
            $value = new StringExpression($this->interpolatedDeclarationValue());
        } else {
            $this->whitespace();
            $value = $this->expression();
        }

        return new SupportsDeclaration($name, $value, $this->scanner->spanFrom($start));
    }

    /**
     * If $interpolation is followed by `"and"` or `"or"`, parse it as a supports operation.
     *
     * Otherwise, return `null` without moving the scanner position.
     */
    private function trySupportsOperation(Interpolation $interpolation, int $start): ?SupportsOperation
    {
        if (\count($interpolation->getContents()) !== 1) {
            return null;
        }

        $expression = $interpolation->getContents()[0];

        if (!$expression instanceof Expression) {
            return null;
        }

        $beforeWhitespace = $this->scanner->getPosition();
        $this->whitespace();

        $operation = null;
        $operator = null;

        while ($this->lookingAtIdentifier()) {
            if ($operator !== null) {
                $this->expectIdentifier($operator);
            } elseif ($this->scanIdentifier('and')) {
                $operator = 'and';
            } elseif ($this->scanIdentifier('or')) {
                $operator = 'or';
            } else {
                $this->scanner->setPosition($beforeWhitespace);

                return null;
            }

            $this->whitespace();
            $right = $this->supportsConditionInParens();

            $operation = new SupportsOperation($operation ?? new SupportsInterpolation($expression, $interpolation->getSpan()), $right, $operator, $this->scanner->spanFrom($start));
            $this->whitespace();
        }

        return $operation;
    }

    /**
     * Returns whether the scanner is immediately before an identifier that may
     * contain interpolation.
     *
     * This is based on [the CSS algorithm][], but it assumes all backslashes
     * start escapes and it considers interpolation to be valid in an identifier.
     *
     * [the CSS algorithm]: https://drafts.csswg.org/css-syntax-3/#would-start-an-identifier
     */
    private function lookingAtInterpolatedIdentifier(): bool
    {
        $first = $this->scanner->peekChar();

        if ($first === null) {
            return false;
        }

        if ($first === '\\' || Character::isNameStart($first)) {
            return true;
        }

        if ($first === '#' && $this->scanner->peekChar(1) === '{') {
            return true;
        }

        if ($first !== '-') {
            return false;
        }

        $second = $this->scanner->peekChar(1);

        if ($second === null) {
            return false;
        }

        if ($second === '#') {
            return $this->scanner->peekChar(2) === '{';
        }

        return $second === '\\' || $second === '-' || Character::isNameStart($second);
    }

    /**
     * Returns whether the scanner is immediately before a sequence of characters
     * that could be part of an CSS identifier body.
     *
     * The identifier body may include interpolation.
     */
    private function lookingAtInterpolatedIdentifierBody(): bool
    {
        $first = $this->scanner->peekChar();

        if ($first === null) {
            return false;
        }

        if ($first === '\\' || Character::isName($first)) {
            return true;
        }

        return $first === '#' && $this->scanner->peekChar(1) === '{';
    }

    /**
     * Returns whether the scanner is immediately before a SassScript expression.
     */
    private function lookingAtExpression(): bool
    {
        $character = $this->scanner->peekChar();

        if ($character === null) {
            return false;
        }

        if ($character === '.') {
            return $this->scanner->peekChar(1) !== '.';
        }

        if ($character === '!') {
            $next = $this->scanner->peekChar(1);

            return $next === null || $next === 'i' || $next === 'I' || Character::isWhitespace($next);
        }

        return $character === '(' ||
            $character === '/' ||
            $character === '[' ||
            $character === "'" ||
            $character === '"' ||
            $character === '#' ||
            $character === '+' ||
            $character === '-' ||
            $character === '\\' ||
            $character === '$' ||
            $character === '&' ||
            Character::isNameStart($character) ||
            Character::isDigit($character);
    }

    /**
     * Consumes a block of $child statements and passes them, as well as the
     * span from $start to the end of the child block, to $create.
     *
     * @template T
     * @param callable(): Statement $child
     * @param callable(Statement[], FileSpan): T $create
     * @return T
     *
     * @param-immediately-invoked-callable $child
     * @param-immediately-invoked-callable $create
     */
    private function withChildren(callable $child, int $start, callable $create)
    {
        $children = $this->children($child);
        $result = $create($children, $this->scanner->spanFrom($start));
        $this->whitespaceWithoutComments();

        return $result;
    }

    /**
     * Like {@see identifier}, but rejects identifiers that begin with `_` or `-`.
     */
    private function publicIdentifier(): string
    {
        $start = $this->scanner->getPosition();
        $result = $this->identifier();
        $this->assertPublic($result, fn() => $this->scanner->spanFrom($start));

        return $result;
    }

    /**
     * Throws an error if $identifier isn't public.
     *
     * Calls $span to provide the span for an error if one occurs.
     *
     * @param callable(): FileSpan $span
     *
     * @param-immediately-invoked-callable $span
     */
    private function assertPublic(string $identifier, callable $span): void
    {
        if (!Character::isPrivate($identifier)) {
            return;
        }

        $this->error("Private members can't be accessed from outside their modules.", $span());
    }

    /**
     * Adds $expression to $buffer, or if it's an unquoted string adds the
     * interpolation it contains instead.
     */
    private function addOrInject(InterpolationBuffer $buffer, Expression $expression): void
    {
        if ($expression instanceof StringExpression && !$expression->hasQuotes()) {
            $buffer->addInterpolation($expression->getText());
        } else {
            $buffer->add($expression);
        }
    }

    /**
     * Whether this is parsing the indented syntax.
     */
    abstract protected function isIndented(): bool;

    /**
     * Whether this is a plain CSS stylesheet.
     */
    protected function isPlainCss(): bool
    {
        return false;
    }

    /**
     * The indentation level at the current scanner position.
     *
     * This value isn't used directly by StylesheetParser; it's just passed to
     * {@see scanElse}.
     */
    abstract protected function getCurrentIndentation(): int;

    /**
     * Parses and returns a selector used in a style rule.
     */
    abstract protected function styleRuleSelector(): Interpolation;

    /**
     * Asserts that the scanner is positioned before a statement separator, or at
     * the end of a list of statements.
     *
     * If the name of the parent rule is passed, it's used for error reporting.
     *
     * This consumes whitespace, but nothing else, including comments.
     *
     * @throws FormatException
     */
    abstract protected function expectStatementSeparator(?string $name = null): void;

    /**
     * Whether the scanner is positioned at the end of a statement.
     */
    abstract protected function atEndOfStatement(): bool;

    /**
     * Whether the scanner is positioned before a block of children that can be
     * parsed with {@see children}.
     */
    abstract protected function lookingAtChildren(): bool;

    /**
     * Tries to scan an `@else` rule after an `@if` block, and returns whether that succeeded.
     *
     * This should just scan the rule name, not anything afterwards.
     * $ifIndentation is the result of {@see getCurrentIndentation} from before the
     * corresponding `@if` was parsed.
     */
    abstract protected function scanElse(int $ifIndentation): bool;

    /**
     * Consumes a block of child statements.
     *
     * Unlike most production consumers, this does *not* consume trailing
     * whitespace. This is necessary to ensure that the source span for the
     * parent rule doesn't cover whitespace after the rule.
     *
     * @param callable(): Statement $child
     *
     * @return Statement[]
     *
     * @param-immediately-invoked-callable $child
     */
    abstract protected function children(callable $child): array;

    /**
     * Consumes top-level statements.
     *
     * The $statement callback may return `null`, indicating that a statement
     * was consumed that shouldn't be added to the AST.
     *
     * @param callable(): ?Statement $statement
     *
     * @return Statement[]
     *
     * @param-immediately-invoked-callable $statement
     */
    abstract protected function statements(callable $statement): array;
}
