<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Html;

use Latte\Compiler\Node;
use Latte\Compiler\NodeHelpers;
use Latte\Compiler\Nodes;
use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\AuxiliaryNode;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;


/**
 * HTML element node.
 */
class ElementNode extends AreaNode
{
	public ?Node $customName = null;
	public ?FragmentNode $attributes = null;
	public bool $selfClosing = false;
	public ?AreaNode $content = null;

	/** @var Tag[] */
	public array $nAttributes = [];

	/** n:tag & n:tag- support */
	public AreaNode $tagNode;
	public bool $captureTagName = false;
	private ?string $endTagVar;


	public function __construct(
		public /*readonly*/ string $name,
		public ?Position $position = null,
		public /*readonly*/ ?self $parent = null,
		public ?\stdClass $data = null,
	) {
		$this->data ??= new \stdClass;
		$this->tagNode = new AuxiliaryNode(\Closure::fromCallable([$this, 'printStartTag']));
	}


	public function getAttribute(string $name): string|Node|bool|null
	{
		foreach ($this->attributes?->children as $child) {
			if ($child instanceof AttributeNode
				&& $child->name instanceof Nodes\TextNode
				&& strcasecmp($name, $child->name->content) === 0
			) {
				return NodeHelpers::toText($child->value) ?? $child->value ?? true;
			}
		}

		return null;
	}


	public function print(PrintContext $context): string
	{
		$res = $this->endTagVar = null;
		if ($this->captureTagName || $this->customName) {
			$endTag = $this->endTagVar = '$ʟ_tag[' . $context->generateId() . ']';
			$res = "$this->endTagVar = '';";
		} else {
			$endTag = var_export('</' . $this->name . '>', true);
		}

		$res .= $this->tagNode->print($context); // calls $this->printStartTag()

		if ($this->content) {
			$context->beginEscape()->enterHtmlText($this);
			$res .= $this->content->print($context);
			$context->restoreEscape();
			$res .= 'echo ' . $endTag . ';';
		}

		return $res;
	}


	private function printStartTag(PrintContext $context): string
	{
		$context->beginEscape()->enterHtmlTag($this->name);
		$res = "echo '<';";

		$namePhp = var_export($this->name, true);
		if ($this->endTagVar) {
			$res .= 'echo $ʟ_tmp = (' . ($this->customName ? $this->customName->print($context) : $namePhp) . ');';
			$res .= $this->endTagVar . ' = '
				. "'</' . \$ʟ_tmp . '>'"
				. ' . ' . $this->endTagVar . ';';
		} else {
			$res .= 'echo ' . $namePhp . ';';
		}

		foreach ($this->attributes?->children ?? [] as $attr) {
			$res .= $attr->print($context);
		}

		$res .= "echo '" . ($this->selfClosing ? '/>' : '>') . "';";
		$context->restoreEscape();
		return $res;
	}


	public function &getIterator(): \Generator
	{
		yield $this->tagNode;
		if ($this->customName) {
			yield $this->customName;
		}
		if ($this->attributes) {
			yield $this->attributes;
		}
		if ($this->content) {
			yield $this->content;
		}
	}
}
