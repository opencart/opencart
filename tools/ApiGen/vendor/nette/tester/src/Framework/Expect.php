<?php

/**
 * This file is part of the Nette Tester.
 * Copyright (c) 2009 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Tester;


/**
 * Expectations for more complex assertions formulation.
 *
 * @method static self same($expected)
 * @method static self notSame($expected)
 * @method static self equal($expected)
 * @method static self notEqual($expected)
 * @method static self contains($needle)
 * @method static self notContains($needle)
 * @method static self true()
 * @method static self false()
 * @method static self null()
 * @method static self nan()
 * @method static self truthy()
 * @method static self falsey()
 * @method static self count(int $count)
 * @method static self type(string|object $type)
 * @method static self match(string $pattern)
 * @method static self matchFile(string $file)
 *
 * @method self andSame($expected)
 * @method self andNotSame($expected)
 * @method self andEqual($expected)
 * @method self andNotEqual($expected)
 * @method self andContains($needle)
 * @method self andNotContains($needle)
 * @method self andTrue()
 * @method self andFalse()
 * @method self andNull()
 * @method self andNan()
 * @method self andTruthy()
 * @method self andFalsey()
 * @method self andCount(int $count)
 * @method self andType(string|object $type)
 * @method self andMatch(string $pattern)
 * @method self andMatchFile(string $file)
 */
class Expect
{
	/** array<self|\Closure|\stdClass> */
	private array $constraints = [];


	public static function __callStatic(string $method, array $args): self
	{
		$me = new self;
		$me->constraints[] = (object) ['method' => $method, 'args' => $args];
		return $me;
	}


	public static function that(callable $constraint): self
	{
		return (new self)->and($constraint);
	}


	public function __call(string $method, array $args): self
	{
		if (preg_match('#^and([A-Z]\w+)#', $method, $m)) {
			$this->constraints[] = (object) ['method' => lcfirst($m[1]), 'args' => $args];
			return $this;
		}

		throw new \Error('Call to undefined method ' . self::class . '::' . $method . '()');
	}


	public function and(callable $constraint): self
	{
		$this->constraints[] = $constraint;
		return $this;
	}


	/**
	 * Checks the expectations.
	 */
	public function __invoke(mixed $actual): void
	{
		foreach ($this->constraints as $cstr) {
			if ($cstr instanceof \stdClass) {
				$args = $cstr->args;
				$args[] = $actual;
				Assert::{$cstr->method}(...$args);

			} elseif ($cstr($actual) === false) {
				Assert::fail('%1 is expected to be %2', $actual, is_string($cstr) ? $cstr : 'user-expectation');
			}
		}
	}


	public function dump(): string
	{
		$res = [];
		foreach ($this->constraints as $cstr) {
			if ($cstr instanceof \stdClass) {
				$args = isset($cstr->args[0])
					? Dumper::toLine($cstr->args[0])
					: '';
				$res[] = "$cstr->method($args)";

			} elseif ($cstr instanceof self) {
				$res[] = $cstr->dump();

			} else {
				$res[] = is_string($cstr) ? $cstr : 'user-expectation';
			}
		}

		return implode(',', $res);
	}
}
