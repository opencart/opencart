<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php;

use Latte\CompileException;
use Latte\Compiler\Node;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


class ParameterNode extends Node
{
	public function __construct(
		public Expression\VariableNode $var,
		public ?ExpressionNode $default = null,
		public IdentifierNode|NameNode|ComplexTypeNode|null $type = null,
		public bool $byRef = false,
		public bool $variadic = false,
		public ?Position $position = null,
	) {
		if ($variadic && $default !== null) {
			throw new CompileException('Variadic parameter cannot have a default value', $position);
		}
	}


	public function print(PrintContext $context): string
	{
		return ($this->type ? $this->type->print($context) . ' ' : '')
			. ($this->byRef ? '&' : '')
			. ($this->variadic ? '...' : '')
			. $this->var->print($context)
			. ($this->default ? ' = ' . $this->default->print($context) : '');
	}


	public function &getIterator(): \Generator
	{
		if ($this->type) {
			yield $this->type;
		}
		yield $this->var;
		if ($this->default) {
			yield $this->default;
		}
	}
}
