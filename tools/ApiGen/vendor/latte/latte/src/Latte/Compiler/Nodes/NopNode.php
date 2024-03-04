<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes;

use Latte\Compiler\PrintContext;


class NopNode extends AreaNode
{
	public function print(PrintContext $context): string
	{
		return '';
	}


	public function &getIterator(): \Generator
	{
		false && yield;
	}
}
