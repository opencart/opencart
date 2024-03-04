<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Sandbox;

use Latte;
use Latte\Compiler\ExpressionBuilder;
use Latte\Compiler\Node;
use Latte\Compiler\Nodes\Php;
use Latte\Compiler\Nodes\Php\Expression;
use Latte\Compiler\Nodes\TemplateNode;
use Latte\Compiler\NodeTraverser;
use Latte\Engine;
use Latte\Runtime\Template;
use Latte\SecurityViolationException;


/**
 * Security protection for the sandbox.
 */
final class SandboxExtension extends Latte\Extension
{
	use Latte\Strict;

	private ?Latte\Policy $policy;


	public function beforeCompile(Engine $engine): void
	{
		$this->policy = $engine->getPolicy(effective: true);
	}


	public function getTags(): array
	{
		return [
			'sandbox' => [Nodes\SandboxNode::class, 'create'],
		];
	}


	public function getPasses(): array
	{
		return $this->policy
			? ['sandbox' => self::order([$this, 'processPass'], before: '*')]
			: [];
	}


	public function beforeRender(Template $template): void
	{
		$engine = $template->getEngine();
		if ($policy = $engine->getPolicy()) {
			$engine->addProvider('sandbox', new RuntimeChecker($policy));
		}
	}


	public function getCacheKey(Engine $engine): mixed
	{
		return (bool) $engine->getPolicy(effective: true);
	}


	public function processPass(TemplateNode $node): void
	{
		(new NodeTraverser)->traverse($node, leave: \Closure::fromCallable([$this, 'sandboxVisitor']));
	}


	private function sandboxVisitor(Node $node): Node
	{
		if ($node instanceof Expression\VariableNode) {
			if ($node->name === 'this') {
				throw new SecurityViolationException("Forbidden variable \${$node->name}.", $node->position);
			} elseif (!is_string($node->name)) {
				throw new SecurityViolationException('Forbidden variable variables.', $node->position);
			}

			return $node;

		} elseif ($node instanceof Expression\NewNode) {
			throw new SecurityViolationException("Forbidden keyword 'new'", $node->position);

		} elseif ($node instanceof Expression\FunctionCallNode
			&& $node->name instanceof Php\NameNode
		) {
			if (!$this->policy->isFunctionAllowed((string) $node->name)) {
				throw new SecurityViolationException("Function $node->name() is not allowed.", $node->position);

			} elseif ($node->args) {
				$arg = ExpressionBuilder::variable('$this')->property('global')->property('sandbox')->method('args', $node->args)
					->build();
				$node->args = [new Php\ArgumentNode($arg, unpack: true)];
			}

			return $node;

		} elseif ($node instanceof Php\FilterNode) {
			$name = (string) $node->name;
			if (!$this->policy->isFilterAllowed($name)) {
				throw new SecurityViolationException("Filter |$name is not allowed.", $node->position);

			} elseif ($node->args) {
				$arg = ExpressionBuilder::variable('$this')->property('global')->property('sandbox')->method('args', $node->args)
					->build();
				$node->args = [new Php\ArgumentNode($arg, unpack: true)];
			}

			return $node;

		} elseif ($node instanceof Expression\PropertyFetchNode
			|| $node instanceof Expression\StaticPropertyFetchNode
			|| $node instanceof Expression\FunctionCallNode
			|| $node instanceof Expression\FunctionCallableNode
			|| $node instanceof Expression\MethodCallNode
			|| $node instanceof Expression\MethodCallableNode
			|| $node instanceof Expression\StaticCallNode
			|| $node instanceof Expression\StaticCallableNode
		) {
			$class = namespace\Nodes::class . strrchr($node::class, '\\');
			return new $class($node);

		} else {
			return $node;
		}
	}
}
