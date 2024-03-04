<?php

namespace StubTests\Model;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use ReturnTypeWillChange;
use RuntimeException;

class PhpVersions implements ArrayAccess, IteratorAggregate
{
    private static $versions = [5.3, 5.4, 5.5, 5.6, 7.0, 7.1, 7.2, 7.3, 7.4, 8.0, 8.1, 8.2];

    public static function getLatest()
    {
        return end(self::$versions);
    }

    /**
     * @return float
     */
    public static function getFirst()
    {
        return self::$versions[0];
    }

    /**
     * @param $offset
     * @return bool
     */
    #[ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return isset(self::$versions[$offset]);
    }

    #[ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? self::$versions[$offset] : null;
    }

    #[ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        throw new RuntimeException('Unsupported operation');
    }

    #[ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        throw new RuntimeException('Unsupported operation');
    }

    /**
     * @return ArrayIterator
     */
    #[ReturnTypeWillChange]
    public function getIterator()
    {
        return new ArrayIterator(self::$versions);
    }
}
