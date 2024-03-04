<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes;

use Latte\Compiler\Node;
use Latte\Compiler\PrintContext;


final class TemplateNode extends Node
{
	public FragmentNode $head;
	public FragmentNode $main;
	public string $contentType;


	public function print(PrintContext $context): string
	{
		throw new \LogicException('Cannot directly print TemplateNode');
	}


	public function &getIterator(): \Generator
	{
		yield $this->head;
		yield $this->main;
	}
}
