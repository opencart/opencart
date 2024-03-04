<?php

/**
 * This file is part of the Nette Tester.
 * Copyright (c) 2009 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Tester\Runner;


/**
 * Test represents one result.
 */
class Test
{
	public const
		Prepared = 0,
		Failed = 1,
		Passed = 2,
		Skipped = 3;

	/** @deprecated */
	public const
		PREPARED = self::Prepared,
		FAILED = self::Failed,
		PASSED = self::Passed,
		SKIPPED = self::Skipped;

	public ?string $title;
	public ?string $message = null;
	public string $stdout = '';
	public string $stderr = '';
	private string $file;
	private int $result = self::Prepared;
	private ?float $duration = null;

	/** @var string[]|string[][] */
	private $args = [];


	public function __construct(string $file, ?string $title = null)
	{
		$this->file = $file;
		$this->title = $title;
	}


	public function getFile(): string
	{
		return $this->file;
	}


	/**
	 * @return string[]|string[][]
	 */
	public function getArguments(): array
	{
		return $this->args;
	}


	public function getSignature(): string
	{
		$args = implode(' ', array_map(fn($arg): string => is_array($arg) ? "$arg[0]=$arg[1]" : $arg, $this->args));

		return $this->file . ($args ? " $args" : '');
	}


	public function getResult(): int
	{
		return $this->result;
	}


	public function hasResult(): bool
	{
		return $this->result !== self::Prepared;
	}


	/**
	 * Duration in seconds.
	 */
	public function getDuration(): ?float
	{
		return $this->duration;
	}


	/**
	 * Full output (stdout + stderr)
	 */
	public function getOutput(): string
	{
		return $this->stdout . ($this->stderr ? "\nSTDERR:\n" . $this->stderr : '');
	}


	/**
	 * @return static
	 */
	public function withArguments(array $args): self
	{
		if ($this->hasResult()) {
			throw new \LogicException('Cannot change arguments of test which already has a result.');
		}

		$me = clone $this;
		foreach ($args as $name => $values) {
			foreach ((array) $values as $value) {
				$me->args[] = is_int($name)
					? "$value"
					: [$name, "$value"];
			}
		}

		return $me;
	}


	/**
	 * @return static
	 */
	public function withResult(int $result, ?string $message, ?float $duration = null): self
	{
		if ($this->hasResult()) {
			throw new \LogicException("Result of test is already set to $this->result with message '$this->message'.");
		}

		$me = clone $this;
		$me->result = $result;
		$me->message = $message;
		$me->duration = $duration;
		return $me;
	}
}
