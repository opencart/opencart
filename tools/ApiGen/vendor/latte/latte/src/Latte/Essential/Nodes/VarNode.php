<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\Compiler\Nodes\Php\Expression\AssignNode;
use Latte\Compiler\Nodes\Php\Expression\VariableNode;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\Scalar\NullNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Compiler\Token;


/**
 * {var [type] $var = value, ...}
 * {default [type] $var = value, ...}
 */
class VarNode extends StatementNode
{
	public bool $default;

	/** @var AssignNode[] */
	public array $assignments = [];


	public static function create(Tag $tag): static
	{
		$tag->expectArguments();
		$node = new static;
		$node->default = $tag->name === 'default';
		$node->assignments = self::parseAssignments($tag, $node->default);
		return $node;
	}


	private static function parseAssignments(Tag $tag, bool $default): array
	{
		$stream = $tag->parser->stream;
		$res = [];
		do {
			$tag->parser->parseType();

			$save = $stream->getIndex();
			$expr = $stream->is(Token::Php_Variable) ? $tag->parser->parseExpression() : null;
			if ($expr instanceof VariableNode) {
				$res[] = new AssignNode($expr, new NullNode);
			} elseif ($expr instanceof AssignNode && (!$default || $expr->var instanceof VariableNode)) {
				$res[] = $expr;
			} else {
				$stream->seek($save);
				$stream->throwUnexpectedException(addendum: ' in ' . $tag->getNotation());
			}
		} while ($stream->tryConsume(',') && !$stream->peek()->isEnd());

		return $res;
	}


	public function print(PrintContext $context): string
	{
		$res = [];
		if ($this->default) {
			foreach ($this->assignments as $assign) {
				assert($assign->var instanceof VariableNode);
				if ($assign->var->name instanceof ExpressionNode) {
					$var = $assign->var->name->print($context);
				} else {
					$var = $context->encodeString($assign->var->name);
				}
				$res[] = $var . ' => ' . $assign->expr->print($context);
			}

			return $context->format(
				'extract([%raw], EXTR_SKIP) %line;',
				implode(', ', $res),
				$this->position,
			);
		}

		foreach ($this->assignments as $assign) {
			$res[] = $assign->print($context);
		}

		return $context->format(
			'%raw %line;',
			implode('; ', $res),
			$this->position,
		);
	}


	public function &getIterator(): \Generator
	{
		foreach ($this->assignments as &$assign) {
			yield $assign;
		}
	}
}
