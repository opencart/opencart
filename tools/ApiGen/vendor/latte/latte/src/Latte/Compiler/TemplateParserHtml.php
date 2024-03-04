<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte;
use Latte\CompileException;
use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\Html;
use Latte\ContentType;
use Latte\Helpers;
use Latte\SecurityViolationException;


/**
 * Template parser extension for HTML.
 */
final class TemplateParserHtml
{
	use Latte\Strict;

	/** @var array<string, callable(Tag, TemplateParser): (Node|\Generator|void)> */
	private array /*readonly*/ $attrParsers;
	private ?Html\ElementNode $element = null;
	private TemplateParser /*readonly*/ $parser;


	public function __construct(TemplateParser $parser, array $attrParsers)
	{
		$this->parser = $parser;
		$this->attrParsers = $attrParsers;
	}


	public function getElement(): ?Html\ElementNode
	{
		return $this->element;
	}


	public function inTextResolve(): ?Node
	{
		$stream = $this->parser->getStream();
		$token = $stream->peek();
		return match ($token->type) {
			Token::Html_TagOpen => $stream->peek(1)?->is(Token::Slash)
				? $this->parseEndTag()
				: $this->parseElement(),
			Token::Html_CommentOpen => $this->parseComment(),
			Token::Html_BogusOpen => $this->parseBogusTag(),
			default => $this->parser->inTextResolve(),
		};
	}


	public function inTagResolve(): ?Node
	{
		$stream = $this->parser->getStream();
		$token = $stream->peek();
		return match ($token->type) {
			Token::Html_Name => str_starts_with($token->text, TemplateLexer::NPrefix)
				? $this->parseNAttribute()
				: $this->parseAttribute(),
			Token::Latte_TagOpen => $this->parseAttribute(),
			Token::Whitespace => $this->parseAttributeWhitespace(),
			Token::Html_TagClose => null,
			default => $this->parser->inTextResolve(),
		};
	}


	private function parseElement(): Node
	{
		$res = new FragmentNode;
		$res->append($this->extractIndentation());
		$res->append($this->parseTag($this->element));
		$elem = $this->element;

		$stream = $this->parser->getStream();
		$void = $this->resolveVoidness($elem);
		$attrs = $this->prepareNAttrs($elem->nAttributes, $void);
		$outerNodes = $this->openNAttrNodes($attrs[Tag::PrefixNone] ?? []);
		$tagNodes = $this->openNAttrNodes($attrs[Tag::PrefixTag] ?? []);
		$elem->tagNode = $this->finishNAttrNodes($elem->tagNode, $tagNodes);
		$elem->captureTagName = (bool) $tagNodes;

		if (!$void) {
			$content = new FragmentNode;
			if ($token = $stream->tryConsume(Token::Newline)) {
				$content->append(new Nodes\TextNode($token->text, $token->position));
			}

			$innerNodes = $this->openNAttrNodes($attrs[Tag::PrefixInner] ?? []);
			$elem->data->tag = $this->parser->peekTag();
			$frag = $this->parser->parseFragment([$this, 'inTextResolve']);
			$content->append($this->finishNAttrNodes($frag, $innerNodes));

			if ($this->isClosingTag($elem->name)) {
				$elem->content = $content;
				$elem->content->append($this->extractIndentation());
				$this->parseTag();

			} elseif ($outerNodes || $innerNodes || $tagNodes
				|| ($this->parser->getContentType() === ContentType::Html && in_array(strtolower($elem->name), ['script', 'style'], true))
			) {
				$stream->throwUnexpectedException(
					addendum: ", expecting </{$elem->name}> for element started " . $elem->position->toWords(),
				);
			} else { // element collapsed to tags
				$res->append($content);
				$this->element = $elem->parent;
				if ($this->element && !$stream->is(Token::Html_TagOpen)) {
					$this->element->data->unclosedTags[] = $elem->name;
				}
				return $res;
			}
		}

		if ($token = $stream->tryConsume(Token::Newline)) {
			$res->append(new Nodes\TextNode($token->text, $token->position));
		}

		$res = $this->finishNAttrNodes($res, $outerNodes);
		$this->element = $elem->parent;
		return $res;
	}


