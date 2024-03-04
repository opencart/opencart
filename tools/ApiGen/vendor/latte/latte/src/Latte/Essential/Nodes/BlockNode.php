<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\Block;
use Latte\Compiler\Escaper;
use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\Php\Expression\AssignNode;
use Latte\Compiler\Nodes\Php\Expression\VariableNode;
use Latte\Compiler\Nodes\Php\ModifierNode;
use Latte\Compiler\Nodes\Php\Scalar;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Compiler\TemplateParser;
use Latte\Compiler\Token;
use Latte\Runtime\Template;


/**
 * {block [local] [name]}
 */
class BlockNode extends StatementNode
{
	public ?Block $block = null;
	public ModifierNode $modifier;
	public AreaNode $content;


	/** @return \Generator<int, ?array, array{AreaNode, ?Tag}, static|AreaNode> */
	public static function create(Tag $tag, TemplateParser $parser): \Generator
	{
		$tag->outputMode = $tag::OutputRemoveIndentation;
		$stream = $tag->parser->stream;
		$node = new static;

		if (!$stream->is('|', Token::End)) {
			$layer = $tag->parser->tryConsumeTokenBeforeUnquotedString('local')
				? Template::LayerLocal
				: $parser->blockLayer;
			$stream->tryConsume('#');
			$name = $tag->parser->parseUnquotedStringOrExpression();
			$node->block = new Block($name, $layer, $tag);

			if (!$node->block->isDynamic()) {
				$parser->checkBlockIsUnique($node->block);
				$tag->data->block = $node->block; // for {include}
			}
		}

		$node->modifier = $tag->parser->parseModifier();
		$node->modifier->escape = (bool) $node->modifier->filters;
		if ($node->modifier->hasFilter('noescape') && count($node->modifier->filters) === 1) {
			throw new CompileException('Filter |noescape is not expected here.', $tag->position);
		}

		[$node->content, $endTag] = yield;

		if ($node->block && $endTag && $name instanceof Scalar\StringNode) {
			$endTag->parser->stream->tryConsume($name->value);
		}

		return $node;
	}


	public function print(PrintContext $context): string
	{
		if (!$this->block) {
			return $this->printFilter($context);

		} elseif ($this->block->isDynamic()) {
			return $this->printDynamic($context);
		}

		return $this->printStatic($context);
	}


	private function printFilter(PrintContext $context): string
	{
		return $context->format(
			<<<'XX'
				ob_start(fn() => '') %line;
				try {
					(function () { extract(func_get_arg(0));
						%node
					})(get_defined_vars());
				} finally {
					$ʟ_fi = new LR\FilterInfo(%dump);
					echo %modifyContent(ob_get_clean());
				}

				XX,
			$this->position,
			$this->content,
			$context->getEscaper()->export(),
			$this->modifier,
		);
	}


	private function printStatic(PrintContext $context): string
	{
		$this->modifier->escape = $this->modifier->escape || $context->getEscaper()->getState() === Escaper::HtmlAttribute;
		$context->addBlock($this->block);
		$this->block->content = $this->content->print($context); // must be compiled after is added

		return $context->format(
			'$this->renderBlock(%node, get_defined_vars()'
			. ($this->modifier->filters || $this->modifier->escape
				? ', function ($s, $type) { $ʟ_fi = new LR\FilterInfo($type); return %modifyContent($s); }'
				: '')
			. ') %2.line;',
			$this->block->name,
			$this->modifier,
			$this->position,
		);
	}


	private function printDynamic(PrintContext $context): string
	{
		$context->addBlock($this->block);
		$this->block->content = $this->content->print($context); // must be compiled after is added
		$escaper = $context->getEscaper();
		$this->modifier->escape = $this->modifier->escape || $escaper->getState() === Escaper::HtmlAttribute;

		return $context->format(
			'$this->addBlock(%node, %dump, [[$this, %dump]], %dump);
			$this->renderBlock($ʟ_nm, get_defined_vars()'
			. ($this->modifier->filters || $this->modifier->escape
				? ', function ($s, $type) { $ʟ_fi = new LR\FilterInfo($type); return %modifyContent($s); }'
				: '')
			. ');',
			new AssignNode(new VariableNode('ʟ_nm'), $this->block->name),
			$escaper->export(),
			$this->block->method,
			$this->block->layer,
			$this->modifier,
		);
	}


	public function &getIterator(): \Generator
	{
		if ($this->block) {
			yield $this->block->name;
		}
		yield $this->modifier;
		yield $this->content;
	}
}
