<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Expression;

use Latte\Compiler\Nodes\Php\ArgumentNode;
use Latte\Compiler\Nodes\Php\ArrayItemNode;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\IdentifierNode;
use Latte\Compiler\Nodes\Php\Scalar;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


class ArrayNode extends ExpressionNode
{
	public function __construct(
		/** @var array<ArrayItemNode|null> */
		public array $items = [],
		public ?Position $position = null,
	) {
		(function (?ArrayItemNode ...$args) {})(...$items);
	}


	/** @param  ArgumentNode[]  $args */
	public static function fromArguments(array $args): self
	{
		$node = new self;
		foreach ($args as $arg) {
			$node->items[] = new ArrayItemNode($arg->value, $arg->name, $arg->byRef, $arg->unpack, $arg->position);
		}

		return $node;
	}


	/** @return ArgumentNode[] */
	public function toArguments(): array
	{
		$args = [];
		foreach ($this->items as $item) {
			$key = match (true) {
				$item->key instanceof Scalar\StringNode => new IdentifierNode($item->key->value),
				$item->key instanceof IdentifierNode => $item->key,
				$item->key === null => null,
				default => throw new \InvalidArgumentException('The expression used in the key cannot be converted to an argument.'),
			};
			$args[] = new ArgumentNode($item->value, $item->byRef, $item->unpack, $key, $item->position);
		}

		return $args;
	}


	public function print(PrintContext $context): string
	{
		// Converts [...$var] -> $var, because PHP 8.0 doesn't support unpacking with string keys
		if (PHP_VERSION_ID < 80100) {
			$res = '[';
			$merge = false;
			foreach ($this->items as $item) {
				if ($item === null) {
					$res .= ', ';
				} elseif ($item->unpack) {
					$res .= '], ' . $item->value->print($context) . ', [';
					$merge = true;
				} else {
					$res .= $item->print($context) . ', ';
				}
			}

			$res = str_ends_with($res, ', ') ? substr($res, 0, -2) : $res;
			return $merge ? "array_merge($res])" : $res . ']';
		}

		return '[' . $context->implode($this->items) . ']';
	}


	public function &getIterator(): \Generator
	{
		foreach ($this->items as &$item) {
			if ($item) {
				yield $item;
			}
		}
	}
}
