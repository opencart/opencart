<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte;
use Latte\Compiler\Nodes\Html\ElementNode;
use Latte\ContentType;
use Latte\Runtime\Filters;


/**
 * Context-aware escaping.
 */
final class Escaper
{
	use Latte\Strict;

	public const
		Text = 'text',
		JavaScript = 'js',
		Css = 'css',
		ICal = 'ical',
		Url = 'url';

	public const
		HtmlText = 'html',
		HtmlComment = 'html/comment',
		HtmlBogusTag = 'html/bogus',
		HtmlCss = 'html/css',
		HtmlJavaScript = 'html/js',
		HtmlTag = 'html/tag',
		HtmlAttribute = 'html/attr';

	private string $state = '';
	private string $tag = '';
	private string $quote = '';
	private string $subType = '';


	public function __construct(
		private string $contentType,
	) {
		$this->state = $this->contentType;
	}


	public function getContentType(): string
	{
		return $this->contentType;
	}


	public function getState(): string
	{
		return $this->state;
	}


	public function export(): string
	{
		return ($this->state === self::HtmlAttribute && $this->quote === '' ? 'html/unquoted-attr' : $this->state)
			. ($this->subType ? '/' . $this->subType : '');
	}


	public function enterContentType(string $type): void
	{
		$this->contentType = $this->state = $type;
	}


	public function enterHtmlText(?ElementNode $node): void
	{
		$this->state = self::HtmlText;
		if ($this->contentType === ContentType::Html && $node) {
			$name = strtolower($node->name);
			if (
				($name === 'script' || $name === 'style')
				&& is_string($attr = $node->getAttribute('type') ?? 'css')
				&& preg_match('#(java|j|ecma|live)script|module|json|css|plain#i', $attr)
			) {
				$this->state = $name === 'script'
					? self::HtmlJavaScript
					: self::HtmlCss;
			}
		}
	}


	public function enterHtmlTag(string $name): void
	{
		$this->state = self::HtmlTag;
		$this->tag = strtolower($name);
	}


	public function enterHtmlAttribute(?string $name = null, string $quote = ''): void
	{
		$this->state = self::HtmlAttribute;
		$this->quote = $quote;
		$this->subType = '';

		if ($this->contentType === ContentType::Html && is_string($name)) {
			$name = strtolower($name);
			if (str_starts_with($name, 'on')) {
				$this->subType = self::JavaScript;
			} elseif ($name === 'style') {
				$this->subType = self::Css;
			} elseif ((in_array($name, ['href', 'src', 'action', 'formaction'], true)
				|| ($name === 'data' && $this->tag === 'object'))
			) {
				$this->subType = self::Url;
			}
		}
	}


	public function enterHtmlAttributeQuote(string $quote = '"'): void
	{
		$this->quote = $quote;
	}


	public function enterHtmlBogusTag(): void
	{
		$this->state = self::HtmlBogusTag;
	}


	public function enterHtmlComment(): void
	{
		$this->state = self::HtmlComment;
	}


