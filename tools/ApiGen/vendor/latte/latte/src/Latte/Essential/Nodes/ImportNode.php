<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;


/**
 * {import "file"}
 */
class ImportNode extends StatementNode
{
	public ExpressionNode $file;


	public static function create(Tag $tag): static
	{
		$tag->expectArguments();
		$node = new static;
		$node->file = $tag->parser->parseUnquotedStringOrExpression();
		return $node;
	}


	public function print(PrintContext $context): string
	{
		return $context->format(
			'$this->createTemplate(%node, $this->params, "import")->render() %line;',
			$this->file,
			$this->position,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->file;
	}
}
