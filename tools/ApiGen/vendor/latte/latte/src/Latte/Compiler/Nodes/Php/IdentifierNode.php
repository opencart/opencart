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


class IdentifierNode extends Node
{
	public function __construct(
		public string $name,
		public ?Position $position = null,
	) {
	}


	public function __toString(): string
	{
		return $this->name;
	}


	public function print(PrintContext $context): string
	{
		return $this->name;
	}


	public function &getIterator(): \Generator
	{
		false && yield;
	}
}
