<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php;

use Latte\Compiler\Node;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


class MatchArmNode extends Node
{
	public function __construct(
		/** @var ExpressionNode[]|null */
		public ?array $conds,
		public ExpressionNode $body,
		public ?Position $position = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		return ($this->conds ? $context->implode($this->conds) : 'default')
			. ' => '
			. $this->body->print($context);
	}


	public function &getIterator(): \Generator
	{
		if ($this->conds) {
			foreach ($this->conds as &$item) {
				yield $item;
			}
		}
		yield $this->body;
	}
}
