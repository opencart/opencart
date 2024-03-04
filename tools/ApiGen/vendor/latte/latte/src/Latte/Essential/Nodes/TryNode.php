<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;


/**
 * {try} ... {else}
 */
class TryNode extends StatementNode
{
	public AreaNode $try;
	public ?AreaNode $else = null;


	/** @return \Generator<int, ?array, array{AreaNode, ?Tag}, static> */
	public static function create(): \Generator
	{
		$node = new static;
		[$node->try, $nextTag] = yield ['else'];
		if ($nextTag?->name === 'else') {
			[$node->else] = yield;
		}

		return $node;
	}


	public function print(PrintContext $context): string
	{
		return $context->format(
			<<<'XX'
				$ʟ_try[%dump] = [$ʟ_it ?? null];
				ob_start(fn() => '');
				try %line {
					%node
				} catch (Throwable $ʟ_e) {
					ob_end_clean();
					if (!($ʟ_e instanceof Latte\Essential\RollbackException) && isset($this->global->coreExceptionHandler)) {
						($this->global->coreExceptionHandler)($ʟ_e, $this);
					}
					%node
					ob_start();
				} finally {
					echo ob_get_clean();
					$iterator = $ʟ_it = $ʟ_try[%0.dump][0];
				}
				XX,
			$context->generateId(),
			$this->position,
			$this->try,
			$this->else,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->try;
		if ($this->else) {
			yield $this->else;
		}
	}
}
