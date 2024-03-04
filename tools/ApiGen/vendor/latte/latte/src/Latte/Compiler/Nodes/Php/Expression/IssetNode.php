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


class IssetNode extends ExpressionNode
{
	public function __construct(
		/** @var ExpressionNode[] */
		public array $vars,
		public ?Position $position = null,
	) {
		(function (ExpressionNode ...$args) {})(...$vars);
	}


	public function print(PrintContext $context): string
	{
		return 'isset(' . $context->implode($this->vars) . ')';
	}


	public function &getIterator(): \Generator
	{
		foreach ($this->vars as &$item) {
			yield $item;
		}
	}
}
