<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte;
use Latte\Compiler\Nodes\Php as Nodes;
use Latte\Compiler\Nodes\Php\Expression;
use Latte\Compiler\Nodes\Php\Scalar;
use Latte\ContentType;


/**
 * PHP printing helpers and context.
 * The parts are based on great nikic/PHP-Parser project by Nikita Popov.
 */
final class PrintContext
{
	use Latte\Strict;

	public array $paramsExtraction = [];
	public array $blocks = [];

	private array $exprPrecedenceMap = [
		// [precedence, associativity] (-1 is %left, 0 is %nonassoc and 1 is %right)
		Expression\PreOpNode::class              => [10,  1],
		Expression\PostOpNode::class             => [10, -1],
		Expression\UnaryOpNode::class            => [10,  1],
		Expression\CastNode::class               => [10,  1],
		Expression\ErrorSuppressNode::class      => [10,  1],
		Expression\InstanceofNode::class         => [20,  0],
		Expression\NotNode::class                => [30,  1],
		Expression\TernaryNode::class            => [150,  0],
		// parser uses %left for assignments, but they really behave as %right
		Expression\AssignNode::class             => [160,  1],
		Expression\AssignOpNode::class           => [160,  1],
	];

	private array $binaryPrecedenceMap = [
		// [precedence, associativity] (-1 is %left, 0 is %nonassoc and 1 is %right)
		'**'  => [0, 1],
		'*'   => [40, -1],
		'/'   => [40, -1],
		'%'   => [40, -1],
		'+'   => [50, -1],
		'-'   => [50, -1],
		'.'   => [50, -1],
		'<<'  => [60, -1],
		'>>'  => [60, -1],
		'<'   => [70, 0],
		'<='  => [70, 0],
		'>'   => [70, 0],
		'>='  => [70, 0],
		'=='  => [80, 0],
		'!='  => [80, 0],
		'===' => [80, 0],
		'!==' => [80, 0],
		'<=>' => [80, 0],
		'&'   => [90, -1],
		'^'   => [100, -1],
		'|'   => [110, -1],
		'&&'  => [120, -1],
		'||'  => [130, -1],
		'??'  => [140, 1],
		'and' => [170, -1],
		'xor' => [180, -1],
		'or'  => [190, -1],
	];
	private int $counter = 0;

	/** @var Escaper[] */
	private array $escaperStack = [];


	public function __construct(string $contentType = ContentType::Html)
	{
		$this->escaperStack[] = new Escaper($contentType);
	}


	/**
	 * Expands %node, %dump, %raw, %args, %line, %escape(), %modify(), %modifyContent() in code.
	 */
	public function format(string $mask, mixed ...$args): string
	{
		$pos = 0; // enumerate arguments except for %escape
		$mask = preg_replace_callback(
			'#%([a-z]{3,})#i',
			function ($m) use (&$pos) {
				return $m[1] === 'escape'
					? '%0.escape'
					: '%' . ($pos++) . '.' . $m[1];
			},
			$mask,
		);

		$mask = preg_replace_callback(
			'#% (\d+) \. (escape|modify(?:Content)?) ( \( ([^()]*+|(?-2))+ \) )#xi',
			function ($m) use ($args) {
				[, $pos, $fn, $var] = $m;
				$var = substr($var, 1, -1);
				/** @var Nodes\FilterNode[] $args */
				return match ($fn) {
					'modify' => $args[$pos]->printSimple($this, $var),
					'modifyContent' => $args[$pos]->printContentAware($this, $var),
					'escape' => end($this->escaperStack)->escape($var),
				};
			},
			$mask,
		);

		return preg_replace_callback(
			'#([,+]?\s*)? % (\d+) \. ([a-z]{3,}) (\?)? (\s*\+\s*)? ()#xi',
			function ($m) use ($args) {
				[, $l, $pos, $format, $cond, $r] = $m;
				$arg = $args[$pos];

				$code = match ($format) {
					'dump' => PhpHelpers::dump($arg),
					'node' => $arg ? $arg->print($this) : '',
					'raw' => (string) $arg,
					'args' => $this->implode($arg instanceof Expression\ArrayNode ? $arg->toArguments() : $arg),
					'line' => $arg?->line ? "/* line $arg->line */" : '',
				};

				if ($cond && ($code === '[]' || $code === '')) {
					return $r ? $l : $r;
				}

				return $code === ''
					? trim($l) . $r
					: $l . $code . $r;
			},
			$mask,
		);
	}


	public function beginEscape(): Escaper
	{
		return $this->escaperStack[] = $this->getEscaper();
	}


	public function restoreEscape(): void
	{
		array_pop($this->escaperStack);
	}


	public function getEscaper(): Escaper
	{
		return clone end($this->escaperStack);
	}


	public function addBlock(Block $block): void
	{
		$block->escaping = $this->getEscaper()->export();
		$block->method = 'block' . ucfirst(trim(preg_replace('#\W+#', '_', $block->name->print($this)), '_'));
		$lower = strtolower($block->method);
		$used = $this->blocks + ['block' => 1];
		$counter = null;
		while (isset($used[$lower . $counter])) {
			$counter++;
		}

		$block->method .= $counter;
		$this->blocks[$lower . $counter] = $block;
	}


