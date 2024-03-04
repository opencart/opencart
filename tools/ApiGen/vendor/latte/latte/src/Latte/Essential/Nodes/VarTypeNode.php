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
use Latte\Compiler\Token;


/**
 * {varType type $var}
 */
class VarTypeNode extends StatementNode
{
	public static function create(Tag $tag): static
	{
		$tag->expectArguments();
		$tag->parser->parseType();
		$tag->parser->stream->consume(Token::Php_Variable);
		return new static;
	}


	public function print(PrintContext $context): string
	{
		return '';
	}


	public function &getIterator(): \Generator
	{
		false && yield;
	}
}
