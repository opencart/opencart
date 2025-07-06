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
use ScssPhp\ScssPhp\Util\SpanUtil;
use ScssPhp\ScssPhp\Visitor\ExpressionVisitor;
use SourceSpan\FileSpan;

/**
 * A binary operator, as in `1 + 2` or `$this and $other`.
 *
 * @internal
 */
final class BinaryOperationExpression implements Expression
{
    private readonly BinaryOperator $operator;

    private readonly Expression $left;

    private readonly Expression $right;

    /**
     * Whether this is a dividedBy operation that may be interpreted as slash-separated numbers.
     */
    private bool $allowsSlash = false;

    public function __construct(BinaryOperator $operator, Expression $left, Expression $right)
    {
        $this->operator = $operator;
        $this->left = $left;
        $this->right = $right;
    }

    /**
     * Creates a dividedBy operation that may be interpreted as slash-separated numbers.
     */
    public static function slash(Expression $left, Expression $right): self
    {
        $operation = new self(BinaryOperator::DIVIDED_BY, $left, $right);
        $operation->allowsSlash = true;

        return $operation;
    }

    public function getOperator(): BinaryOperator
    {
        return $this->operator;
    }

    public function getLeft(): Expression
    {
        return $this->left;
    }

    public function getRight(): Expression
    {
        return $this->right;
    }

    public function allowsSlash(): bool
    {
        return $this->allowsSlash;
    }

    public function getSpan(): FileSpan
    {
        $left = $this->left;

        while ($left instanceof BinaryOperationExpression) {
            $left = $left->left;
        }

        $right = $this->right;

        while ($right instanceof BinaryOperationExpression) {
            $right = $right->right;
        }

        $leftSpan = $left->getSpan();
        $rightSpan = $right->getSpan();

        return $leftSpan->expand($rightSpan);
    }

    /**
     * Returns the span that covers only {@see $operator}.
     *
     * @internal
     */
    public function getOperatorSpan(): FileSpan
    {
        $leftSpan = $this->left->getSpan();
        $rightSpan = $this->right->getSpan();

        if ($leftSpan->getFile() === $rightSpan->getFile() && $leftSpan->getEnd()->getOffset() < $rightSpan->getStart()->getOffset()) {
            return SpanUtil::trim($leftSpan->getFile()->span($leftSpan->getEnd()->getOffset(), $rightSpan->getStart()->getOffset()));
        }

        return $this->getSpan();
    }

    public function accept(ExpressionVisitor $visitor)
    {
        return $visitor->visitBinaryOperationExpression($this);
    }

    public function __toString(): string
    {
        $buffer = '';

        $leftNeedsParens = ($this->left instanceof BinaryOperationExpression && $this->left->getOperator()->getPrecedence() < $this->operator->getPrecedence()) || ($this->left instanceof ListExpression && !$this->left->hasBrackets() && \count($this->left->getContents()) > 1);
        if ($leftNeedsParens) {
            $buffer .= '(';
        }
        $buffer .= $this->left;
        if ($leftNeedsParens) {
            $buffer .= ')';
        }

        $buffer .= ' ';
        $buffer .= $this->operator->getOperator();
        $buffer .= ' ';

        $rightNeedsParens = ($this->right instanceof BinaryOperationExpression && $this->right->getOperator()->getPrecedence() <= $this->operator->getPrecedence() && !($this->right->operator === $this->operator && $this->operator->isAssociative())) || ($this->right instanceof ListExpression && !$this->right->hasBrackets() && \count($this->right->getContents()) > 1);
        if ($rightNeedsParens) {
            $buffer .= '(';
        }
        $buffer .= $this->right;
        if ($rightNeedsParens) {
            $buffer .= ')';
        }

        return $buffer;
    }
}
