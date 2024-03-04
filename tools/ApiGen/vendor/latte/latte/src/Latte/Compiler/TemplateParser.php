<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte;
use Latte\CompileException;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\ContentType;
use Latte\Helpers;
use Latte\Policy;
use Latte\Runtime\Template;
use Latte\SecurityViolationException;


final class TemplateParser
{
	use Latte\Strict;

	public const
		LocationHead = 1,
		LocationText = 2,
		LocationTag = 3;

	/** @var Block[][] */
	public array $blocks = [[]];
	public int $blockLayer = Template::LayerTop;
	public int $location = self::LocationHead;
	public ?Nodes\TextNode $lastIndentation = null;

	/** @var array<string, callable(Tag, self): (Node|\Generator|void)> */
	private array $tagParsers = [];

	/** @var array<string, \stdClass> */
	private array $attrParsersInfo = [];

	private TemplateParserHtml $html;
	private ?TokenStream $stream = null;
	private ?TemplateLexer $lexer = null;
	private ?Policy $policy = null;
	private string $contentType = ContentType::Html;
	private int $counter = 0;
	private ?Tag $tag = null;
	private $lastResolver;


	/**
	 * Parses tokens to nodes.
	 * @throws CompileException
	 */
	public function parse(string $template, TemplateLexer $lexer): Nodes\TemplateNode
	{
		$this->lexer = $lexer;
		$this->html = new TemplateParserHtml($this, $this->completeAttrParsers());
		$this->stream = new TokenStream($lexer->tokenize($template, $this->contentType));

		$headLength = 0;
		$findLength = function (FragmentNode $fragment) use (&$headLength) {
			if ($this->location === self::LocationHead && !end($fragment->children) instanceof Nodes\TextNode) {
				$headLength = count($fragment->children);
			}
		};

		$node = new Nodes\TemplateNode;
		$node->main = $this->parseFragment([$this->html, 'inTextResolve'], $findLength);
		$node->head = new FragmentNode(array_splice($node->main->children, 0, $headLength));
		$node->contentType = $this->contentType;

		if (!$this->stream->peek()->isEnd()) {
			$this->stream->throwUnexpectedException();
		}

		return $node;
	}


	public function parseFragment(callable $resolver, callable $after = null): FragmentNode
	{
		$res = new FragmentNode;
		$save = [$this->lastResolver, $this->tag];
		$this->lastResolver = $resolver;
		try {
			while (!$this->stream->peek()->isEnd()) {
				if ($node = $resolver($res)) {
					$res->append($node);
					$after && $after($res);
				} else {
					break;
				}
			}

			return $res;
		} finally {
			[$this->lastResolver, $this->tag] = $save;
		}
	}


	public function inTextResolve(): ?Node
	{
		$token = $this->stream->peek();
		return match ($token->type) {
			Token::Text => $this->parseText(),
			Token::Indentation => $this->parseIndentation(),
			Token::Newline => $this->parseNewline(),
			Token::Latte_TagOpen => $this->parseLatteStatement(),
			Token::Latte_CommentOpen => $this->parseLatteComment(),
			default => null,
		};
	}


	public function parseText(): Nodes\TextNode
	{
		$token = $this->stream->consume(Token::Text, Token::Html_Name);
		if ($this->location === self::LocationHead && trim($token->text) !== '') {
			$this->location = self::LocationText;
		}
		$this->lastIndentation = null;
		return new Nodes\TextNode($token->text, $token->position);
	}


	private function parseIndentation(): Nodes\TextNode
	{
		$token = $this->stream->consume(Token::Indentation);
		return $this->lastIndentation = new Nodes\TextNode($token->text, $token->position);
	}


	private function parseNewline(): Nodes\AreaNode
	{
		$token = $this->stream->consume(Token::Newline);
		if ($this->lastIndentation) { // drop indentation & newline
			$this->lastIndentation->content = '';
			$this->lastIndentation = null;
			return new Nodes\NopNode;
		} else {
			return new Nodes\TextNode($token->text, $token->position);
		}
	}


	public function parseLatteComment(): Nodes\NopNode
	{
		if (str_ends_with($this->stream->peek(-1)?->text ?? "\n", "\n")) {
			$this->lastIndentation ??= new Nodes\TextNode('');
		}
		$this->stream->consume(Token::Latte_CommentOpen);
		$this->stream->consume(Token::Text);
		$this->stream->consume(Token::Latte_CommentClose);
		return new Nodes\NopNode;
	}


