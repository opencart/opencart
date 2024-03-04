<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte;
use Latte\CompileException;
use Latte\Compiler\Nodes\Html\ElementNode;


/**
 * Latte tag or n:attribute.
 */
final class Tag
{
	use Latte\Strict;

	public const
		PrefixInner = 'inner',
		PrefixTag = 'tag',
		PrefixNone = '';

	public const
		OutputNone = 0,
		OutputKeepIndentation = 1,
		OutputRemoveIndentation = 2;

	public TagParser $parser;
	public int $outputMode = self::OutputNone;


	public function __construct(
		public /*readonly*/ string $name,
		array $tokens,
		public /*readonly*/ Position $position,
		public /*readonly*/ bool $void = false,
		public /*readonly*/ bool $closing = false,
		public /*readonly*/ int $location = 0,
		public /*readonly*/ ?ElementNode $htmlElement = null,
		public ?self $parent = null,
		public /*readonly*/ ?string $prefix = null,
		public ?\stdClass $data = null,
	) {
		$this->data ??= new \stdClass;
		$this->parser = new TagParser($tokens);
	}


	public function isInHead(): bool
	{
		return $this->location === TemplateParser::LocationHead && !$this->parent;
	}


	public function isInText(): bool
	{
		return $this->location <= TemplateParser::LocationText;
	}


	public function isNAttribute(): bool
	{
		return $this->prefix !== null;
	}


	public function getNotation(bool $withArgs = false): string
	{
		return $this->isNAttribute()
			? TemplateLexer::NPrefix . ($this->prefix ? $this->prefix . '-' : '')
				. $this->name
				. ($withArgs ? '="' . $this->parser->text . '"' : '')
			: '{'
				. ($this->closing ? '/' : '')
				. $this->name
				. ($withArgs ? $this->parser->text : '')
			. '}';
	}


	/**
	 * @param  string[]  $names
	 */
	public function closestTag(array $names, ?callable $condition = null): ?self
	{
		$tag = $this->parent;
		while ($tag && (
			!in_array($tag->name, $names, true)
			|| ($condition && !$condition($tag))
		)) {
			$tag = $tag->parent;
		}

		return $tag;
	}


	public function expectArguments(string $what = 'arguments'): void
	{
		if ($this->parser->isEnd()) {
			throw new CompileException("Missing $what in " . $this->getNotation(), $this->position);
		}
	}


	public function replaceNAttribute(Node $node): void
	{
		$index = array_search($this->data->node, $this->htmlElement->attributes->children, true);
		$this->htmlElement->attributes->children[$index] = $node;
	}
}
