<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Expression;

use Latte\Compiler\Nodes\Php;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\NameNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


class NewNode extends ExpressionNode
{
	public function __construct(
		public NameNode|ExpressionNode $class,
		/** @var Php\ArgumentNode[] */
		public array $args = [],
		public ?Position $position = null,
	) {
		(function (Php\ArgumentNode ...$args) {})(...$args);
	}


	public function print(PrintContext $context): string
	{
		return 'new ' . $context->dereferenceExpr($this->class)
			. ($this->args ? '(' . $context->implode($this->args) . ')' : '');
	}


	public function &getIterator(): \Generator
	{
		yield $this->class;
		foreach ($this->args as &$item) {
			yield $item;
		}
	}
}
