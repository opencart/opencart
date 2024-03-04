<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential;

use Latte;
use Latte\Compiler\NodeHelpers;
use Latte\Compiler\Nodes\Php;
use Latte\Compiler\Tag;
use Latte\Engine;
use Latte\Essential\Nodes\PrintNode;
use Nette\Localization\Translator;


/**
 * Extension for translations.
 */
final class TranslatorExtension extends Latte\Extension
{
	public function __construct(
		private /*?callable|Translator*/ $translator,
		private ?string $key = null,
	) {
		if ($translator instanceof Translator) {
			$this->translator = [$translator, 'translate'];
		}
	}


	public function getTags(): array
	{
		return [
			'_' => [$this, 'parseTranslate'],
			'translate' => fn(Tag $tag) => yield from Nodes\TranslateNode::create($tag, $this->key ? $this->translator : null),
		];
	}


	public function getFilters(): array
	{
		return [
			'translate' => fn(Latte\Runtime\FilterInfo $fi, ...$args): string => $this->translator
				? ($this->translator)(...$args)
				: $args[0],
		];
	}


	public function getCacheKey(Engine $engine): mixed
	{
		return $this->key;
	}


	/**
	 * {_ ...}
	 */
	public function parseTranslate(Tag $tag): PrintNode
	{
		$tag->outputMode = $tag::OutputKeepIndentation;
		$tag->expectArguments();
		$node = new PrintNode;
		$node->expression = $tag->parser->parseUnquotedStringOrExpression();
		$args = new Php\Expression\ArrayNode;
		if ($tag->parser->stream->tryConsume(',')) {
			$args = $tag->parser->parseArguments();
		}

		$node->modifier = $tag->parser->parseModifier();
		$node->modifier->escape = true;

		if ($this->translator
			&& $this->key
			&& ($expr = self::toValue($node->expression))
			&& is_array($values = self::toValue($args))
			&& is_string($translation = ($this->translator)($expr, ...$values))
		) {
			$node->expression = new Php\Scalar\StringNode($translation);
			return $node;
		}

		array_unshift($node->modifier->filters, new Php\FilterNode(new Php\IdentifierNode('translate'), $args->toArguments()));
		return $node;
	}


	public static function toValue($args): mixed
	{
		try {
			return NodeHelpers::toValue($args, constants: true);
		} catch (\InvalidArgumentException) {
			return null;
		}
	}
}
