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
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;


/**
 * {first [$width]}
 * {last [$width]}
 * {sep [$width]}
 */
class FirstLastSepNode extends StatementNode
{
	public string $name;
	public ?ExpressionNode $width;
	public AreaNode $then;
	public ?AreaNode $else = null;
	public ?Position $elseLine = null;


	/** @return \Generator<int, ?array, array{AreaNode, ?Tag}, static> */
	public static function create(Tag $tag): \Generator
	{
		$node = new static;
		$node->name = $tag->name;
		$node->width = $tag->parser->isEnd() ? null : $tag->parser->parseExpression();

		[$node->then, $nextTag] = yield ['else'];
		if ($nextTag?->name === 'else') {
			$node->elseLine = $nextTag->position;
			[$node->else] = yield;
		}

		return $node;
	}


	public function print(PrintContext $context): string
	{
		$cond = match ($this->name) {
			'first' => '$iterator->isFirst',
			'last' => '$iterator->isLast',
			'sep' => '!$iterator->isLast',
		};
		return $context->format(
			$this->else
				? "if ($cond(%node)) %line { %node } else %line { %node }\n"
				: "if ($cond(%node)) %line { %node }\n",
			$this->width,
			$this->position,
			$this->then,
			$this->elseLine,
			$this->else,
		);
	}


	public function &getIterator(): \Generator
	{
		if ($this->width) {
			yield $this->width;
		}
		yield $this->then;
		if ($this->else) {
			yield $this->else;
		}
	}
}
