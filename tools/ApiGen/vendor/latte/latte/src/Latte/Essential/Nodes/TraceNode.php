<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;


/**
 * {trace}
 */
class TraceNode extends StatementNode
{
	public static function create(Tag $tag): static
	{
		return new static;
	}


	public function print(PrintContext $context): string
	{
		return $context->format(
			'Latte\Essential\Tracer::throw() %line;',
			$this->position,
		);
	}


	public function &getIterator(): \Generator
	{
		false && yield;
	}
}
