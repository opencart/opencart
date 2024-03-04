<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes;

use Latte\Compiler\PrintContext;


final class FragmentNode extends AreaNode
{
	/** @var AreaNode[] */
	public array $children = [];


	/** @param AreaNode[] $children */
	public function __construct(array $children = [])
	{
		foreach ($children as $child) {
			$this->append($child);
		}
	}


	public function append(AreaNode $node): static
	{
		if ($node instanceof self) {
			$this->children = array_merge($this->children, $node->children);
		} elseif (!$node instanceof NopNode) {
			$this->children[] = $node;
		}
		$this->position ??= $node->position;
		return $this;
	}


	public function print(PrintContext $context): string
	{
		$res = '';
		foreach ($this->children as $child) {
			$res .= $child->print($context);
		}

		return $res;
	}


	public function &getIterator(): \Generator
	{
		foreach ($this->children as &$item) {
			yield $item;
		}
	}
}
