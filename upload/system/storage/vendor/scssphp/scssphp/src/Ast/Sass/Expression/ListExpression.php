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

namespace ScssPhp\ScssPhp\Ast\Sass\Expression;

use ScssPhp\ScssPhp\Ast\Sass\Expression;
use ScssPhp\ScssPhp\Value\ListSeparator;
use ScssPhp\ScssPhp\Visitor\ExpressionVisitor;
use SourceSpan\FileSpan;

/**
 * A list literal.
 *
 * @internal
 */
final class ListExpression implements Expression
{
    /**
     * @var list<Expression>
     */
    private readonly array $contents;

    private readonly ListSeparator $separator;

    private readonly FileSpan $span;

    private readonly bool $brackets;

    /**
     * ListExpression constructor.
     *
     * @param list<Expression> $contents
     */
    public function __construct(array $contents, ListSeparator $separator, FileSpan $span, bool $brackets = false)
    {
        $this->contents = $contents;
        $this->separator = $separator;
        $this->span = $span;
        $this->brackets = $brackets;
    }

    /**
     * @return list<Expression>
     */
    public function getContents(): array
    {
        return $this->contents;
    }

    public function getSeparator(): ListSeparator
    {
        return $this->separator;
    }

    public function hasBrackets(): bool
    {
        return $this->brackets;
    }

    public function getSpan(): FileSpan
    {
        return $this->span;
    }

    public function accept(ExpressionVisitor $visitor)
    {
        return $visitor->visitListExpression($this);
    }

    public function __toString(): string
    {
        $buffer = '';
        if ($this->hasBrackets()) {
            $buffer .= '[';
        } elseif (\count($this->contents) === 0 || (\count($this->contents) === 1 && $this->separator === ListSeparator::COMMA)) {
            $buffer .= '(';
        }

        $buffer .= implode(
            $this->separator === ListSeparator::COMMA ? ', ' : ' ',
            array_map(fn($element) => $this->elementNeedsParens($element) ? "($element)" : (string) $element, $this->contents)
        );

        if ($this->hasBrackets()) {
            $buffer .= ']';
        } elseif (\count($this->contents) === 0) {
            $buffer .= ')';
        } elseif (\count($this->contents) === 1 && $this->separator === ListSeparator::COMMA) {
            $buffer .= ',)';
        }

        return $buffer;
    }

    /**
     * Returns whether $expression, contained in $this, needs parentheses when
     * printed as Sass source.
     */
    private function elementNeedsParens(Expression $expression): bool
    {
        if ($expression instanceof ListExpression) {
            if (\count($expression->contents) < 2) {
                return false;
            }

            if ($expression->brackets) {
                return false;
            }

            return $this->separator === ListSeparator::COMMA ? $expression->separator === ListSeparator::COMMA : $expression->separator !== ListSeparator::UNDECIDED;
        }

        if ($this->separator !== ListSeparator::SPACE) {
            return false;
        }

        if ($expression instanceof UnaryOperationExpression) {
            return $expression->getOperator() === UnaryOperator::PLUS || $expression->getOperator() === UnaryOperator::MINUS;
        }

        return false;
    }
}