	private function extractIndentation(): AreaNode
	{
		if ($this->parser->lastIndentation) {
			$dolly = clone $this->parser->lastIndentation;
			$this->parser->lastIndentation->content = '';
			return $dolly;
		} else {
			return new Nodes\NopNode;
		}
	}


	private function parseTag(&$elem = null): Html\ElementNode
	{
		$stream = $this->parser->getStream();
		$openToken = $stream->consume(Token::Html_TagOpen);
		$stream->tryConsume(Token::Slash);
		$this->parser->lastIndentation = null;
		$this->parser->location = $this->parser::LocationTag;
		$elem = new Html\ElementNode(
			name: $stream->consume(Token::Html_Name)->text,
			position: $openToken->position,
			parent: $this->element,
			data: (object) ['tag' => $this->parser->peekTag()],
		);
		$elem->attributes = $this->parser->parseFragment([$this, 'inTagResolve']);
		$elem->selfClosing = (bool) $stream->tryConsume(Token::Slash);
		$stream->consume(Token::Html_TagClose);
		$this->parser->location = $this->parser::LocationText;
		return $elem;
	}


	private function parseEndTag(): ?Html\BogusTagNode
	{
		$stream = $this->parser->getStream();
		$name = $stream->peek(2)->text ?? '';

		if ($this->element
			&& $this->parser->peekTag() === $this->element->data->tag
			&& (strcasecmp($name, $this->element->name) === 0
				|| !in_array($name, $this->element->data->unclosedTags ?? [], true))
		) {
			return null; // go back to parseElement()
		}

		$openToken = $stream->consume(Token::Html_TagOpen);
		$this->parser->lastIndentation = null;
		$this->parser->location = $this->parser::LocationTag;
		$node = new Html\BogusTagNode(
			openDelimiter: $openToken->text . $stream->consume(Token::Slash)->text . $stream->tryConsume(Token::Text)?->text,
			content: $this->parser->parseFragment([$this, 'inTagResolve']),
			endDelimiter: $stream->consume(Token::Html_TagClose)->text,
			position: $openToken->position,
		);
		$this->parser->location = $this->parser::LocationText;
		return $node;
	}


	private function parseBogusTag(): Html\BogusTagNode
	{
		$stream = $this->parser->getStream();
		$openToken = $stream->consume(Token::Html_BogusOpen);
		$this->parser->lastIndentation = null;
		$this->parser->location = $this->parser::LocationTag;
		$content = $this->parser->parseFragment(fn() => match ($stream->peek()->type) {
			Token::Html_TagClose => null,
			default => $this->parser->inTextResolve(),
		});
		$this->parser->location = $this->parser::LocationText;
		return new Html\BogusTagNode(
			openDelimiter: $openToken->text,
			content: $content,
			endDelimiter: $stream->consume(Token::Html_TagClose)->text,
			position: $openToken->position,
		);
	}


	private function resolveVoidness(Html\ElementNode $elem): bool
	{
		if ($this->parser->getContentType() !== ContentType::Html) {
			return $elem->selfClosing;
		} elseif (isset(Helpers::$emptyElements[strtolower($elem->name)])) {
			return true;
		} elseif ($elem->selfClosing) { // auto-correct
			$elem->content = new Nodes\NopNode;
			$elem->selfClosing = false;
			$last = end($elem->attributes->children);
			if ($last instanceof Nodes\TextNode && $last->isWhitespace()) {
				array_pop($elem->attributes->children);
			}
			return true;
		}

		return $elem->selfClosing;
	}


	private function parseAttributeWhitespace(): Node
	{
		$stream = $this->parser->getStream();
		$token = $stream->consume(Token::Whitespace);
		return $stream->is(Token::Html_Name) && str_starts_with($stream->peek()->text, TemplateLexer::NPrefix)
			? new Nodes\NopNode
			: new Nodes\TextNode($token->text, $token->position);
	}


