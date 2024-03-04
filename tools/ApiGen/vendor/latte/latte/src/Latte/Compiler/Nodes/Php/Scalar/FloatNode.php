<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Scalar;

use Latte\Compiler\Nodes\Php\ScalarNode;
use Latte\Compiler\PhpHelpers;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


class FloatNode extends ScalarNode
{
	public function __construct(
		public float $value,
		public ?Position $position = null,
	) {
	}


	public static function parse(string $str, Position $position): static
	{
		return strpbrk($str, '.eE') === false
			? new static((float) PhpHelpers::decodeNumber($str), $position)
			: new static((float) str_replace('_', '', $str), $position);
	}


	public function print(PrintContext $context): string
	{
		if (!is_finite($this->value)) {
			if ($this->value === \INF) {
				return '\INF';
			} elseif ($this->value === -\INF) {
				return '-\INF';
			} else {
				return '\NAN';
			}
		}

		// Try to find a short full-precision representation
		$stringValue = sprintf('%.16G', $this->value);
		if ($this->value !== (float) $stringValue) {
			$stringValue = sprintf('%.17G', $this->value);
		}

		// %G is locale dependent and there exists no locale-independent alternative. We don't want
		// mess with switching locales here, so let's assume that a comma is the only non-standard
		// decimal separator we may encounter...
		$stringValue = str_replace(',', '.', $stringValue);

		// ensure that number is really printed as float
		return preg_match('/^-?[0-9]+$/', $stringValue)
			? $stringValue . '.0'
			: $stringValue;
	}
}
