<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte;


interface Exception
{
}


/**
 * The exception occurred during Latte compilation.
 */
class CompileException extends \Exception implements Exception
{
	use PositionAwareException;

	/** @deprecated */
	public ?int $sourceLine;


	public function __construct(string $message, ?Compiler\Position $position = null, ?\Throwable $previous = null)
	{
		parent::__construct($message, 0, $previous);
		$this->position = $position;
		$this->sourceLine = $position?->line;
		$this->generateMessage();
	}
}


/**
 * The exception that indicates error of the last Regexp execution.
 */
class RegexpException extends \Exception implements Exception
{
	public function __construct()
	{
		parent::__construct(preg_last_error_msg(), preg_last_error());
	}
}


/**
 * Exception thrown when a not allowed construction is used in a template.
 */
class SecurityViolationException extends \Exception implements Exception
{
	use PositionAwareException;

	public function __construct(string $message, ?Compiler\Position $position = null)
	{
		parent::__construct($message);
		$this->position = $position;
		$this->generateMessage();
	}
}


class RuntimeException extends \RuntimeException implements Exception
{
}
