<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte;
use Latte\Compiler\Nodes\Php;
use Latte\Compiler\Nodes\Php\Expression;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\Scalar;


final class NodeHelpers
{
	use Latte\Strict;

	/** @return Node[] */
	public static function find(Node $node, callable $filter): array
	{
		$found = [];
		(new NodeTraverser)
			->traverse($node, enter: function (Node $node) use ($filter, &$found) {
				if ($filter($node)) {
					$found[] = $node;
				}
			});
		return $found;
	}


	public static function findFirst(Node $node, callable $filter): ?Node
	{
		$found = null;
		(new NodeTraverser)
			->traverse($node, enter: function (Node $node) use ($filter, &$found) {
				if ($filter($node)) {
					$found = $node;
					return NodeTraverser::StopTraversal;
				}
			});
		return $found;
	}


	public static function clone(Node $node): Node
	{
		return (new NodeTraverser)
			->traverse($node, enter: fn(Node $node) => clone $node);
	}


	public static function toValue(ExpressionNode $node, bool $constants = false): mixed
	{
		if ($node instanceof Scalar\BooleanNode
			|| $node instanceof Scalar\FloatNode
			|| $node instanceof Scalar\IntegerNode
			|| $node instanceof Scalar\StringNode
		) {
			return $node->value;

		} elseif ($node instanceof Scalar\NullNode) {
			return null;

		} elseif ($node instanceof Expression\ArrayNode) {
			$res = [];
			foreach ($node->items as $item) {
				$value = self::toValue($item->value, $constants);
				if ($item->key) {
					$key = $item->key instanceof Php\IdentifierNode
						? $item->key->name
						: self::toValue($item->key, $constants);
					$res[$key] = $value;

				} elseif ($item->unpack) {
					$res = array_merge($res, $value);

				} else {
					$res[] = $value;
				}
			}

			return $res;

		} elseif ($node instanceof Expression\ConstantFetchNode && $constants) {
			$name = $node->name->toCodeString();
			return defined($name)
				? constant($name)
				: throw new \InvalidArgumentException("The constant '$name' is not defined.");

		} elseif ($node instanceof Expression\ClassConstantFetchNode && $constants) {
			$class = $node->class instanceof Php\NameNode
				? $node->class->toCodeString()
				: self::toValue($node->class, $constants);
			$name = $class . '::' . $node->name->name;
			return defined($name)
				? constant($name)
				: throw new \InvalidArgumentException("The constant '$name' is not defined.");

		} else {
			throw new \InvalidArgumentException('The expression cannot be converted to PHP value.');
		}
	}


	public static function toText(?Node $node): ?string
	{
		if ($node instanceof Nodes\FragmentNode) {
			$res = '';
			foreach ($node->children as $child) {
				if (($s = self::toText($child)) === null) {
					return null;
				}
				$res .= $s;
			}

			return $res;
		}

		return match (true) {
			$node instanceof Nodes\TextNode => $node->content,
			$node instanceof Nodes\Html\QuotedValue => self::toText($node->value),
			$node instanceof Nodes\NopNode => '',
			default => null,
		};
	}
}
