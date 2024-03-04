<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Expression;

use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


class UnaryOpNode extends ExpressionNode
{
	private const Ops = ['+' => 1, '-' => 1, '~' => 1];


	public function __construct(
		public ExpressionNode $expr,
		public /*readonly*/ string $operator,
		public ?Position $position = null,
	) {
		if (!isset(self::Ops[$this->operator])) {
			throw new \InvalidArgumentException("Unexpected operator '$this->operator'");
		}
	}


	public function print(PrintContext $context): string
	{
		return $this->expr instanceof self || $this->expr instanceof PreOpNode
			? $this->operator . '(' . $this->expr->print($context) . ')' // Enforce -(-$expr) instead of --$expr
			: $context->prefixOp($this, $this->operator, $this->expr);
	}


	public function &getIterator(): \Generator
	{
		yield $this->expr;
	}
}
