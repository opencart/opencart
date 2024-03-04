<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte;


final class Position
{
	use Latte\Strict;

	public function __construct(
		public /*readonly*/ int $line,
		public /*readonly*/ int $column,
		public /*readonly*/ int $offset = 0,
	) {
	}


	public function advance(string $str): self
	{
		if ($lines = substr_count($str, "\n")) {
			return new self(
				$this->line + $lines,
				strlen($str) - strrpos($str, "\n"),
				$this->offset + strlen($str),
			);
		} else {
			return new self(
				$this->line,
				$this->column + strlen($str),
				$this->offset + strlen($str),
			);
		}
	}


	public function toWords(): string
	{
		return "on line $this->line at column $this->column";
	}
}
