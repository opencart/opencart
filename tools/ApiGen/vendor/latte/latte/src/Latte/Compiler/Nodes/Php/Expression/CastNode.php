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


class CastNode extends ExpressionNode
{
	private const Types = ['int' => 1, 'float' => 1, 'string' => 1, 'array' => 1, 'object' => 1, 'bool' => 1];


	public function __construct(
		public /*readonly*/ string $type,
		public ExpressionNode $expr,
		public ?Position $position = null,
	) {
		if (!isset(self::Types[strtolower($this->type)])) {
			throw new \InvalidArgumentException("Unexpected type '$this->type'");
		}
	}


	public function print(PrintContext $context): string
	{
		return $context->prefixOp($this, '(' . $this->type . ') ', $this->expr);
	}


	public function &getIterator(): \Generator
	{
		yield $this->expr;
	}
}
