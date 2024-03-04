<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Html;

use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


class QuotedValue extends AreaNode
{
	public function __construct(
		public AreaNode $value,
		public string $quote,
		public ?Position $position = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		$res = 'echo ' . var_export($this->quote, true) . ';';
		$escaper = $context->beginEscape();
		$escaper->enterHtmlAttributeQuote($this->quote);

		if ($this->value instanceof FragmentNode && $escaper->export() === 'html/attr/url') {
			foreach ($this->value->children as $child) {
				$res .= $child->print($context);
				$escaper->enterHtmlAttribute(null, $this->quote);
			}
		} else {
			$res .= $this->value->print($context);
		}

		$res .= 'echo ' . var_export($this->quote, true) . ';';
		$context->restoreEscape();
		return $res;
	}


	public function &getIterator(): \Generator
	{
		yield $this->value;
	}
}
