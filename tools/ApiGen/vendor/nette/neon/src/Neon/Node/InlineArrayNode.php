<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Neon\Node;


/** @internal */
final class InlineArrayNode extends ArrayNode
{
	public function __construct(
		public string $bracket,
	) {
	}


	public function toString(): string
	{
		return $this->bracket
			. ArrayItemNode::itemsToInlineString($this->items)
			. ['[' => ']', '{' => '}', '(' => ')'][$this->bracket];
	}
}
