<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Sandbox\Nodes;

use Latte\Compiler\Nodes\Php\Expression\ArrayNode;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;


/**
 * {sandbox "file" [,] [params]}
 */
class SandboxNode extends StatementNode
{
	public ExpressionNode $file;
	public ArrayNode $args;


	public static function create(Tag $tag): static
	{
		$tag->outputMode = $tag::OutputRemoveIndentation;
		$tag->expectArguments();
		$node = new static;
		$node->file = $tag->parser->parseUnquotedStringOrExpression();
		$tag->parser->stream->tryConsume(',');
		$node->args = $tag->parser->parseArguments();
		return $node;
	}


	public function print(PrintContext $context): string
	{
		return $context->format(
			<<<'XX'
				ob_start(fn() => '');
				try {
					$this->createTemplate(%node, %node, 'sandbox')->renderToContentType(%dump) %line;
					echo ob_get_clean();
				} catch (\Throwable $ʟ_e) {
					if (isset($this->global->coreExceptionHandler)) {
						ob_end_clean();
						($this->global->coreExceptionHandler)($ʟ_e, $this);
					} else {
						echo ob_get_clean();
						throw $ʟ_e;
					}
				}


				XX,
			$this->file,
			$this->args,
			$context->getEscaper()->export(),
			$this->position,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->file;
		yield $this->args;
	}
}
