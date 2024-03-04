<?php

declare(strict_types=1);

namespace Swoole\Connection;

class Iterator implements \Iterator, \ArrayAccess, \Countable
{
    public function __construct() {}

    public function __destruct() {}

    public function rewind(): void {}

    public function next(): void {}

    /**
     * @return mixed
     */
    public function current() {}

    /**
     * @return mixed
     */
    public function key() {}

    public function valid(): bool {}

    public function count(): int {}

    public function offsetExists($fd): bool {}

    /**
     * @param mixed $fd
     * @return mixed
     */
    public function offsetGet($fd) {}

    public function offsetSet($fd, $value): void {}

    public function offsetUnset($fd): void {}
}
