<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\DI;

use Nette;


/**
 * Service not found exception.
 */
class MissingServiceException extends Nette\InvalidStateException
{
}


/**
 * Service creation exception.
 */
class ServiceCreationException extends Nette\InvalidStateException
{
	public function setMessage(string $message): self
	{
		$this->message = $message;
		return $this;
	}
}


/**
 * Not allowed when container is resolving.
 */
class NotAllowedDuringResolvingException extends Nette\InvalidStateException
{
}


/**
 * Error in configuration.
 */
class InvalidConfigurationException extends Nette\InvalidStateException
{
}
