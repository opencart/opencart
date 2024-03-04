<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\DI\Extensions;

use Nette;


/**
 * Constant definitions.
 * @deprecated  use Nette\Bootstrap\Extensions\ConstantsExtension
 */
final class ConstantsExtension extends Nette\DI\CompilerExtension
{
	public function loadConfiguration()
	{
		trigger_error(self::class . ' is deprecated, use Nette\Bootstrap\Extensions\ConstantsExtension.', E_USER_DEPRECATED);
		foreach ($this->getConfig() as $name => $value) {
			$this->initialization->addBody('define(?, ?);', [$name, $value]);
		}
	}
}