	public function generateId(): int
	{
		return $this->counter++;
	}


	// PHP helpers


	public function encodeString(string $str, string $quote = "'"): string
	{
		return $quote === "'"
			? "'" . addcslashes($str, "'\\") . "'"
			: '"' . addcslashes($str, "\n\r\t\f\v$\"\\") . '"';
	}


	/**
	 * Prints an infix operation while taking precedence into account.
	 */
	public function infixOp(Node $node, Node $leftNode, string $operatorString, Node $rightNode): string
	{
		[$precedence, $associativity] = $this->getPrecedence($node);
		return $this->prec($leftNode, $precedence, $associativity, -1)
			. $operatorString
			. $this->prec($rightNode, $precedence, $associativity, 1);
	}


	/**
	 * Prints a prefix operation while taking precedence into account.
	 */
	public function prefixOp(Node $node, string $operatorString, Node $expr): string
	{
		[$precedence, $associativity] = $this->getPrecedence($node);
		return $operatorString . $this->prec($expr, $precedence, $associativity, 1);
	}


	/**
	 * Prints a postfix operation while taking precedence into account.
	 */
	public function postfixOp(Node $node, Node $var, string $operatorString): string
	{
		[$precedence, $associativity] = $this->getPrecedence($node);
		return $this->prec($var, $precedence, $associativity, -1) . $operatorString;
	}


	/**
	 * Prints an expression node with the least amount of parentheses necessary to preserve the meaning.
	 */
	private function prec(Node $node, int $parentPrecedence, int $parentAssociativity, int $childPosition): string
	{
		$precedence = $this->getPrecedence($node);
		if ($precedence) {
			$childPrecedence = $precedence[0];
			if ($childPrecedence > $parentPrecedence
				|| ($parentPrecedence === $childPrecedence && $parentAssociativity !== $childPosition)
			) {
				return '(' . $node->print($this) . ')';
			}
		}

		return $node->print($this);
	}


	private function getPrecedence(Node $node): ?array
	{
		return $node instanceof Expression\BinaryOpNode
			? $this->binaryPrecedenceMap[$node->operator]
			: $this->exprPrecedenceMap[$node::class] ?? null;
	}


	/**
	 * Prints an array of nodes and implodes the printed values with $glue
	 */
	public function implode(array $nodes, string $glue = ', '): string
	{
		$pNodes = [];
		foreach ($nodes as $node) {
			if ($node === null) {
				$pNodes[] = '';
			} else {
				$pNodes[] = $node->print($this);
			}
		}

		return implode($glue, $pNodes);
	}


	public function objectProperty(Node $node): string
	{
		return $node instanceof Nodes\NameNode || $node instanceof Nodes\IdentifierNode
			? (string) $node
			: '{' . $node->print($this) . '}';
	}


	public function memberAsString(Node $node): string
	{
		return $node instanceof Nodes\NameNode || $node instanceof Nodes\IdentifierNode
			? $this->encodeString((string) $node)
			: $node->print($this);
	}


	/**
	 * Wraps the LHS of a call in parentheses if needed.
	 */
	public function callExpr(Node $expr): string
	{
		return $expr instanceof Nodes\NameNode
			|| $expr instanceof Expression\VariableNode
			|| $expr instanceof Expression\ArrayAccessNode
			|| $expr instanceof Expression\FunctionCallNode
			|| $expr instanceof Expression\FunctionCallableNode
			|| $expr instanceof Expression\MethodCallNode
			|| $expr instanceof Expression\MethodCallableNode
			|| $expr instanceof Expression\StaticCallNode
			|| $expr instanceof Expression\StaticCallableNode
			|| $expr instanceof Expression\ArrayNode
			? $expr->print($this)
			: '(' . $expr->print($this) . ')';
	}


	/**
	 * Wraps the LHS of a dereferencing operation in parentheses if needed.
	 */
	public function dereferenceExpr(Node $expr): string
	{
		return $expr instanceof Expression\VariableNode
			|| $expr instanceof Nodes\NameNode
			|| $expr instanceof Expression\ArrayAccessNode
			|| $expr instanceof Expression\PropertyFetchNode
			|| $expr instanceof Expression\StaticPropertyFetchNode
			|| $expr instanceof Expression\FunctionCallNode
			|| $expr instanceof Expression\FunctionCallableNode
			|| $expr instanceof Expression\MethodCallNode
			|| $expr instanceof Expression\MethodCallableNode
			|| $expr instanceof Expression\StaticCallNode
			|| $expr instanceof Expression\StaticCallableNode
			|| $expr instanceof Expression\ArrayNode
			|| $expr instanceof Scalar\StringNode
			|| $expr instanceof Scalar\BooleanNode
			|| $expr instanceof Scalar\NullNode
			|| $expr instanceof Expression\ConstantFetchNode
			|| $expr instanceof Expression\ClassConstantFetchNode
			? $expr->print($this)
			: '(' . $expr->print($this) . ')';
	}
}
