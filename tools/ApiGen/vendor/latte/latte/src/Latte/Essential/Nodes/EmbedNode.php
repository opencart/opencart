<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\Php\Expression\ArrayNode;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\Scalar\StringNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\Nodes\TextNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Compiler\TemplateParser;


/**
 * {embed [block|file] name [,] [params]}
 */
class EmbedNode extends StatementNode
{
	public ExpressionNode $name;
	public string $mode;
	public ArrayNode $args;
	public FragmentNode $blocks;
	public int|string|null $layer;


	/** @return \Generator<int, ?array, array{FragmentNode, ?Tag}, static> */
	public static function create(Tag $tag, TemplateParser $parser): \Generator
	{
		if ($tag->isNAttribute()) {
			throw new CompileException('Attribute n:embed is not supported.', $tag->position);
		}

		$tag->outputMode = $tag::OutputRemoveIndentation;
		$tag->expectArguments();

		$node = new static;
		$mode = $tag->parser->tryConsumeTokenBeforeUnquotedString('block', 'file')?->text;
		$node->name = $tag->parser->parseUnquotedStringOrExpression();
		$node->mode = $mode ?? ($node->name instanceof StringNode && preg_match('~[\w-]+$~DA', $node->name->value) ? 'block' : 'file');
		$tag->parser->stream->tryConsume(',');
		$node->args = $tag->parser->parseArguments();

		$prevIndex = $parser->blockLayer;
		$parser->blockLayer = $node->layer = count($parser->blocks);
		$parser->blocks[$parser->blockLayer] = [];
		[$node->blocks] = yield;

		foreach ($node->blocks->children as $child) {
			if (!$child instanceof ImportNode && !$child instanceof BlockNode && !$child instanceof TextNode) {
				throw new CompileException('Unexpected content inside {embed} tags.', $child->position);
			}
		}

		$parser->blockLayer = $prevIndex;
		return $node;
	}


	public function print(PrintContext $context): string
	{
		$imports = '';
		foreach ($this->blocks->children as $child) {
			if ($child instanceof ImportNode) {
				$imports .= $child->print($context);
			} else {
				$child->print($context);
			}
		}

		return $this->mode === 'file'
			? $context->format(
				<<<'XX'
					$this->enterBlockLayer(%dump, get_defined_vars()) %line; %raw
					try {
						$this->createTemplate(%node, %node, "embed")->renderToContentType(%dump) %1.line;
					} finally {
						$this->leaveBlockLayer();
					}

					XX,
				$this->layer,
				$this->position,
				$imports,
				$this->name,
				$this->args,
				$context->getEscaper()->export(),
			)
			: $context->format(
				<<<'XX'
					$this->enterBlockLayer(%dump, get_defined_vars()) %line; %raw
					$this->copyBlockLayer();
					try {
						$this->renderBlock(%node, %node, %dump) %1.line;
					} finally {
						$this->leaveBlockLayer();
					}

					XX,
				$this->layer,
				$this->position,
				$imports,
				$this->name,
				$this->args,
				$context->getEscaper()->export(),
			);
	}


	public function &getIterator(): \Generator
	{
		yield $this->name;
		yield $this->args;
		yield $this->blocks;
	}
}
