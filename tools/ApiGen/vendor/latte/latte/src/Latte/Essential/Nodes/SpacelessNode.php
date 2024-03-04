<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\ContentType;


/**
 * {spaceless}
 */
class SpacelessNode extends StatementNode
{
	public AreaNode $content;


	/** @return \Generator<int, ?array, array{AreaNode, ?Tag}, static> */
	public static function create(Tag $tag): \Generator
	{
		$node = new static;
		[$node->content] = yield;
		return $node;
	}


	public function print(PrintContext $context): string
	{
		return $context->format(
			<<<'XX'
				ob_start('Latte\Essential\Filters::%raw', 4096) %line;
				try {
					%node
				} finally {
					ob_end_flush();
				}


				XX,
			$context->getEscaper()->getContentType() === ContentType::Html
				? 'spacelessHtmlHandler'
				: 'spacelessText',
			$this->position,
			$this->content,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->content;
	}
}