	public function parseLatteStatement(): ?Node
	{
		if ($this->stream->peek(1)->is(Token::Slash)
			|| isset($this->tag->data->filters) && in_array($this->stream->peek(1)->text, $this->tag->data->filters, true)
		) {
			return null; // go back to previous parseLatteStatement()
		}

		$token = $this->stream->peek();
		$startTag = $this->pushTag($this->parseLatteTag());

		$parser = $this->getTagParser($startTag->name, $token->position);
		$res = $parser($startTag, $this);
		if ($res instanceof \Generator) {
			if (!$res->valid() && !$startTag->void) {
				throw new \LogicException("Incorrect behavior of {{$startTag->name}} parser, yield call is expected (on line {$startTag->position->line})");
			}

			$this->ensureIsConsumed($startTag);
			if ($startTag->outputMode === $startTag::OutputKeepIndentation) {
				$this->lastIndentation = null;
			}

			if ($startTag->void) {
				$res->send([new FragmentNode, $startTag]);
			} else {
				while ($res->valid()) {
					$startTag->data->filters = $res->current() ?: null;
					$content = $this->parseFragment($this->lastResolver);

					if (!$this->stream->is(Token::Latte_TagOpen)) {
						$this->checkEndTag($startTag, null);
						$res->send([$content, null]);
						break;
					}

					$tag = $this->parseLatteTag();

					if ($startTag->outputMode === $startTag::OutputKeepIndentation) {
						$this->lastIndentation = null;
					}

					if ($tag->closing) {
						$this->checkEndTag($startTag, $tag);
						$res->send([$content, $tag]);
						$this->ensureIsConsumed($tag);
						break;
					} elseif (in_array($tag->name, $startTag->data->filters ?? [], true)) {
						$this->pushTag($tag);
						$res->send([$content, $tag]);
						$this->ensureIsConsumed($tag);
						$this->popTag();
					} else {
						throw new CompileException('Unexpected tag ' . substr($tag->getNotation(true), 0, -1) . '}', $tag->position);
					}
				}
			}

			if ($res->valid()) {
				throw new \LogicException("Incorrect behavior of {{$startTag->name}} parser, more yield calls than expected (on line {$startTag->position->line})");
			}

			$node = $res->getReturn();

		} elseif ($startTag->void) {
			throw new CompileException('Unexpected /} in tag ' . substr($startTag->getNotation(true), 0, -1) . '/}', $startTag->position);

		} else {
			$this->ensureIsConsumed($startTag);
			$node = $res;
			if ($startTag->outputMode === $startTag::OutputKeepIndentation) {
				$this->lastIndentation = null;
			}
		}

		if (!$node instanceof Node) {
			throw new \LogicException("Incorrect behavior of {{$startTag->name}} parser, unexpected returned value (on line {$startTag->position->line})");
		}

		if ($this->location === self::LocationHead && $startTag->outputMode !== $startTag::OutputNone) {
			$this->location = self::LocationText;
		}

		$this->popTag();

		$node->position = $startTag->position;
		return $node;
	}


	private function parseLatteTag(): Tag
	{
		$stream = $this->stream;
		if (str_ends_with($stream->peek(-1)?->text ?? "\n", "\n")) {
			$this->lastIndentation ??= new Nodes\TextNode('');
		}

		$openToken = $stream->consume(Token::Latte_TagOpen);
		$tag = new Tag(
			position: $openToken->position,
			closing: $closing = (bool) $stream->tryConsume(Token::Slash),
			name: $stream->tryConsume(Token::Latte_Name)?->text ?? ($closing ? '' : '='),
			tokens: $this->consumeTag(),
			void: (bool) $stream->tryConsume(Token::Slash),
			location: $this->location,
			htmlElement: $this->html->getElement(),
		);
		$stream->consume(Token::Latte_TagClose);
		return $tag;
	}


	private function consumeTag(): array
	{
		$res = [];
		while ($this->stream->peek()->isPhpKind()) {
			$res[] = $this->stream->consume();
		}

		$res[] = new Token(Token::End, '', $this->stream->peek()->position);
		return $res;
	}


