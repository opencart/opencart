<?php

/**
 * This file is part of the Tracy (https://tracy.nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

if (!function_exists('dump')) {
	/**
	 * Tracy\Debugger::dump() shortcut.
	 * @tracySkipLocation
	 */
	function dump(mixed $var): mixed
	{
		array_map([Tracy\Debugger::class, 'dump'], func_get_args());
		return $var;
	}
}

if (!function_exists('dumpe')) {
	/**
	 * Tracy\Debugger::dump() & exit shortcut.
	 * @tracySkipLocation
	 */
	function dumpe(mixed $var): void
	{
		array_map([Tracy\Debugger::class, 'dump'], func_get_args());
		if (!Tracy\Debugger::$productionMode) {
			exit;
		}
	}
}

if (!function_exists('bdump')) {
	/**
	 * Tracy\Debugger::barDump() shortcut.
	 * @tracySkipLocation
	 */
	function bdump(mixed $var): mixed
	{
		Tracy\Debugger::barDump(...func_get_args());
		return $var;
	}
}
