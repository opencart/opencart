<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Html;

use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


class CommentNode extends AreaNode
{
	public function __construct(
		public AreaNode $content,
		public ?Position $position = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		$context->beginEscape()->enterHtmlComment();
		$content = $this->content->print($context);
		$context->restoreEscape();
		return "echo '<!--'; $content echo '-->';";
	}


	public function &getIterator(): \Generator
	{
		yield $this->content;
	}
}
