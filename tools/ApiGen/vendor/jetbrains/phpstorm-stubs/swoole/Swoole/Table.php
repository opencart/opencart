<?php

declare(strict_types=1);

namespace Swoole;

class Table implements \Iterator, \ArrayAccess, \Countable
{
    public const TYPE_INT = 1;
    public const TYPE_STRING = 3;
    public const TYPE_FLOAT = 2;

    /**
     * @var int
     */
    public $size;

    /**
     * @var int
     */
    public $memorySize;

    public function __construct(int $table_size, float $conflict_proportion = 0.2) {}

    /**
     * @return bool
     */
    public function column(string $name, int $type, int $size = 0) {}

    /**
     * @return bool
     */
    public function create() {}

    /**
     * @return bool returns TRUE all the time
     */
    public function destroy() {}

    /**
     * @return bool
     */
    public function set(string $key, array $value) {}

    /**
     * @return array|false Return an array of stats information; Return FALSE when error happens.
     * @since 4.8.0
     */
    public function stats() {}

    /**
     * @return mixed
     */
    public function get(string $key, string $field = null) {}

    /**
     * This method has an alias of \Swoole\Table::delete().
     *
     * @return bool
     * @see \Swoole\Table::delete()
     */
    public function del(string $key) {}

    /**
     * Alias of method \Swoole\Table::del().
     *
     * @return bool
     * @see \Swoole\Table::del()
     */
    public function delete(string $key) {}

    /**
     * This method has an alias of \Swoole\Table::exist().
     *
     * @return bool
     * @see \Swoole\Table::exist()
     */
    public function exists(string $key) {}

    /**
     * Alias of method \Swoole\Table::exists().
     *
     * @return bool
     * @see \Swoole\Table::exists()
     */
    public function exist(string $key) {}

    /**
     * @param mixed $incrby
     * @return int
     */
    public function incr(string $key, string $column, $incrby = 1) {}

    /**
     * @param mixed $decrby
     * @return int
     */
    public function decr(string $key, string $column, $decrby = 1) {}

    /**
     * @return int
     */
    public function getSize() {}

    /**
     * @return int
     */
    public function getMemorySize() {}

    /**
     * @return mixed
     * @see \Iterator::current()
     * @see https://www.php.net/manual/en/iterator.current.php
     * {@inheritDoc}
     */
    public function current() {}

    /**
     * @return mixed
     * @see \Iterator::key()
     * @see https://www.php.net/manual/en/iterator.key.php
     * {@inheritDoc}
     */
    public function key() {}

    /**
     * @return void
     * @see \Iterator::next()
     * @see https://www.php.net/manual/en/iterator.next.php
     * {@inheritDoc}
     */
    public function next() {}

    /**
     * @return void
     * @see \Iterator::rewind()
     * @see https://www.php.net/manual/en/iterator.rewind.php
     * {@inheritDoc}
     */
    public function rewind() {}

    /**
     * @return bool
     * @see \Iterator::valid()
     * @see https://www.php.net/manual/en/iterator.valid.php
     * {@inheritDoc}
     */
    public function valid() {}

    /**
     * Whether or not an offset exists.
     *
     * @param mixed $offset an offset to check for
     * @return bool returns true on success or false on failure
     * @see \ArrayAccess::offsetExists()
     * @see https://www.php.net/manual/en/arrayaccess.offsetexists.php
     * {@inheritDoc}
     */
    public function offsetExists($offset) {}

    /**
     * Returns the value at specified offset.
     *
     * @param mixed $offset the offset to retrieve
     * @return mixed can return all value types
     * @see \ArrayAccess::offsetGet()
     * @see https://www.php.net/manual/en/arrayaccess.offsetget.php
     * {@inheritDoc}
     */
    public function offsetGet($offset) {}

    /**
     * Assigns a value to the specified offset.
     *
     * @param mixed $offset the offset to assign the value to
     * @param mixed $value the value to set
     * @return void
     * @see \ArrayAccess::offsetSet()
     * @see https://www.php.net/manual/en/arrayaccess.offsetset.php
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value) {}

    /**
     * Unsets an offset.
     *
     * @param mixed $offset the offset to unset
     * @return void
     * @see \ArrayAccess::offsetUnset()
     * @see https://www.php.net/manual/en/arrayaccess.offsetunset.php
     * {@inheritDoc}
     */
    public function offsetUnset($offset) {}

    /**
     * @return int
     * @see \Countable::count()
     * @see https://www.php.net/manual/en/countable.count.php
     * {@inheritDoc}
     */
    public function count() {}
}
