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

use ScssPhp\ScssPhp\Ast\Sass\ArgumentInvocation;
use ScssPhp\ScssPhp\Ast\Sass\Expression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\FunctionExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\ParenthesizedExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\StringExpression;
use ScssPhp\ScssPhp\Ast\Sass\Import\StaticImport;
use ScssPhp\ScssPhp\Ast\Sass\Interpolation;
use ScssPhp\ScssPhp\Ast\Sass\Statement;
use ScssPhp\ScssPhp\Ast\Sass\Statement\ImportRule;
use ScssPhp\ScssPhp\Function\FunctionRegistry;

/**
 * A parser for imported CSS files.
 *
 * @internal
 */
final class CssParser extends ScssParser
{
    /**
     * Sass global functions which are shadowing a CSS function are allowed in CSS files.
     */
    private const CSS_ALLOWED_FUNCTIONS = [
        'rgb' => true, 'rgba' => true, 'hsl' => true, 'hsla' => true, 'grayscale' => true,
        'invert' => true, 'alpha' => true, 'opacity' => true, 'saturate' => true,
        'min' => true, 'max' => true, 'round' => true, 'abs' => true,
    ];

    protected function isPlainCss(): bool
    {
        return true;
    }

    protected function silentComment(): bool
    {
        if ($this->inExpression()) {
            return false;
        }

        $start = $this->scanner->getPosition();
        parent::silentComment();
        $this->error("Silent comments aren't allowed in plain CSS.", $this->scanner->spanFrom($start));
    }

    protected function atRule(callable $child, bool $root = false): Statement
    {
        $start = $this->scanner->getPosition();

        $this->scanner->expectChar('@');
        $name = $this->interpolatedIdentifier();
        $this->whitespace();

        return match ($name->getAsPlain()) {
            'at-root',
            'content',
            'debug',
            'each',
            'error',
            'extend',
            'for',
            'function',
            'if',
            'include',
            'mixin',
            'return',
            'warn',
            'while' => $this->forbiddenAtRule($start),
            'import' => $this->cssImportRule($start),
            'media' => $this->mediaRule($start),
            '-moz-document' => $this->mozDocumentRule($start, $name),
            'supports' => $this->supportsRule($start),
            default => $this->unknownAtRule($start, $name),
        };
    }

    private function forbiddenAtRule(int $start): never
    {
        $this->almostAnyValue();
        $this->error("This at-rule isn't allowed in plain CSS.", $this->scanner->spanFrom($start));
    }

    private function cssImportRule(int $start): ImportRule
    {
        $urlStart = $this->scanner->getPosition();
        $next = $this->scanner->peekChar();

        if ($next === 'u' || $next === 'U') {
            $url = $this->dynamicUrl();
        } else {
            $url = new StringExpression($this->interpolatedString()->asInterpolation(true));
        }
        $urlSpan = $this->scanner->spanFrom($urlStart);

        $this->whitespace();
        $modifiers = $this->tryImportModifiers();
        $this->expectStatementSeparator('@import rule');

        return new ImportRule([
            new StaticImport(new Interpolation([$url], $urlSpan), $this->scanner->spanFrom($start), $modifiers),
        ], $this->scanner->spanFrom($start));
    }

    protected function parentheses(): Expression
    {
        // Expressions are only allowed within calculations, but we verify this at
        // evaluation time.
        $start = $this->scanner->getPosition();
        $this->scanner->expectChar('(');
        $this->whitespace();
        $expression = $this->expressionUntilComma();
        $this->scanner->expectChar(')');

        return new ParenthesizedExpression($expression, $this->scanner->spanFrom($start));
    }

    protected function identifierLike(): Expression
    {
        $start = $this->scanner->getPosition();
        $identifier = $this->interpolatedIdentifier();
        $plain = $identifier->getAsPlain();
        assert($plain !== null); // CSS doesn't allow non-plain identifiers

        $lower = strtolower($plain);
        $specialFunction = $this->trySpecialFunction($lower, $start);

        if ($specialFunction !== null) {
            return $specialFunction;
        }

        $beforeArguments = $this->scanner->getPosition();
        // `namespacedExpression()` is just here to throw a clearer error.
        if ($this->scanner->scanChar('.')) {
            return $this->namespacedExpression($plain, $start);
        }
        if (!$this->scanner->scanChar('(')) {
            return new StringExpression($identifier);
        }

        $allowEmptySecondArg = $lower === 'var';
        $arguments = [];

        if (!$this->scanner->scanChar(')')) {
            do {
                $this->whitespace();

                if ($allowEmptySecondArg && \count($arguments) === 1 && $this->scanner->peekChar() === ')') {
                    $arguments[] = StringExpression::plain('', $this->scanner->getEmptySpan());
                    break;
                }

                $arguments[] = $this->expressionUntilComma(true);
                $this->whitespace();
            } while ($this->scanner->scanChar(','));
            $this->scanner->expectChar(')');
        }

        if ($plain === 'if' || (!isset(self::CSS_ALLOWED_FUNCTIONS[$plain]) && FunctionRegistry::isBuiltinFunction($plain))) {
            $this->error("This function isn't allowed in plain CSS.", $this->scanner->spanFrom($start));
        }

        return new FunctionExpression(
            $plain,
            new ArgumentInvocation($arguments, [], $this->scanner->spanFrom($beforeArguments)),
            $this->scanner->spanFrom($start)
        );
    }

    protected function namespacedExpression(string $namespace, int $start): Expression
    {
        $expression = parent::namespacedExpression($namespace, $start);

        $this->error("Module namespaces aren't allowed in plain CSS.", $expression->getSpan());
    }
}
