<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\DI\Definitions;

use Nette;


/**
 * Reference to service. Either by name or by type or reference to the 'self' service.
 */
final class Reference
{
	use Nette\SmartObject;

	public const Self = 'self';

	/** @deprecated use Reference::Self */
	public const SELF = self::Self;

	/** @var string */
	private $value;


	public static function fromType(string $value): self
	{
		if (strpos($value, '\\') === false) {
			$value = '\\' . $value;
		}

		return new static($value);
	}


	public function __construct(string $value)
	{
		$this->value = $value;
	}


	public function getValue(): string
	{
		return $this->value;
	}


	public function isName(): bool
	{
		return strpos($this->value, '\\') === false && $this->value !== self::Self;
	}


	public function isType(): bool
	{
		return strpos($this->value, '\\') !== false;
	}


	public function isSelf(): bool
	{
		return $this->value === self::Self;
	}
}
