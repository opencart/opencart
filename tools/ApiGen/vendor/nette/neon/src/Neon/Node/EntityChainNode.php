<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Neon\Node;

use Nette\Neon;
use Nette\Neon\Node;


/** @internal */
final class EntityChainNode extends Node
{
	public function __construct(
		/** @var EntityNode[] */
		public array $chain = [],
	) {
	}


	public function toValue(): Neon\Entity
	{
		$entities = [];
		foreach ($this->chain as $item) {
			$entities[] = $item->toValue();
		}

		return new Neon\Entity(Neon\Neon::Chain, $entities);
	}


	public function toString(): string
	{
		return implode('', array_map(fn($entity) => $entity->toString(), $this->chain));
	}


	public function &getIterator(): \Generator
	{
		foreach ($this->chain as &$item) {
			yield $item;
		}
	}
}
