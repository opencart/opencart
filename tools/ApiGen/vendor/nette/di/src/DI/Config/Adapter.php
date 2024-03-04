<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\DI\Config;


/**
 * Adapter for reading and writing configuration files.
 */
interface Adapter
{
	/**
	 * Reads configuration from file.
	 */
	function load(string $file): array;
}


class_exists(IAdapter::class);
