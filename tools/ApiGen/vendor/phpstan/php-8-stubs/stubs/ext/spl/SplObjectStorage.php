<?php 

class SplObjectStorage implements \Countable, \Iterator, \Serializable, \ArrayAccess
{
    /**
     * @tentative-return-type
     * @return void
     */
    public function attach(object $object, mixed $info = null)
    {
    }
    /**
     * @tentative-return-type
     * @return void
     */
    public function detach(object $object)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function contains(object $object)
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function addAll(SplObjectStorage $storage)
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function removeAll(SplObjectStorage $storage)
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function removeAllExcept(SplObjectStorage $storage)
    {
    }
    /**
     * @tentative-return-type
     * @return mixed
     */
    public function getInfo()
    {
    }
    /**
     * @tentative-return-type
     * @return void
     */
    public function setInfo(mixed $info)
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function count(int $mode = COUNT_NORMAL)
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
     * @return bool
     */
    public function valid()
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function key()
    {
    }
    /**
     * @tentative-return-type
     * @return (object | null)
     */
    public function current()
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
     * @return void
     */
    public function unserialize(string $data)
    {
    }
    /**
     * @tentative-return-type
     * @return string
     */
    public function serialize()
    {
    }
    /**
     * @param object $object
     * @tentative-return-type
     * @implementation-alias SplObjectStorage::contains
     * @no-verify Cannot specify arg type because ArrayAccess does not
     * @return bool
     */
    public function offsetExists($object)
    {
    }
    /**
     * @param object $object
     * @tentative-return-type
     * @return mixed
     */
    public function offsetGet($object)
    {
    }
    /**
     * @param object $object
     * @tentative-return-type
     * @implementation-alias SplObjectStorage::attach
     * @no-verify Cannot specify arg type because ArrayAccess does not
     * @return void
     */
    public function offsetSet($object, mixed $info = null)
    {
    }
    /**
     * @param object $object
     * @tentative-return-type
     * @implementation-alias SplObjectStorage::detach
     * @no-verify Cannot specify arg type because ArrayAccess does not
     * @return void
     */
    public function offsetUnset($object)
    {
    }
    /**
     * @tentative-return-type
     * @return string
     */
    public function getHash(object $object)
    {
    }
    /**
     * @tentative-return-type
     * @return array
     */
    public function __serialize()
    {
    }
    /**
     * @tentative-return-type
     * @return void
     */
    public function __unserialize(array $data)
    {
    }
    /**
     * @tentative-return-type
     * @return array
     */
    public function __debugInfo()
    {
    }
}