<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\PhpGenerator;

use Nette;


/**
 * @internal
 */
final class Helpers
{
	use Nette\StaticClass;

	public const ReIdentifier = '[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*';

	public const Keywords = [
		// built-in types
		'string' => 1, 'int' => 1, 'float' => 1, 'bool' => 1, 'array' => 1, 'object' => 1,
		'callable' => 1, 'iterable' => 1, 'void' => 1, 'null' => 1, 'mixed' => 1, 'false' => 1,
		'never' => 1, 'true' => 1,

		// class keywords
		'self' => 1, 'parent' => 1, 'static' => 1,

		// PHP keywords
		'include' => 1, 'include_once' => 1, 'eval' => 1, 'require' => 1, 'require_once' => 1, 'or' => 1, 'xor' => 1,
		'and' => 1, 'instanceof' => 1, 'new' => 1, 'clone' => 1, 'exit' => 1, 'if' => 1, 'elseif' => 1, 'else' => 1,
		'endif' => 1, 'echo' => 1, 'do' => 1, 'while' => 1, 'endwhile' => 1, 'for' => 1, 'endfor' => 1, 'foreach' => 1,
		'endforeach' => 1, 'declare' => 1, 'enddeclare' => 1, 'as' => 1, 'try' => 1, 'catch' => 1, 'finally' => 1,
		'throw' => 1, 'use' => 1, 'insteadof' => 1, 'global' => 1, 'var' => 1, 'unset' => 1, 'isset' => 1, 'empty' => 1,
		'continue' => 1, 'goto' => 1, 'function' => 1, 'const' => 1, 'return' => 1, 'print' => 1, 'yield' => 1, 'list' => 1,
		'switch' => 1, 'endswitch' => 1, 'case' => 1, 'default' => 1, 'break' => 1,
		'extends' => 1, 'implements' => 1, 'namespace' => 1, 'trait' => 1, 'interface' => 1, 'class' => 1, '__CLASS__' => 1,
		'__TRAIT__' => 1, '__FUNCTION__' => 1, '__METHOD__' => 1, '__LINE__' => 1, '__FILE__' => 1, '__DIR__' => 1,
		'__NAMESPACE__' => 1, 'fn' => 1, 'match' => 1, 'enum' => 1, 'abstract' => 1, 'final' => 1,
		'private' => 1, 'protected' => 1, 'public' => 1, 'readonly' => 1,
	];

	/** @deprecated  */
	public const
		PHP_IDENT = self::ReIdentifier,
		KEYWORDS = self::Keywords;


	/** @deprecated  use (new Nette\PhpGenerator\Dumper)->dump() */
	public static function dump(mixed $var): string
	{
		trigger_error(__METHOD__ . '() is deprecated, use (new Nette\PhpGenerator\Dumper)->dump().', E_USER_DEPRECATED);
		return (new Dumper)->dump($var);
	}


	/** @deprecated  use (new Nette\PhpGenerator\Dumper)->format() */
	public static function format(string $statement, mixed ...$args): string
	{
		trigger_error(__METHOD__ . '() is deprecated, use (new Nette\PhpGenerator\Dumper)->format().', E_USER_DEPRECATED);
		return (new Dumper)->format($statement, ...$args);
	}


	/** @deprecated  use (new Nette\PhpGenerator\Dumper)->format() */
	public static function formatArgs(string $statement, array $args): string
	{
		trigger_error(__METHOD__ . '() is deprecated, use (new Nette\PhpGenerator\Dumper)->format().', E_USER_DEPRECATED);
		return (new Dumper)->format($statement, ...$args);
	}


	public static function formatDocComment(string $content, bool $forceMultiLine = false): string
	{
		$s = trim($content);
		$s = str_replace('*/', '* /', $s);
		if ($s === '') {
			return '';
		} elseif ($forceMultiLine || str_contains($content, "\n")) {
			$s = str_replace("\n", "\n * ", "/**\n$s") . "\n */";
			return Nette\Utils\Strings::normalize($s) . "\n";
		} else {
			return "/** $s */\n";
		}
	}


	public static function tagName(string $name, string $of = PhpNamespace::NameNormal): string
	{
		return isset(self::Keywords[strtolower($name)])
			? $name
			: "/*($of*/$name";
	}


	public static function simplifyTaggedNames(string $code, ?PhpNamespace $namespace): string
	{
		return preg_replace_callback('~/\*\(([ncf])\*/([\w\x7f-\xff\\\\]++)~', function ($m) use ($namespace) {
			[, $of, $name] = $m;
			return $namespace
				? $namespace->simplifyType($name, $of)
				: $name;
		}, $code);
	}


	public static function unformatDocComment(string $comment): string
	{
		return preg_replace('#^\s*\* ?#m', '', trim(trim(trim($comment), '/*')));
	}


	public static function unindent(string $s, int $level = 1): string
	{
		return $level
			? preg_replace('#^(\t| {4}){1,' . $level . '}#m', '', $s)
			: $s;
	}


	public static function isIdentifier(mixed $value): bool
	{
		return is_string($value) && preg_match('#^' . self::ReIdentifier . '$#D', $value);
	}


	public static function isNamespaceIdentifier(mixed $value, bool $allowLeadingSlash = false): bool
	{
		$re = '#^' . ($allowLeadingSlash ? '\\\\?' : '') . self::ReIdentifier . '(\\\\' . self::ReIdentifier . ')*$#D';
		return is_string($value) && preg_match($re, $value);
	}


	public static function extractNamespace(string $name): string
	{
		return ($pos = strrpos($name, '\\')) ? substr($name, 0, $pos) : '';
	}


	public static function extractShortName(string $name): string
	{
		return ($pos = strrpos($name, '\\')) === false
			? $name
			: substr($name, $pos + 1);
	}


	public static function tabsToSpaces(string $s, int $count = 4): string
	{
		return str_replace("\t", str_repeat(' ', $count), $s);
	}


	/**
	 * @param  mixed[]  $props
	 * @internal
	 */
	public static function createObject(string $class, array $props): object
	{
		return Dumper::createObject($class, $props);
	}


	public static function validateType(?string $type, bool &$nullable = false): ?string
	{
		if ($type === '' || $type === null) {
			return null;
		} elseif (!Nette\Utils\Validators::isTypeDeclaration($type)) {
			throw new Nette\InvalidArgumentException("Value '$type' is not valid type.");
		}

		if ($type[0] === '?') {
			$nullable = true;
			return substr($type, 1);
		}

		return $type;
	}
}
