<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential;

use Latte;
use Latte\CompileException;
use Latte\Compiler\ExpressionBuilder;
use Latte\Compiler\Node;
use Latte\Compiler\Nodes\AuxiliaryNode;
use Latte\Compiler\Nodes\Php\Expression\FunctionCallNode;
use Latte\Compiler\Nodes\Php\Expression\VariableNode;
use Latte\Compiler\Nodes\Php\NameNode;
use Latte\Compiler\Nodes\TemplateNode;
use Latte\Compiler\NodeTraverser;
use Latte\Compiler\PrintContext;
use Latte\Essential\Nodes\ForeachNode;


final class Passes
{
	use Latte\Strict;

	/**
	 * Checks if foreach overrides template variables.
	 */
	public static function overwrittenVariablesPass(TemplateNode $node): void
	{
		$vars = [];
		(new NodeTraverser)->traverse($node, function (Node $node) use (&$vars) {
			if ($node instanceof ForeachNode && $node->checkArgs) {
				foreach ([$node->key, $node->value] as $var) {
					if ($var instanceof VariableNode) {
						$vars[$var->name][] = $node->position->line;
					}
				}
			}
		});
		if ($vars) {
			array_unshift($node->head->children, new AuxiliaryNode(fn(PrintContext $context) => $context->format(
				<<<'XX'
					if (!$this->getReferringTemplate() || $this->getReferenceType() === 'extends') {
						foreach (array_intersect_key(%dump, $this->params) as $ʟ_v => $ʟ_l) {
							trigger_error("Variable \$$ʟ_v overwritten in foreach on line $ʟ_l");
						}
					}

					XX,
				array_map(fn($l) => implode(', ', $l), $vars),
			)));
		}
	}


	/**
	 * Move TemplatePrintNode to head.
	 */
	public static function moveTemplatePrintToHeadPass(TemplateNode $templateNode): void
	{
		(new NodeTraverser)->traverse($templateNode->main, function (Node $node) use ($templateNode) {
			if ($node instanceof Latte\Essential\Nodes\TemplatePrintNode) {
				array_unshift($templateNode->head->children, $node);
				return new Latte\Compiler\Nodes\NopNode;
			}
		});
	}


	/**
	 * Enable custom functions.
	 */
	public static function customFunctionsPass(TemplateNode $node, array $functions): void
	{
		$names = array_keys($functions);
		$names = array_combine(array_map('strtolower', $names), $names);

		(new NodeTraverser)->traverse($node, function (Node $node) use ($names) {
			if (($node instanceof FunctionCallNode || $node instanceof FunctionCallableNode)
				&& $node->name instanceof NameNode
				&& ($orig = $names[strtolower((string) $node->name)] ?? null)
			) {
				if ((string) $node->name !== $orig) {
					trigger_error("Case mismatch on function name '{$node->name}', correct name is '$orig'.", E_USER_WARNING);
				}

				return ExpressionBuilder::function(
					ExpressionBuilder::variable('$this')->property('global')->property('fn')->property($orig),
					$node->args,
				)->build();
			}
		});
	}


	/**
	 * $ʟ_xxx variables are forbidden
	 */
	public static function internalVariablesPass(TemplateNode $node): void
	{
		(new NodeTraverser)->traverse($node, function (Node $node) {
			if ($node instanceof VariableNode
				&& is_string($node->name)
				&& (str_starts_with($node->name, 'ʟ_'))
			) {
				throw new CompileException("Forbidden variable \$$node->name.", $node->position);
			}
		});
	}
}
