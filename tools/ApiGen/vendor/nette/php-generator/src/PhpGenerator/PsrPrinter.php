<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\PhpGenerator;


/**
 * Generates PHP code compatible with PSR-2 and PSR-12.
 */
final class PsrPrinter extends Printer
{
	public string $indentation = '    ';
	public int $linesBetweenMethods = 1;
	public int $linesBetweenUseTypes = 1;
}