	/** @param  array<string, \stdClass|callable(Tag, self): (Node|\Generator|void)>  $parsers */
	public function addTags(array $parsers): static
	{
		foreach ($parsers as $name => $info) {
			$info = $info instanceof \stdClass ? $info : Latte\Extension::order($info);
			if (str_starts_with($name, TemplateLexer::NPrefix)) {
				$this->attrParsersInfo[substr($name, 2)] = $info;
			} else {
				$this->tagParsers[$name] = $info->subject;
				if ($info->generator = Helpers::toReflection($info->subject)->isGenerator()) {
					$this->attrParsersInfo[$name] = $info;
				}
			}
		}

		return $this;
	}


	/** @return callable(Tag, self): (Node|\Generator|void) */
	private function getTagParser(string $name, Position $pos): callable
	{
		if (!isset($this->tagParsers[$name])) {
			$hint = ($t = Helpers::getSuggestion(array_keys($this->tagParsers), $name))
				? ", did you mean {{$t}}?"
				: '';
			if ($this->contentType === ContentType::Html
				&& in_array($this->html->getElement()?->name, ['script', 'style'], true)
			) {
				$hint .= ' (in JavaScript or CSS, try to put a space after bracket or use n:syntax=off)';
			}
			throw new CompileException("Unexpected tag {{$name}}$hint", $pos);
		} elseif (!$this->isTagAllowed($name)) {
			throw new SecurityViolationException("Tag {{$name}} is not allowed", $pos);
		}

		return $this->tagParsers[$name];
	}


	private function completeAttrParsers(): array
	{
		$list = Helpers::sortBeforeAfter($this->attrParsersInfo);
		$parsers = [];
		foreach ($list as $name => $info) {
			$parsers[$name] = $info->subject;
			if ($info->generator ?? false) {
				$parsers[Tag::PrefixInner . '-' . $name] = $parsers[Tag::PrefixTag . '-' . $name] = $parsers[$name];
			}
		}

		return $parsers;
	}


	private function checkEndTag(Tag $start, ?Tag $end): void
	{
		if (!$end) {
			if ($start->name !== 'syntax' && ($start->name !== 'block' || $this->tag->parent)) { // TODO: hardcoded
				$this->stream->throwUnexpectedException(expected: ["{/$start->name}"]);
			}

		} elseif (
			($end->name !== $start->name && $end->name !== '')
			|| !$end->closing
			|| $end->void
		) {
			throw new CompileException("Unexpected {$end->getNotation()}, expecting {/$start->name}", $end->position);
		}
	}


	public function ensureIsConsumed(Tag $tag): void
	{
		if (!$tag->parser->isEnd()) {
			$end = $tag->isNAttribute() ? ['end of attribute'] : ['end of tag'];
			$tag->parser->stream->throwUnexpectedException($end, addendum: ' in ' . $tag->getNotation());
		}
	}


	public function checkBlockIsUnique(Block $block): void
	{
		if ($block->isDynamic() || !preg_match('#^[a-z]#iD', $name = (string) $block->name->value)) {
			throw new CompileException(ucfirst($block->tag->name) . " name must start with letter a-z, '$name' given.", $block->tag->position);
		}

		if ($block->layer === Template::LayerSnippet
			? isset($this->blocks[$block->layer][$name])
			: (isset($this->blocks[Template::LayerLocal][$name]) || isset($this->blocks[$this->blockLayer][$name]))
		) {
			throw new CompileException("Cannot redeclare {$block->tag->name} '{$name}'", $block->tag->position);
		}

		$this->blocks[$block->layer][$name] = $block;
	}


	public function setPolicy(?Policy $policy): static
	{
		$this->policy = $policy;
		return $this;
	}


	public function setContentType(string $type): static
	{
		$this->contentType = $type;
		$this->lexer?->setContentType($type);
		return $this;
	}


	public function getContentType(): string
	{
		return $this->contentType;
	}


	/** @internal */
	public function getStream(): TokenStream
	{
		return $this->stream;
	}


	public function getLexer(): TemplateLexer
	{
		return $this->lexer;
	}


	public function peekTag(): ?Tag
	{
		return $this->tag;
	}


	public function pushTag(Tag $tag): Tag
	{
		$tag->parent = $this->tag;
		$this->tag = $tag;
		return $tag;
	}


	public function popTag(): void
	{
		$this->tag = $this->tag->parent;
	}


	public function generateId(): int
	{
		return $this->counter++;
	}


	public function isTagAllowed(string $name): bool
	{
		return !$this->policy || $this->policy->isTagAllowed($name);
	}
}
