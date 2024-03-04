<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Expression;

use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\IdentifierNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


class MethodCallableNode extends ExpressionNode
{
	public function __construct(
		public ExpressionNode $object,
		public IdentifierNode|ExpressionNode $name,
		public ?Position $position = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		return PHP_VERSION_ID < 80100
			? '[' . $this->object->print($context) . ', ' . $context->memberAsString($this->name) . ']'
			: $context->dereferenceExpr($this->object)
				. '->' . $context->objectProperty($this->name) . '(...)';
	}


	public function &getIterator(): \Generator
	{
		yield $this->object;
		yield $this->name;
	}
}
