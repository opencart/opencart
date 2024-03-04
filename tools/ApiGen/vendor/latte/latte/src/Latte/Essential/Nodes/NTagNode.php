<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte;
use Latte\CompileException;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;


/**
 * n:tag="..."
 */
final class NTagNode extends StatementNode
{
	public ExpressionNode $name;
	public string $origName;


	public static function create(Tag $tag): void
	{
		if (preg_match('(style$|script$)iA', $tag->htmlElement->name)) {
			throw new CompileException('Attribute n:tag is not allowed in <script> or <style>', $tag->position);
		}

		$tag->expectArguments();
		$node = new static;
		$node->name = $tag->parser->parseExpression();
		$node->origName = $tag->htmlElement->name;
		$tag->htmlElement->customName = $node;
	}


	public function print(PrintContext $context): string
	{
		return self::class . '::check('
			. var_export($this->origName, true)
			. ', '
			. $this->name->print($context)
			. ')';
	}


	public static function check(string $orig, $new): string
	{
		if ($new === null) {
			return $orig;

		} elseif (
			!is_string($new)
			|| !preg_match('~' . Latte\Compiler\TemplateLexer::ReTagName . '$~DA', $new)
		) {
			throw new Latte\RuntimeException('Invalid tag name ' . var_export($new, true));

		} elseif (
			in_array($lower = strtolower($new), ['style', 'script'], true)
			|| isset(Latte\Helpers::$emptyElements[strtolower($orig)]) !== isset(Latte\Helpers::$emptyElements[$lower])
		) {
			throw new Latte\RuntimeException("Forbidden tag <$orig> change to <$new>.");
		}

		return $new;
	}


	public function &getIterator(): \Generator
	{
		yield $this->name;
	}
}
