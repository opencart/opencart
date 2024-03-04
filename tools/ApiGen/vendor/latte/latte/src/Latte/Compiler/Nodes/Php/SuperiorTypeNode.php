<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php;

use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


class SuperiorTypeNode extends ComplexTypeNode
{
	public function __construct(
		public string $type,
		public ?Position $position = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		throw new \LogicException('Cannot directly print SuperiorTypeNode');
	}


	public function &getIterator(): \Generator
	{
		false && yield;
	}
}
