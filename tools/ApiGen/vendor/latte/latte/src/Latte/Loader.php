<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte;


/**
 * Template loader.
 */
interface Loader
{
	/**
	 * Returns template source code.
	 */
	function getContent(string $name): string;

	/**
	 * Checks whether template is expired.
	 */
	function isExpired(string $name, int $time): bool;

	/**
	 * Returns referred template name.
	 */
	function getReferredName(string $name, string $referringName): string;

	/**
	 * Returns unique identifier for caching.
	 */
	function getUniqueId(string $name): string;
}
