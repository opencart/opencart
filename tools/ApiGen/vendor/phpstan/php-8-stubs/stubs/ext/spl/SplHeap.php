<?php 

abstract class SplHeap implements \Iterator, \Countable
{
    /**
     * @tentative-return-type
     * @return mixed
     */
    public function extract()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function insert(mixed $value)
    {
    }
    /**
     * @tentative-return-type
     * @return mixed
     */
    public function top()
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function count()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function isEmpty()
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
     * @return int
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
     * @return bool
     */
    public function recoverFromCorruption()
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    protected abstract function compare(mixed $value1, mixed $value2);
    /**
     * @tentative-return-type
     * @return bool
     */
    public function isCorrupted()
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