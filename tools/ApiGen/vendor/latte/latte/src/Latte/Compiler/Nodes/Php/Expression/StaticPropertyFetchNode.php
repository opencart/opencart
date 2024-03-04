<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Expression;

use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\NameNode;
use Latte\Compiler\Nodes\Php\VarLikeIdentifierNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


class StaticPropertyFetchNode extends ExpressionNode
{
	public function __construct(
		public NameNode|ExpressionNode $class,
		public VarLikeIdentifierNode|ExpressionNode $name,
		public ?Position $position = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		return $context->dereferenceExpr($this->class)
			. '::$'
			. $context->objectProperty($this->name);
	}


	public function &getIterator(): \Generator
	{
		yield $this->class;
		yield $this->name;
	}
}
