<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Runtime;

use Latte;
use Latte\ContentType;
use Latte\RuntimeException;
use Nette;


/**
 * Escaping & sanitization filters.
 * @internal
 */
class Filters
{
	/** @deprecated */
	public static string $dateFormat = "j.\u{a0}n.\u{a0}Y";

	/** @internal use XML syntax? */
	public static bool $xml = false;


	/**
	 * Escapes string for use everywhere inside HTML (except for comments).
	 */
	public static function escapeHtml($s): string
	{
		return htmlspecialchars((string) $s, ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE, 'UTF-8');
	}


	/**
	 * Escapes string for use inside HTML text.
	 */
	public static function escapeHtmlText($s): string
	{
		if ($s instanceof HtmlStringable || $s instanceof Nette\HtmlStringable) {
			return $s->__toString();
		}

		$s = htmlspecialchars((string) $s, ENT_NOQUOTES | ENT_SUBSTITUTE, 'UTF-8');
		$s = strtr($s, ['{{' => '{<!-- -->{', '{' => '&#123;']);
		return $s;
	}


	/**
	 * Escapes string for use inside HTML attribute value.
	 */
	public static function escapeHtmlAttr($s, bool $double = true): string
	{
		$double = $double && $s instanceof HtmlStringable ? false : $double;
		$s = (string) $s;
		if (str_contains($s, '`') && strpbrk($s, ' <>"\'') === false) {
			$s .= ' '; // protection against innerHTML mXSS vulnerability nette/nette#1496
		}

		$s = htmlspecialchars($s, ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE, 'UTF-8', $double);
		$s = str_replace('{', '&#123;', $s);
		return $s;
	}


	/**
	 * Escapes string for use inside HTML tag.
	 */
	public static function escapeHtmlTag($s): string
	{
		$s = (string) $s;
		return preg_match('#^[a-z0-9:-]+$#i', $s)
			? $s
			: '"' . self::escapeHtmlAttr($s) . '"';
	}


	/**
	 * Escapes string for use inside HTML/XML comments.
	 */
	public static function escapeHtmlComment($s): string
	{
		$s = (string) $s;
		if ($s && ($s[0] === '-' || $s[0] === '>' || $s[0] === '!')) {
			$s = ' ' . $s;
		}

		$s = str_replace('--', '- - ', $s);
		if (substr($s, -1) === '-') {
			$s .= ' ';
		}

		return $s;
	}


	/**
	 * Escapes string for use everywhere inside XML (except for comments and tags).
	 */
	public static function escapeXml($s): string
	{
		if ($s instanceof HtmlStringable) {
			return $s->__toString();
		}

		// XML 1.0: \x09 \x0A \x0D and C1 allowed directly, C0 forbidden
		// XML 1.1: \x00 forbidden directly and as a character reference,
		//   \x09 \x0A \x0D \x85 allowed directly, C0, C1 and \x7F allowed as character references
		$s = preg_replace('#[\x00-\x08\x0B\x0C\x0E-\x1F]#', "\u{FFFD}", (string) $s);
		return htmlspecialchars($s, ENT_QUOTES | ENT_XML1 | ENT_SUBSTITUTE, 'UTF-8');
	}


	/**
	 * Escapes string for use inside XML tag.
	 */
	public static function escapeXmlTag($s): string
	{
		$s = (string) $s;
		return preg_match('#^[a-z0-9:-]+$#i', $s)
			? $s
			: '"' . self::escapeXml($s) . '"';
	}


	/**
	 * Escapes string for use inside CSS template.
	 */
	public static function escapeCss($s): string
	{
		// http://www.w3.org/TR/2006/WD-CSS21-20060411/syndata.html#q6
		return addcslashes((string) $s, "\x00..\x1F!\"#$%&'()*+,./:;<=>?@[\\]^`{|}~");
	}


	/**
	 * Escapes variables for use inside <script>.
	 */
	public static function escapeJs(mixed $s): string
	{
		if ($s instanceof HtmlStringable || $s instanceof Nette\HtmlStringable) {
			$s = $s->__toString();
		}

		$json = json_encode($s, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE);
		if ($error = json_last_error()) {
			throw new Latte\RuntimeException(json_last_error_msg(), $error);
		}

		return str_replace([']]>', '<!', '</'], [']]\u003E', '\u003C!', '<\/'], $json);
	}


	/**
	 * Escapes string for use inside iCal template.
	 */
	public static function escapeICal($s): string
	{
		// https://www.ietf.org/rfc/rfc5545.txt
		$s = str_replace("\r", '', (string) $s);
		$s = preg_replace('#[\x00-\x08\x0B-\x1F]#', "\u{FFFD}", (string) $s);
		return addcslashes($s, "\";\\,:\n");
	}


	/**
	 * Converts JS and CSS for usage in <script> or <style>
	 */
	public static function convertJSToHtmlRawText($s): string
	{
		return preg_replace('#</(script|style)#i', '<\/$1', (string) $s);
	}


	/**
	 * Converts ... to ...
	 */
	public static function convertTo(FilterInfo $info, string $dest, string $s): string
	{
		$source = $info->contentType ?: ContentType::Text;
		if ($source === $dest) {
			return $s;
		} elseif ($conv = Latte\Compiler\Escaper::getConvertor($source, $dest)) {
			$info->contentType = $dest;
			return $conv($s);
		} else {
			throw new RuntimeException('Filters: unable to convert content type ' . strtoupper($source) . ' to ' . strtoupper($dest));
		}
	}


	public static function nop($s): string
	{
		return (string) $s;
	}


	/**
	 * Converts HTML text to quoted attribute. The quotation marks need to be escaped.
	 */
	public static function convertHtmlToHtmlAttr(string $s): string
	{
		return self::escapeHtmlAttr($s, false);
	}


	/**
	 * Converts HTML text to unquoted attribute. The quotation marks need to be escaped.
	 */
	public static function convertHtmlToUnquotedAttr(string $s): string
	{
		return '"' . self::escapeHtmlAttr($s, false) . '"';
	}


	/**
	 * Converts HTML quoted attribute to unquoted.
	 */
	public static function convertHtmlAttrToUnquotedAttr(string $s): string
	{
		return '"' . $s . '"';
	}


	/**
	 * Converts HTML to plain text.
	 */
	public static function convertHtmlToText(string $s): string
	{
		$s = strip_tags($s);
		return html_entity_decode($s, ENT_QUOTES | ENT_HTML5, 'UTF-8');
	}


	/**
	 * Sanitizes string for use inside href attribute.
	 */
	public static function safeUrl(string|HtmlStringable $s): string
	{
		if ($s instanceof HtmlStringable) {
			$s = self::convertHtmlToText((string) $s);
		}

		return preg_match('~^(?:(?:https?|ftp)://[^@]+(?:/.*)?|(?:mailto|tel|sms):.+|[/?#].*|[^:]+)$~Di', $s) ? $s : '';
	}
}
