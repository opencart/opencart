<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Scalar;

use Latte\CompileException;
use Latte\Compiler\Nodes\Php\ScalarNode;
use Latte\Compiler\PhpHelpers;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


class IntegerNode extends ScalarNode
{
	public const KindBinary = 2;
	public const KindOctal = 8;
	public const KindDecimal = 10;
	public const KindHexa = 16;


	public function __construct(
		public int $value,
		public int $kind = self::KindDecimal,
		public ?Position $position = null,
	) {
	}


	public static function parse(string $str, Position $position): static
	{
		$num = PhpHelpers::decodeNumber($str, $base);
		if ($num === null) {
			throw new CompileException('Invalid numeric literal', $position);
		}
		return new static($num, $base, $position);
	}


	public function print(PrintContext $context): string
	{
		if ($this->value === -\PHP_INT_MAX - 1) {
			// PHP_INT_MIN cannot be represented as a literal, because the sign is not part of the literal
			return '(-' . \PHP_INT_MAX . '-1)';

		} elseif ($this->kind === self::KindDecimal) {
			return (string) $this->value;
		}

		if ($this->value < 0) {
			$sign = '-';
			$str = (string) -$this->value;
		} else {
			$sign = '';
			$str = (string) $this->value;
		}

		return match ($this->kind) {
			self::KindBinary => $sign . '0b' . base_convert($str, 10, 2),
			self::KindOctal => $sign . '0' . base_convert($str, 10, 8),
			self::KindHexa => $sign . '0x' . base_convert($str, 10, 16),
			default => throw new \Exception('Invalid number kind'),
		};
	}
}
