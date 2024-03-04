<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php;

use Latte\Compiler\Node;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


class NameNode extends Node
{
	public const
		KindNormal = 1,
		KindFullyQualified = 2;

	/** @var string[] */
	public array $parts;


	public function __construct(
		string|array $name,
		public int $kind = self::KindNormal,
		public ?Position $position = null,
	) {
		if ($name === '' || $name === []) {
			throw new \InvalidArgumentException('Name cannot be empty');

		} elseif (is_string($name)) {
			if (str_starts_with($name, '\\')) {
				$this->kind = self::KindFullyQualified;
				$name = substr($name, 1);
			} elseif (str_starts_with($name, 'namespace\\')) {
				throw new \InvalidArgumentException('Relative name is not supported');
			} else {
				$this->kind = self::KindNormal;
			}
			$this->parts = explode('\\', $name);

		} else {
			$this->parts = $name;
		}
	}


	public function isKeyword(): bool
	{
		static $keywords;
		$keywords ??= array_flip([ // https://www.php.net/manual/en/reserved.keywords.php
			'__halt_compiler', '__class__', '__dir__', '__file__', '__function__', '__line__', '__method__', '__namespace__', '__trait__',
			'abstract', 'and', 'array', 'as', 'break', 'callable', 'case', 'catch', 'class', 'clone', 'const', 'continue', 'declare',
			'default', 'die', 'do', 'echo', 'else', 'elseif', 'empty', 'enddeclare', 'endfor', 'endforeach', 'endif', 'endswitch',
			'endwhile', 'eval', 'exit', 'extends', 'final', 'finally', 'fn', 'for', 'foreach', 'function', 'global', 'goto', 'if',
			'implements', 'include', 'include_once', 'instanceof', 'insteadof', 'interface', 'isset', 'list', 'match', 'namespace',
			'new', 'or', 'print', 'private', 'protected', 'public', 'readonly', 'require', 'require_once', 'return', 'static',
			'switch', 'throw', 'trait', 'try', 'unset', 'use', 'var', 'while', 'xor', 'yield',
			'parent', 'self', 'mixed', 'void', 'enum', // extra
		]);
		return count($this->parts) === 1 && isset($keywords[strtolower($this->parts[0])]);
	}


	public function print(PrintContext $context): string
	{
		return $this->toCodeString();
	}


	public function __toString(): string
	{
		return implode('\\', $this->parts);
	}


	public function toCodeString(): string
	{
		$prefix = match ($this->kind) {
			self::KindNormal => $this->isKeyword() ? 'namespace\\' : '',
			self::KindFullyQualified => '\\',
		};
		return $prefix . implode('\\', $this->parts);
	}


	public function &getIterator(): \Generator
	{
		false && yield;
	}
}
