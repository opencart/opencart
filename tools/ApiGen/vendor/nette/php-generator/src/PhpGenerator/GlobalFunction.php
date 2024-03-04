<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\PhpGenerator;

use Nette;


/**
 * Global function.
 *
 * @property-deprecated string $body
 */
final class GlobalFunction
{
	use Nette\SmartObject;
	use Traits\FunctionLike;
	use Traits\NameAware;
	use Traits\CommentAware;
	use Traits\AttributeAware;

	public static function from(string $function, bool $withBody = false): self
	{
		return (new Factory)->fromFunctionReflection(new \ReflectionFunction($function), $withBody);
	}


	/** @deprecated  use GlobalFunction::from(..., withBody: true) */
	public static function withBodyFrom(string $function): self
	{
		trigger_error(__METHOD__ . '() is deprecated, use GlobalFunction::from(..., withBody: true)', E_USER_DEPRECATED);
		return (new Factory)->fromFunctionReflection(new \ReflectionFunction($function), withBody: true);
	}


	public function __toString(): string
	{
		return (new Printer)->printFunction($this);
	}
}
