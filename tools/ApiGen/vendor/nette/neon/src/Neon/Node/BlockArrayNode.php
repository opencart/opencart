<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Neon\Node;


/** @internal */
final class BlockArrayNode extends ArrayNode
{
	public function __construct(
		public string $indentation = '',
	) {
	}


	public function toString(): string
	{
		if (count($this->items) === 0) {
			return '[]';
		}

		$res = ArrayItemNode::itemsToBlockString($this->items);
		return preg_replace('#^(?=.)#m', $this->indentation, $res);
	}
}
