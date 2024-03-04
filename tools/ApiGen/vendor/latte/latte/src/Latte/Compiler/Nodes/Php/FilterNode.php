<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php;

use Latte\Compiler\Node;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


class FilterNode extends Node
{
	public function __construct(
		public IdentifierNode $name,
		/** @var ArgumentNode[] */
		public array $args = [],
		public ?Position $position = null,
	) {
		(function (ArgumentNode ...$args) {})(...$args);
	}


	public function print(PrintContext $context): string
	{
		throw new \LogicException('Cannot directly print FilterNode');
	}


	public function printSimple(PrintContext $context, string $expr): string
	{
		return '($this->filters->' . $this->name . ')('
			. $expr
			. ($this->args ? ', ' . $context->implode($this->args) : '')
			. ')';
	}


	public function printContentAware(PrintContext $context, string $expr): string
	{
		return '$this->filters->filterContent('
			. $context->encodeString($this->name->name)
			. ', $ʟ_fi, '
			. $expr
			. ($this->args ? ', ' . $context->implode($this->args) : '')
			. ')';
	}


	public function &getIterator(): \Generator
	{
		yield $this->name;
		foreach ($this->args as &$item) {
			yield $item;
		}
	}
}
