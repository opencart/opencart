<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Html;

use Latte\Compiler\NodeHelpers;
use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


class AttributeNode extends AreaNode
{
	public function __construct(
		public AreaNode $name,
		public ?AreaNode $value = null,
		public ?Position $position = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		$res = $this->name->print($context);
		if ($this->value) {
			$context->beginEscape()->enterHtmlAttribute(NodeHelpers::toText($this->name));
			$res .= "echo '=';";
			$res .= $this->value->print($context);
			$context->restoreEscape();
		}
		return $res;
	}


	public function &getIterator(): \Generator
	{
		yield $this->name;
		if ($this->value) {
			yield $this->value;
		}
	}
}
