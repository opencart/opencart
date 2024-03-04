<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\Compiler\Nodes\Php\Expression\ArrayNode;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\ModifierNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PhpHelpers;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;


/**
 * {include [file] "file" [with blocks] [,] [params]}
 */
class IncludeFileNode extends StatementNode
{
	public ExpressionNode $file;
	public ArrayNode $args;
	public ModifierNode $modifier;
	public string $mode;


	public static function create(Tag $tag): static
	{
		$tag->outputMode = $tag::OutputRemoveIndentation;

		$tag->expectArguments();
		$node = new static;
		$tag->parser->tryConsumeTokenBeforeUnquotedString('file');
		$node->file = $tag->parser->parseUnquotedStringOrExpression();
		$node->mode = 'include';

		$stream = $tag->parser->stream;
		if ($stream->tryConsume('with')) {
			$stream->consume('blocks');
			$node->mode = 'includeblock';
		}

		$stream->tryConsume(',');
		$node->args = $tag->parser->parseArguments();
		$node->modifier = $tag->parser->parseModifier();
		$node->modifier->escape = (bool) $node->modifier->filters;
		return $node;
	}


	public function print(PrintContext $context): string
	{
		$noEscape = $this->modifier->hasFilter('noescape');
		return $context->format(
			'$this->createTemplate(%node, %node? + $this->params, %dump)->renderToContentType(%raw) %line;',
			$this->file,
			$this->args,
			$this->mode,
			count($this->modifier->filters) > (int) $noEscape
				? $context->format(
					'function ($s, $type) { $ʟ_fi = new LR\FilterInfo($type); return %modifyContent($s); }',
					$this->modifier,
				)
				: PhpHelpers::dump($noEscape ? null : $context->getEscaper()->export()),
			$this->position,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->file;
		yield $this->args;
		yield $this->modifier;
	}
}
