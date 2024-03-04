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
 * {php statement; statement; ...}
 */
class RawPhpNode extends StatementNode
{
	public string $code;


	public static function create(Tag $tag): static
	{
		$tag->expectArguments();
		while (!$tag->parser->stream->consume()->isEnd());
		$node = new static;
		$node->code = trim($tag->parser->text);
		if (!preg_match('~[;}]$~', $node->code)) {
			$node->code .= ';';
		}
		return $node;
	}


	public function print(PrintContext $context): string
	{
		return $context->format(
			'%line; %raw ',
			$this->position,
			$this->code,
		);
	}


	public function &getIterator(): \Generator
	{
		false && yield;
	}
}
