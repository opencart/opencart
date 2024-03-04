<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\PhpGenerator\Traits;

use Nette;
use Nette\PhpGenerator\Constant;


/**
 * @internal
 */
trait ConstantsAware
{
	/** @var array<string, Constant> */
	private array $consts = [];


	/** @param  Constant[]  $consts */
	public function setConstants(array $consts): static
	{
		$this->consts = [];
		foreach ($consts as $k => $const) {
			if (!$const instanceof Constant) {
				trigger_error(__METHOD__ . '() accepts an array of Constant as parameter, ' . get_debug_type($const) . ' given.', E_USER_DEPRECATED);
				$const = (new Constant($k))->setValue($const)->setPublic();
			}

			$this->consts[$const->getName()] = $const;
		}

		return $this;
	}


	/** @return Constant[] */
	public function getConstants(): array
	{
		return $this->consts;
	}


	public function addConstant(string $name, mixed $value): Constant
	{
		if (isset($this->consts[$name])) {
			throw new Nette\InvalidStateException("Cannot add constant '$name', because it already exists.");
		}
		return $this->consts[$name] = (new Constant($name))
			->setValue($value)
			->setPublic();
	}


	public function removeConstant(string $name): static
	{
		unset($this->consts[$name]);
		return $this;
	}
}
