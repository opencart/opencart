<?php 

class CachingIterator extends \IteratorIterator implements \ArrayAccess, \Countable
{
    public function __construct(Iterator $iterator, int $flags = CachingIterator::CALL_TOSTRING)
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
     * @return void
     */
    public function next()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function hasNext()
    {
    }
    public function __toString() : string
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function getFlags()
    {
    }
    /**
     * @tentative-return-type
     * @return void
     */
    public function setFlags(int $flags)
    {
    }
    /**
     * @param string $key
     * @tentative-return-type
     * @return mixed
     */
    public function offsetGet($key)
    {
    }
    /**
     * @param string $key
     * @tentative-return-type
     * @return void
     */
    public function offsetSet($key, mixed $value)
    {
    }
    /**
     * @param string $key
     * @tentative-return-type
     * @return void
     */
    public function offsetUnset($key)
    {
    }
    /**
     * @param string $key
     * @tentative-return-type
     * @return bool
     */
    public function offsetExists($key)
    {
    }
    /**
     * @tentative-return-type
     * @return array
     */
    public function getCache()
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function count()
    {
    }
}