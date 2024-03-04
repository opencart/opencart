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


class VariableNode extends ExpressionNode
{
	public function __construct(
		public string|ExpressionNode $name,
		public ?Position $position = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		return $this->name instanceof ExpressionNode
			? '${' . $this->name->print($context) . '}'
			: '$' . $this->name;
	}


	public function &getIterator(): \Generator
	{
		if ($this->name instanceof ExpressionNode) {
			yield $this->name;
		}
	}
}
