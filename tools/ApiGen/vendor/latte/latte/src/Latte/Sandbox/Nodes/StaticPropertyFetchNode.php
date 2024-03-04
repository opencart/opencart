<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Sandbox\Nodes;

use Latte\Compiler\Nodes\Php\Expression;
use Latte\Compiler\PrintContext;


class StaticPropertyFetchNode extends Expression\StaticPropertyFetchNode
{
	public function __construct(Expression\StaticPropertyFetchNode $from)
	{
		parent::__construct($from->class, $from->name, $from->position);
	}


	public function print(PrintContext $context): string
	{
		return '$this->global->sandbox->prop('
			. $context->memberAsString($this->class) . ', '
			. $context->memberAsString($this->name) . ')'
			. '::$'
			. $context->objectProperty($this->name);
	}
}