	private function parseAttribute(): ?Node
	{
		$stream = $this->parser->getStream();
		$followsLatte = $stream->is(Token::Latte_TagOpen);
		$save = $stream->getIndex();
		try {
			$name = $this->parser->parseFragment(fn() => match ($stream->peek()->type) {
				Token::Html_Name => $this->parser->parseText(),
				Token::Latte_TagOpen => $this->parser->parseLatteStatement(),
				Token::Latte_CommentOpen => $this->parser->parseLatteComment(),
				default => null,
			});
		} catch (CompileException $e) {
			if ($followsLatte // attribute name together with the value inside the tag
				&& $stream->peek() // it is not lexer exception
			) {
				$stream->seek($save);
				return $this->parser->parseLatteStatement();
			}
			throw $e;
		}

		if (!$name->children) {
			return null;
		} elseif (count($name->children) === 1 && $name->children[0] instanceof Nodes\TextNode) {
			$name = $name->children[0];
		}

		$save = $stream->getIndex();
		$this->consumeIgnored();
		if ($stream->tryConsume(Token::Equals)) {
			$this->consumeIgnored();
			$value = match ($stream->peek()->type) {
				Token::Quote => $this->parseAttributeQuote(),
				Token::Html_Name => $this->parser->parseText(),
				Token::Latte_TagOpen => $this->parser->parseFragment(
					function (FragmentNode $fragment) use ($stream) {
						if ($fragment->children) {
							return null;
						}
						return match ($stream->peek()->type) {
							Token::Quote => $this->parseAttributeQuote(),
							Token::Html_Name => $this->parser->parseText(),
							Token::Latte_TagOpen => $this->parser->parseLatteStatement(),
							Token::Latte_CommentOpen => $this->parser->parseLatteComment(),
							default => null,
						};
					},
				),
				default => null,
			}
			?? $stream->throwUnexpectedException();

		} else {
			$stream->seek($save);
			$value = null;
		}

		return new Html\AttributeNode(
			name: $name,
			value: $value,
			position: $name->position,
		);
	}


	private function parseNAttribute(): Nodes\TextNode
	{
		$stream = $this->parser->getStream();
		$nameToken = $stream->consume(Token::Html_Name);
		$save = $stream->getIndex();
		$pos = $stream->peek()->position;
		$name = substr($nameToken->text, strlen(TemplateLexer::NPrefix));
		if ($this->parser->peekTag() !== $this->element->data->tag) {
			throw new CompileException("Attribute n:$name must not appear inside {tags}", $nameToken->position);

		} elseif (isset($this->element->nAttributes[$name])) {
			throw new CompileException("Found multiple attributes n:$name.", $nameToken->position);
		}

		$this->consumeIgnored();
		if ($stream->tryConsume(Token::Equals)) {
			$this->consumeIgnored();
			if ($stream->tryConsume(Token::Quote)) {
				$valueToken = $stream->tryConsume(Token::Text);
				$pos = $stream->peek()->position;
				$stream->consume(Token::Quote);
			} else {
				$valueToken = $stream->consume(Token::Html_Name);
			}
			if ($valueToken) {
				$tokens = (new TagLexer)->tokenize($valueToken->text, $valueToken->position);
			}
		} else {
			$stream->seek($save);
		}
		$tokens ??= [new Token(Token::End, '', $pos)];

		$this->element->nAttributes[$name] = new Tag(
			name: preg_replace('~(inner-|tag-|)~', '', $name),
			tokens: $tokens,
			position: $nameToken->position,
			prefix: $this->getPrefix($name),
			location: $this->parser->location,
			htmlElement: $this->element,
			data: (object) ['node' => $node = new Nodes\TextNode('')], // TODO: better
		);
		return $node;
	}


	private function parseAttributeQuote(): Html\QuotedValue
	{
		$stream = $this->parser->getStream();
		$quoteToken = $stream->consume(Token::Quote);
		$value = $this->parser->parseFragment(fn() => match ($stream->peek()->type) {
			Token::Quote => null,
			default => $this->parser->inTextResolve(),
		});
		$node = new Html\QuotedValue(
			value: $value,
			quote: $quoteToken->text,
			position: $quoteToken->position,
		);
		$stream->consume(Token::Quote);
		return $node;
	}


