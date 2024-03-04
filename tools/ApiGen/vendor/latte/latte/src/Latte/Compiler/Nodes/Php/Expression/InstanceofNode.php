<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Expression;

use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\NameNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


class InstanceofNode extends ExpressionNode
{
	public function __construct(
		public ExpressionNode $expr,
		public NameNode|ExpressionNode $class,
		public ?Position $position = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		return $context->postfixOp($this, $this->expr, ' instanceof ')
			. $context->dereferenceExpr($this->class);
	}


	public function &getIterator(): \Generator
	{
		yield $this->expr;
		yield $this->class;
	}
}
