<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Loaders;

use Latte;


/**
 * Template loader.
 */
class StringLoader implements Latte\Loader
{
	use Latte\Strict;

	/** @var string[]|null  [name => content] */
	private ?array $templates = null;


	/**
	 * @param  string[]  $templates
	 */
	public function __construct(?array $templates = null)
	{
		$this->templates = $templates;
	}


	/**
	 * Returns template source code.
	 */
	public function getContent(string $name): string
	{
		if ($this->templates === null) {
			return $name;
		} elseif (isset($this->templates[$name])) {
			return $this->templates[$name];
		} else {
			throw new Latte\RuntimeException("Missing template '$name'.");
		}
	}


	public function isExpired(string $name, int $time): bool
	{
		return false;
	}


	/**
	 * Returns referred template name.
	 */
	public function getReferredName(string $name, string $referringName): string
	{
		if ($this->templates === null) {
			throw new \LogicException("Missing template '$name'.");
		}

		return $name;
	}


	/**
	 * Returns unique identifier for caching.
	 */
	public function getUniqueId(string $name): string
	{
		return $this->getContent($name);
	}
}
