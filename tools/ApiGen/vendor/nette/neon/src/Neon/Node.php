<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Neon;


/**
 * @implements \IteratorAggregate<Node>
 */
abstract class Node implements \IteratorAggregate
{
	public ?int $startTokenPos = null;
	public ?int $endTokenPos = null;
	public ?int $startLine = null;
	public ?int $endLine = null;


	abstract public function toValue(): mixed;


	abstract public function toString(): string;


	public function &getIterator(): \Generator
	{
		return;
		yield;
	}
}
