<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential;

use Latte;


/**
 * Smarter caching iterator.
 *
 * @property-read bool $first
 * @property-read bool $last
 * @property-read bool $empty
 * @property-read bool $odd
 * @property-read bool $even
 * @property-read int $counter
 * @property-read int $counter0
 * @property-read mixed $nextKey
 * @property-read mixed $nextValue
 * @property-read ?self $parent
 * @internal
 */
class CachingIterator extends \CachingIterator implements \Countable
{
	use Latte\Strict;

	private int $counter = 0;
	private ?self $parent = null;


	public function __construct(mixed $iterator, ?self $parent = null)
	{
		if (is_array($iterator) || $iterator instanceof \stdClass) {
			$iterator = new \ArrayIterator($iterator);

		} elseif ($iterator instanceof \IteratorAggregate) {
			do {
				$iterator = $iterator->getIterator();
			} while (!$iterator instanceof \Iterator);
		} elseif ($iterator instanceof \Traversable) {
			if (!$iterator instanceof \Iterator) {
				$iterator = new \IteratorIterator($iterator);
			}
		} else {
			throw new \InvalidArgumentException(sprintf('Invalid argument passed to foreach; array or Traversable expected, %s given.', get_debug_type($iterator)));
		}

		parent::__construct($iterator, 0);
		$this->parent = $parent;
	}


	/**
	 * Is the current element the first one?
	 */
	public function isFirst(?int $width = null): bool
	{
		return $this->counter === 1 || ($width && $this->counter !== 0 && (($this->counter - 1) % $width) === 0);
	}


	/**
	 * Is the current element the last one?
	 */
	public function isLast(?int $width = null): bool
	{
		return !$this->hasNext() || ($width && ($this->counter % $width) === 0);
	}


	/**
	 * Is the iterator empty?
	 */
	public function isEmpty(): bool
	{
		return $this->counter === 0;
	}


	/**
	 * Is the counter odd?
	 */
	public function isOdd(): bool
	{
		return $this->counter % 2 === 1;
	}


	/**
	 * Is the counter even?
	 */
	public function isEven(): bool
	{
		return $this->counter % 2 === 0;
	}


	/**
	 * Returns the 1-indexed counter.
	 */
	public function getCounter(): int
	{
		return $this->counter;
	}


	/**
	 * Returns the 0-indexed counter.
	 */
	public function getCounter0(): int
	{
		return max(0, $this->counter - 1);
	}


	/**
	 * Decrements counter.
	 */
	public function skipRound(): void
	{
		$this->counter = max($this->counter - 1, 0);
	}


	/**
	 * Returns the counter as string
	 */
	public function __toString(): string
	{
		return (string) $this->counter;
	}


	/**
	 * Returns the count of elements.
	 */
	public function count(): int
	{
		$inner = $this->getInnerIterator();
		if ($inner instanceof \Countable) {
			return $inner->count();

		} else {
			throw new \LogicException('Iterator is not countable.');
		}
	}


	/**
	 * Forwards to the next element.
	 */
	public function next(): void
	{
		parent::next();
		if (parent::valid()) {
			$this->counter++;
		}
	}


	/**
	 * Rewinds the Iterator.
	 */
	public function rewind(): void
	{
		parent::rewind();
		$this->counter = parent::valid() ? 1 : 0;
	}


	/**
	 * Returns the next key or null if position is not valid.
	 */
	public function getNextKey(): mixed
	{
		$iterator = $this->getInnerIterator();
		return $iterator->valid() ? $iterator->key() : null;
	}


	/**
	 * Returns the next element or null if position is not valid.
	 */
	public function getNextValue(): mixed
	{
		$iterator = $this->getInnerIterator();
		return $iterator->valid() ? $iterator->current() : null;
	}


	/**
	 * Returns the iterator surrounding the current one.
	 */
	public function getParent(): ?self
	{
		return $this->parent;
	}


	/********************* property accessor ****************d*g**/


	/**
	 * Returns property value.
	 * @throws \LogicException if the property is not defined.
	 */
	public function &__get(string $name): mixed
	{
		if (method_exists($this, $m = 'get' . $name) || method_exists($this, $m = 'is' . $name)) {
			$ret = $this->$m();
			return $ret;
		}

		throw new \LogicException('Attempt to read undeclared property ' . static::class . "::\$$name.");
	}


	/**
	 * Is property defined?
	 */
	public function __isset(string $name): bool
	{
		return method_exists($this, 'get' . $name) || method_exists($this, 'is' . $name);
	}
}
