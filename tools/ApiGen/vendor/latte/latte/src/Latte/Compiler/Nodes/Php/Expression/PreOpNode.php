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


class PreOpNode extends ExpressionNode
{
	private const Ops = ['++' => 1, '--' => 1];


	public function __construct(
		public ExpressionNode $var,
		public /*readonly*/ string $operator,
		public ?Position $position = null,
	) {
		if (!isset(self::Ops[$this->operator])) {
			throw new \InvalidArgumentException("Unexpected operator '$this->operator'");
		}
	}


	public function print(PrintContext $context): string
	{
		return $context->prefixOp($this, $this->operator, $this->var);
	}


	public function &getIterator(): \Generator
	{
		yield $this->var;
	}
}
