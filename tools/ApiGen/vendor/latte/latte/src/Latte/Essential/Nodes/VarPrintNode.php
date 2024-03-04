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
 * {varPrint [all]}
 */
class VarPrintNode extends StatementNode
{
	public bool $all;


	public static function create(Tag $tag): static
	{
		$stream = $tag->parser->stream;
		$node = new static;
		$node->all = $stream->consume()->text === 'all';
		return $node;
	}


	public function print(PrintContext $context): string
	{
		$vars = $this->all ? 'get_defined_vars()'
			: 'array_diff_key(get_defined_vars(), $this->getParameters())';
		return "(new Latte\\Essential\\Blueprint)->printVars($vars); exit;";
	}


	public function &getIterator(): \Generator
	{
		false && yield;
	}
}
