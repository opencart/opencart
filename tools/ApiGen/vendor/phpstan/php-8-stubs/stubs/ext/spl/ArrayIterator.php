<?php 

class ArrayIterator implements \SeekableIterator, \ArrayAccess, \Serializable, \Countable
{
    public function __construct(array|object $array = [], int $flags = 0)
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias ArrayObject::offsetExists
     * @return bool
     */
    public function offsetExists(mixed $key)
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias ArrayObject::offsetGet
     * @return mixed
     */
    public function offsetGet(mixed $key)
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias ArrayObject::offsetSet
     * @return void
     */
    public function offsetSet(mixed $key, mixed $value)
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias ArrayObject::offsetUnset
     * @return void
     */
    public function offsetUnset(mixed $key)
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias ArrayObject::append
     * @return void
     */
    public function append(mixed $value)
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias ArrayObject::getArrayCopy
     * @return array
     */
    public function getArrayCopy()
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias ArrayObject::count
     * @return int
     */
    public function count()
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias ArrayObject::getFlags
     * @return int
     */
    public function getFlags()
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias ArrayObject::setFlags
     * @return void
     */
    public function setFlags(int $flags)
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias ArrayObject::asort
     * @return bool
     */
    public function asort(int $flags = SORT_REGULAR)
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias ArrayObject::ksort
     * @return bool
     */
    public function ksort(int $flags = SORT_REGULAR)
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias ArrayObject::uasort
     * @return bool
     */
    public function uasort(callable $callback)
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias ArrayObject::uksort
     * @return bool
     */
    public function uksort(callable $callback)
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias ArrayObject::natsort
     * @return bool
     */
    public function natsort()
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias ArrayObject::natcasesort
     * @return bool
     */
    public function natcasesort()
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias ArrayObject::unserialize
     * @return void
     */
    public function unserialize(string $data)
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias ArrayObject::serialize
     * @return string
     */
    public function serialize()
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias ArrayObject::__serialize
     * @return array
     */
    public function __serialize()
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias ArrayObject::__unserialize
     * @return void
     */
    public function __unserialize(array $data)
    {
    }
    /**
     * @tentative-return-type
     * @return void
     */
    public function rewind()
    {
    }
    /**
     * @tentative-return-type
     * @return mixed
     */
    public function current()
    {
    }
    /**
     * @tentative-return-type
     * @return mixed
     */
    public function key()
    {
    }
    /**
     * @tentative-return-type
     * @return void
     */
    public function next()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function valid()
    {
    }
    /**
     * @tentative-return-type
     * @return void
     */
    public function seek(int $offset)
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias ArrayObject::__debugInfo
     * @return array
     */
    public function __debugInfo()
    {
    }
}