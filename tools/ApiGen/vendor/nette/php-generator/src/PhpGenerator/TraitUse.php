<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\PhpGenerator;

use Nette;


/**
 * use Trait
 */
final class TraitUse
{
	use Nette\SmartObject {
		__call as private parentCall;
	}
	use Traits\NameAware;
	use Traits\CommentAware;

	/** @var string[] */
	private array $resolutions = [];
	private ?ClassLike $parent;


	public function __construct(string $name, ?ClassLike $parent = null)
	{
		if (!Nette\PhpGenerator\Helpers::isNamespaceIdentifier($name, true)) {
			throw new Nette\InvalidArgumentException("Value '$name' is not valid trait name.");
		}

		$this->name = $name;
		$this->parent = $parent;
	}


	public function addResolution(string $resolution): static
	{
		$this->resolutions[] = $resolution;
		return $this;
	}


	/** @return string[] */
	public function getResolutions(): array
	{
		return $this->resolutions;
	}


	/** @param  mixed[]  $args */
	public function __call(string $nm, array $args): mixed
	{
		if (!$this->parent) {
			return $this->parentCall($nm, $args);
		}
		$trace = debug_backtrace(0);
		$loc = isset($trace[0]['file'])
			? ' in ' . $trace[0]['file'] . ':' . $trace[0]['line']
			: '';
		trigger_error('The ClassType::addTrait() method now returns a TraitUse object instead of ClassType. Please fix the method chaining' . $loc, E_USER_DEPRECATED);
		return $this->parent->$nm(...$args);
	}
}
