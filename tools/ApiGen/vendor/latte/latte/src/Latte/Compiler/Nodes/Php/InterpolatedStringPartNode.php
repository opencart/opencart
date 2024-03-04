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


class InterpolatedStringPartNode extends Node
{
	public function __construct(
		public string $value,
		public ?Position $position = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		throw new \LogicException('Cannot directly print InterpolatedStringPart');
	}


	public function &getIterator(): \Generator
	{
		false && yield;
	}
}
