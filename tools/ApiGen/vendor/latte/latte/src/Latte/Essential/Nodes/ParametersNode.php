<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\Nodes\Php\Expression\AssignNode;
use Latte\Compiler\Nodes\Php\Expression\VariableNode;
use Latte\Compiler\Nodes\Php\ParameterNode;
use Latte\Compiler\Nodes\Php\Scalar\NullNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Compiler\Token;


/**
 * {parameters [type] $var, ...}
 */
class ParametersNode extends StatementNode
{
	/** @var ParameterNode[] */
	public array $parameters = [];


	public static function create(Tag $tag): static
	{
		if (!$tag->isInHead()) {
			throw new CompileException('{parameters} is allowed only in template header.', $tag->position);
		}
		$tag->expectArguments();
		$node = new static;
		$node->parameters = self::parseParameters($tag);
		return $node;
	}


	private static function parseParameters(Tag $tag): array
	{
		$stream = $tag->parser->stream;
		$params = [];
		do {
			$type = $tag->parser->parseType();

			$save = $stream->getIndex();
			$expr = $stream->is(Token::Php_Variable) ? $tag->parser->parseExpression() : null;
			if ($expr instanceof VariableNode && is_string($expr->name)) {
				$params[] = new ParameterNode($expr, new NullNode, $type);
			} elseif (
				$expr instanceof AssignNode
				&& $expr->var instanceof VariableNode
				&& is_string($expr->var->name)
			) {
				$params[] = new ParameterNode($expr->var, $expr->expr, $type);
			} else {
				$stream->seek($save);
				$stream->throwUnexpectedException(addendum: ' in ' . $tag->getNotation());
			}
		} while ($stream->tryConsume(',') && !$stream->peek()->isEnd());

		return $params;
	}


	public function print(PrintContext $context): string
	{
		$context->paramsExtraction = $this->parameters;
		return '';
	}


	public function &getIterator(): \Generator
	{
		foreach ($this->parameters as &$param) {
			yield $param;
		}
	}
}