	public function escape(string $str): string
	{
		[$lq, $rq] = $this->state === self::HtmlAttribute && !$this->quote ? ["'\"' . ", " . '\"'"] : ['', ''];
		return match ($this->contentType) {
			ContentType::Html => match ($this->state) {
				self::HtmlText => 'LR\Filters::escapeHtmlText(' . $str . ')',
				self::HtmlTag => 'LR\Filters::escapeHtmlTag(' . $str . ')',
				self::HtmlAttribute => match ($this->subType) {
					'',
					self::Url => $lq . 'LR\Filters::escapeHtmlAttr(' . $str . ')' . $rq,
					self::JavaScript => $lq . 'LR\Filters::escapeHtmlAttr(LR\Filters::escapeJs(' . $str . '))' . $rq,
					self::Css => $lq . 'LR\Filters::escapeHtmlAttr(LR\Filters::escapeCss(' . $str . '))' . $rq,
				},
				self::HtmlComment => 'LR\Filters::escapeHtmlComment(' . $str . ')',
				self::HtmlBogusTag => 'LR\Filters::escapeHtml(' . $str . ')',
				self::HtmlJavaScript => 'LR\Filters::escapeJs(' . $str . ')',
				self::HtmlCss => 'LR\Filters::escapeCss(' . $str . ')',
				default => throw new \LogicException("Unknown context $this->contentType, $this->state."),
			},
			ContentType::Xml => match ($this->state) {
				self::HtmlText,
				self::HtmlBogusTag => 'LR\Filters::escapeXml(' . $str . ')',
				self::HtmlAttribute => $lq . 'LR\Filters::escapeXml(' . $str . ')' . $rq,
				self::HtmlComment => 'LR\Filters::escapeHtmlComment(' . $str . ')',
				self::HtmlTag => 'LR\Filters::escapeXmlTag(' . $str . ')',
				default => throw new \LogicException("Unknown context $this->contentType, $this->state."),
			},
			ContentType::JavaScript => 'LR\Filters::escapeJs(' . $str . ')',
			ContentType::Css => 'LR\Filters::escapeCss(' . $str . ')',
			ContentType::ICal => 'LR\Filters::escapeIcal(' . $str . ')',
			ContentType::Text => '($this->filters->escape)(' . $str . ')',
			default => throw new \LogicException("Unknown content-type $this->contentType."),
		};
	}


	public function check(string $str): string
	{
		if ($this->state === self::HtmlAttribute && $this->subType === self::Url) {
			$str = 'LR\Filters::safeUrl(' . $str . ')';
		}
		return $str;
	}


	public static function getConvertor(string $source, string $dest): ?callable
	{
		$table = [
			self::Text => [
				'html' => 'escapeHtmlText',
				'html/attr' => 'escapeHtmlAttr',
				'html/attr/js' => 'escapeHtmlAttr',
				'html/attr/css' => 'escapeHtmlAttr',
				'html/attr/url' => 'escapeHtmlAttr',
				'html/comment' => 'escapeHtmlComment',
				'xml' => 'escapeXml',
				'xml/attr' => 'escapeXml',
			],
			self::JavaScript => [
				'html' => 'escapeHtmlText',
				'html/attr' => 'escapeHtmlAttr',
				'html/attr/js' => 'escapeHtmlAttr',
				'html/js' => 'convertJSToHtmlRawText',
				'html/comment' => 'escapeHtmlComment',
			],
			self::Css => [
				'html' => 'escapeHtmlText',
				'html/attr' => 'escapeHtmlAttr',
				'html/attr/css' => 'escapeHtmlAttr',
				'html/css' => 'convertJSToHtmlRawText',
				'html/comment' => 'escapeHtmlComment',
			],
			'html' => [
				'html/attr' => 'convertHtmlToHtmlAttr',
				'html/attr/js' => 'convertHtmlToHtmlAttr',
				'html/attr/css' => 'convertHtmlToHtmlAttr',
				'html/attr/url' => 'convertHtmlToHtmlAttr',
				'html/comment' => 'escapeHtmlComment',
				'html/unquoted-attr' => 'convertHtmlToUnquotedAttr',
			],
			'html/attr' => [
				'html' => 'convertHtmlToHtmlAttr',
				'html/unquoted-attr' => 'convertHtmlAttrToUnquotedAttr',
			],
			'html/attr/url' => [
				'html' => 'convertHtmlToHtmlAttr',
				'html/attr' => 'nop',
			],
			'html/unquoted-attr' => [
				'html' => 'convertHtmlToHtmlAttr',
			],
		];

		if ($source === $dest) {
			return [Filters::class, 'nop'];
		}

		return isset($table[$source][$dest])
			? [Filters::class, $table[$source][$dest]]
			: null;
	}
}
