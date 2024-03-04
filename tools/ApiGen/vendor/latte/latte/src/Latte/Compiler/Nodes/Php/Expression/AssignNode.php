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


class AssignNode extends ExpressionNode
{
	public function __construct(
		public ExpressionNode $var,
		public ExpressionNode $expr,
		public bool $byRef = false,
		public ?Position $position = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		return $context->infixOp($this, $this->var, $this->byRef ? ' = &' : ' = ', $this->expr);
	}


	public function &getIterator(): \Generator
	{
		yield $this->var;
		yield $this->expr;
	}
}