	private function parseComment(): Html\CommentNode
	{
		$this->parser->lastIndentation = null;
		$this->parser->location = $this->parser::LocationTag;
		$stream = $this->parser->getStream();
		$node = new Html\CommentNode(
			position: $stream->consume(Token::Html_CommentOpen)->position,
			content: $this->parser->parseFragment(fn() => match ($stream->peek()->type) {
				Token::Html_CommentClose => null,
				default => $this->parser->inTextResolve(),
			}),
		);
		$stream->consume(Token::Html_CommentClose);
		$this->parser->location = $this->parser::LocationText;
		return $node;
	}


	private function consumeIgnored(): void
	{
		$stream = $this->parser->getStream();
		do {
			if ($stream->tryConsume(Token::Whitespace)) {
				continue;
			}
			if ($stream->tryConsume(Token::Latte_CommentOpen)) {
				$stream->consume(Token::Text);
				$stream->consume(Token::Latte_CommentClose);
				$stream->tryConsume(Token::Newline);
				continue;
			}
			return;
		} while (true);
	}


	private function isClosingTag(string $name): bool
	{
		$stream = $this->parser->getStream();
		return $stream->is(Token::Html_TagOpen)
			&& $stream->peek(1)->is(Token::Slash)
			&& strcasecmp($name, $stream->peek(2)->text ?? '') === 0;
	}


	private function prepareNAttrs(array $attrs, bool $void): array
	{
		$res = [];
		foreach ($this->attrParsers as $name => $foo) {
			if ($tag = $attrs[$name] ?? null) {
				$prefix = $this->getPrefix($name);
				if (!$prefix || !$void) {
					$res[$prefix][] = $tag;
					unset($attrs[$name]);
				}
			}
		}

		if ($attrs) {
			$hint = Helpers::getSuggestion(array_keys($this->attrParsers), $k = key($attrs));
			throw new CompileException('Unexpected attribute n:'
				. ($hint ? "$k, did you mean n:$hint?" : implode(' and n:', array_keys($attrs))), $attrs[$k]->position);
		}

		return $res;
	}


	/**
	 * @param  array<Tag>  $toOpen
	 * @return array<array{\Generator, Tag}>
	 */
	private function openNAttrNodes(array $toOpen): array
	{
		$toClose = [];
		foreach ($toOpen as $tag) {
			$parser = $this->getAttrParser($tag->name, $tag->position);
			$this->parser->pushTag($tag);
			$res = $parser($tag, $this->parser);
			if ($res instanceof \Generator && $res->valid()) {
				$toClose[] = [$res, $tag];

			} elseif ($res instanceof Node) {
				$this->parser->ensureIsConsumed($tag);
				$res->position = $tag->position;
				$tag->replaceNAttribute($res);
				$this->parser->popTag();

			} elseif (!$res) {
				$this->parser->ensureIsConsumed($tag);
				$this->parser->popTag();

			} else {
				throw new CompileException("Unexpected value returned by {$tag->getNotation()} parser.", $tag->position);
			}
		}

		return $toClose;
	}


	/** @param  array<array{\Generator, Tag}>  $toClose */
	private function finishNAttrNodes(AreaNode $node, array $toClose): AreaNode
	{
		while ([$gen, $tag] = array_pop($toClose)) {
			$gen->send([$node, null]);
			$node = $gen->getReturn();
			$node->position = $tag->position;
			$this->parser->popTag();
			$this->parser->ensureIsConsumed($tag);
		}

		return $node;
	}


	/** @return callable(Tag, TemplateParser): (Node|\Generator|void) */
	private function getAttrParser(string $name, Position $pos): callable
	{
		if (!isset($this->attrParsers[$name])) {
			$hint = ($t = Helpers::getSuggestion(array_keys($this->attrParsers), $name))
				? ", did you mean n:$t?"
				: '';
			throw new CompileException("Unknown n:{$name}{$hint}", $pos);
		} elseif (!$this->parser->isTagAllowed($name)) {
			throw new SecurityViolationException("Attribute n:$name is not allowed", $pos);
		}
		return $this->attrParsers[$name];
	}


	private function getPrefix(string $name): string
	{
		return match (true) {
			str_starts_with($name, 'inner-') => Tag::PrefixInner,
			str_starts_with($name, 'tag-') => Tag::PrefixTag,
			default => Tag::PrefixNone,
		};
	}
}
