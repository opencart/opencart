<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte\Compiler\Nodes\Php\ArgumentNode;
use Latte\Compiler\Nodes\Php\Expression;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\IdentifierNode;
use Latte\Compiler\Nodes\Php\NameNode;
use Latte\Compiler\Nodes\Php\Scalar;


final class ExpressionBuilder
{
	public function __construct(
		private /*readonly*/ ExpressionNode|NameNode $expr,
	) {
	}


	public static function variable(string $name): self
	{
		return new self(new Expression\VariableNode(ltrim($name, '$')));
	}


	public static function class(string $name): self
	{
		return new self(new NameNode($name));
	}


	public static function function(ExpressionNode|self|string $name, array $args = []): self
	{
		$name = is_string($name)
			? new NameNode($name)
			: ($name instanceof self ? $name->expr : $name);
		return new self(new Expression\FunctionCallNode($name, self::arrayToArgs($args)));
	}


	public function property(ExpressionNode|self|string $name): self
	{
		$name = is_string($name)
			? new IdentifierNode($name)
			: ($name instanceof self ? $name->expr : $name);
		return new self(new Expression\PropertyFetchNode($this->expr, $name));
	}


	public function method(ExpressionNode|self|string $name, array $args = []): self
	{
		$name = is_string($name)
			? new IdentifierNode($name)
			: ($name instanceof self ? $name->expr : $name);
		return new self(new Expression\MethodCallNode($this->expr, $name, self::arrayToArgs($args)));
	}


	public function staticMethod(ExpressionNode|self|string $name, array $args = []): self
	{
		$name = is_string($name)
			? new IdentifierNode($name)
			: ($name instanceof self ? $name->expr : $name);
		return new self(new Expression\StaticCallNode($this->expr, $name, self::arrayToArgs($args)));
	}


	public function build(): ExpressionNode|NameNode
	{
		return $this->expr;
	}


	public static function valueToNode(bool|int|float|string|array|null|ExpressionNode $value): ExpressionNode
	{
		return match (true) {
			$value === null => new Scalar\NullNode,
			is_bool($value) => new Scalar\BooleanNode($value),
			is_int($value) => new Scalar\IntegerNode($value),
			is_float($value) => new Scalar\FloatNode($value),
			is_string($value) => new Scalar\StringNode($value),
			is_array($value) => self::arrayToNode($value),
			default => $value, // ExpressionNode
		};
	}


	private static function arrayToNode(array $arr): Expression\ArrayNode
	{
		$node = new Expression\ArrayNode;
		$lastKey = -1;
		foreach ($arr as $key => $val) {
			if ($lastKey !== null && ++$lastKey === $key) {
				$node->items[] = new Nodes\Php\ArrayItemNode(self::valueToNode($val));
			} else {
				$lastKey = null;
				$node->items[] = new Nodes\Php\ArrayItemNode(self::valueToNode($val), self::valueToNode($key));
			}
		}

		return $node;
	}


	/** @return ArgumentNode[] */
	private static function arrayToArgs(array $arr): array
	{
		$args = [];
		foreach ($arr as $key => $arg) {
			$args[] = $arg instanceof ArgumentNode
				? $arg
				: new ArgumentNode(
					self::valueToNode($arg),
					name: is_string($key) ? new IdentifierNode($key) : null,
				);
		}

		return $args;
	}
}
