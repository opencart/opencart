<?php

/**
 * SCSSPHP
 *
 * @copyright 2012-2020 Leaf Corcoran
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 * @link http://scssphp.github.io/scssphp
 */

namespace ScssPhp\ScssPhp\Extend;

/**
 * @template T of object
 * @template-implements \IteratorAggregate<int, T>
 */
class ObjectSet implements \IteratorAggregate
{
    /**
     * @var \SplObjectStorage<T, mixed>
     */
    private readonly \SplObjectStorage $storage;

    public function __construct()
    {
        $this->storage = new \SplObjectStorage();
    }

    /**
     * @param T $value
     */
    public function contains(object $value): bool
    {
        return $this->storage->contains($value);
    }

    /**
     * @param T $value
     */
    public function add(object $value): void
    {
        $this->storage->attach($value);
    }

    /**
     * @param ObjectSet<T> $set
     */
    public function addAll(self $set): void
    {
        $this->storage->addAll($set->storage);
    }

    public function getIterator(): \Traversable
    {
        return $this->storage;
    }
}
