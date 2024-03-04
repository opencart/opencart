<?php 

class MultipleIterator implements \Iterator
{
    public function __construct(int $flags = MultipleIterator::MIT_NEED_ALL | MultipleIterator::MIT_KEYS_NUMERIC)
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
     * @tentative-return-type
     * @return void
     */
    public function attachIterator(Iterator $iterator, string|int|null $info = null)
    {
    }
    /**
     * @tentative-return-type
     * @return void
     */
    public function detachIterator(Iterator $iterator)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function containsIterator(Iterator $iterator)
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function countIterators()
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
     * @return (array | false)
     */
    public function key()
    {
    }
    /**
     * @tentative-return-type
     * @return (array | false)
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
     * @implementation-alias SplObjectStorage::__debugInfo
     * @return array
     */
    public function __debugInfo()
    {
    }
}