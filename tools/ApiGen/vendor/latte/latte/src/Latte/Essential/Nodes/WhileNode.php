<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;


/**
 * {while $cond}
 */
class WhileNode extends StatementNode
{
	public ExpressionNode $condition;
	public AreaNode $content;
	public bool $postTest;


	/** @return \Generator<int, ?array, array{AreaNode, ?Tag}, static> */
	public static function create(Tag $tag): \Generator
	{
		$node = new static;
		$node->postTest = $tag->parser->isEnd();
		if (!$node->postTest) {
			$node->condition = $tag->parser->parseExpression();
		}

		[$node->content, $nextTag] = yield;
		if ($node->postTest) {
			$nextTag->expectArguments();
			$node->condition = $nextTag->parser->parseExpression();
		}

		return $node;
	}


	public function print(PrintContext $context): string
	{
		return $this->postTest
			? $context->format(
				<<<'XX'
					do %line {
						%node
					} while (%node);

					XX,
				$this->position,
				$this->content,
				$this->condition,
			)
			: $context->format(
				<<<'XX'
					while (%node) %line {
						%node
					}

					XX,
				$this->condition,
				$this->position,
				$this->content,
			);
	}


	public function &getIterator(): \Generator
	{
		yield $this->condition;
		yield $this->content;
	}
}
