<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Neon\Node;

use Nette\Neon\Entity;
use Nette\Neon\Node;


/** @internal */
final class EntityNode extends Node
{
	public function __construct(
		public Node $value,
		/** @var ArrayItemNode[] */
		public array $attributes = [],
	) {
	}


	public function toValue(): Entity
	{
		return new Entity(
			$this->value->toValue(),
			ArrayItemNode::itemsToArray($this->attributes),
		);
	}


	public function toString(): string
	{
		return $this->value->toString()
			. '('
			. ($this->attributes ? ArrayItemNode::itemsToInlineString($this->attributes) : '')
			. ')';
	}


	public function &getIterator(): \Generator
	{
		yield $this->value;

		foreach ($this->attributes as &$item) {
			yield $item;
		}
	}
}
