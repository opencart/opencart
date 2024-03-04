<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\PhpGenerator;


/**
 * PHP return, property and parameter types.
 */
class Type
{
	public const
		String = 'string',
		Int = 'int',
		Float = 'float',
		Bool = 'bool',
		Array = 'array',
		Object = 'object',
		Callable = 'callable',
		Iterable = 'iterable',
		Void = 'void',
		Never = 'never',
		Mixed = 'mixed',
		True = 'true',
		False = 'false',
		Null = 'null',
		Self = 'self',
		Parent = 'parent',
		Static = 'static';

	/** @deprecated use Type::String */
	public const STRING = self::String;

	/** @deprecated use Type::Int */
	public const INT = self::Int;

	/** @deprecated use Type::Float */
	public const FLOAT = self::Float;

	/** @deprecated use Type::Bool */
	public const BOOL = self::Bool;

	/** @deprecated use Type::Array */
	public const ARRAY = self::Array;

	/** @deprecated use Type::Object */
	public const OBJECT = self::Object;

	/** @deprecated use Type::Callable */
	public const CALLABLE = self::Callable;

	/** @deprecated use Type::Iterable */
	public const ITERABLE = self::Iterable;

	/** @deprecated use Type::Void */
	public const VOID = self::Void;

	/** @deprecated use Type::Never */
	public const NEVER = self::Never;

	/** @deprecated use Type::Mixed */
	public const MIXED = self::Mixed;

	/** @deprecated use Type::False */
	public const FALSE = self::False;

	/** @deprecated use Type::Null */
	public const NULL = self::Null;

	/** @deprecated use Type::Self */
	public const SELF = self::Self;

	/** @deprecated use Type::Parent */
	public const PARENT = self::Parent;

	/** @deprecated use Type::Static */
	public const STATIC = self::Static;


	public static function nullable(string $type, bool $state = true): string
	{
		return ($state ? '?' : '') . ltrim($type, '?');
	}


	public static function union(string ...$types): string
	{
		return implode('|', $types);
	}


	public static function intersection(string ...$types): string
	{
		return implode('&', $types);
	}


	/** @deprecated  use get_debug_type() */
	public static function getType(mixed $value): ?string
	{
		trigger_error(__METHOD__ . '() is deprecated, use PHP function get_debug_type()', E_USER_DEPRECATED);
		return is_resource($value) ? null : get_debug_type($value);
	}
}
