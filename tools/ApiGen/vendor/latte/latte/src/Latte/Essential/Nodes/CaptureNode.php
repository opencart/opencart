<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\Escaper;
use Latte\Compiler\Node;
use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\Php\Expression;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\ModifierNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;


/**
 * {capture $variable}
 */
class CaptureNode extends StatementNode
{
	public ExpressionNode $variable;
	public ModifierNode $modifier;
	public AreaNode $content;


	/** @return \Generator<int, ?array, array{AreaNode, ?Tag}, static> */
	public static function create(Tag $tag): \Generator
	{
		$tag->expectArguments();
		$variable = $tag->parser->parseExpression();
		if (!self::canBeAssignedTo($variable)) {
			$text = '';
			$i = 0;
			while ($token = $tag->parser->stream->peek(--$i)) {
				$text = $token->text . $text;
			}

			throw new CompileException("It is not possible to write into '$text' in " . $tag->getNotation(), $tag->position);
		}
		$node = new static;
		$node->variable = $variable;
		$node->modifier = $tag->parser->parseModifier();
		[$node->content] = yield;
		return $node;
	}


	public function print(PrintContext $context): string
	{
		$escaper = $context->getEscaper();
		return $context->format(
			<<<'XX'
				ob_start(fn() => '') %line;
				try {
					%node
				} finally {
					$ʟ_tmp = %raw;
				}
				$ʟ_fi = new LR\FilterInfo(%dump); %node = %modifyContent($ʟ_tmp);


				XX,
			$this->position,
			$this->content,
			$escaper->getState() === Escaper::HtmlText
				? 'ob_get_length() ? new LR\Html(ob_get_clean()) : ob_get_clean()'
				: 'ob_get_clean()',
			$escaper->export(),
			$this->variable,
			$this->modifier,
		);
	}


	private static function canBeAssignedTo(Node $node): bool
	{
		return $node instanceof Expression\VariableNode
			|| $node instanceof Expression\ArrayAccessNode
			|| $node instanceof Expression\PropertyFetchNode
			|| $node instanceof Expression\StaticPropertyFetchNode;
	}


	public function &getIterator(): \Generator
	{
		yield $this->variable;
		yield $this->modifier;
		yield $this->content;
	}
}
