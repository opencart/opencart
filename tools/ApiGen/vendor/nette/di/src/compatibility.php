<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\DI\Config {
	if (false) {
		/** @deprecated use Nette\DI\Config\Adapter */
		interface IAdapter
		{
		}
	} elseif (!interface_exists(IAdapter::class)) {
		class_alias(Adapter::class, IAdapter::class);
	}
}

namespace Nette\DI {
	if (false) {
		/** @deprecated use Nette\DI\Definitions\ServiceDefinition */
		class ServiceDefinition
		{
		}
	} elseif (!class_exists(ServiceDefinition::class)) {
		class_alias(Definitions\ServiceDefinition::class, ServiceDefinition::class);
	}

	if (false) {
		/** @deprecated use Nette\DI\Definitions\Statement */
		class Statement
		{
		}
	} elseif (!class_exists(Statement::class)) {
		class_alias(Definitions\Statement::class, Statement::class);
	}
}
