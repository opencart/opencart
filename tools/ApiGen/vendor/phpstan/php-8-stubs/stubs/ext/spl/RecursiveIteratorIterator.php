<?php 

class RecursiveIteratorIterator implements \OuterIterator
{
    public function __construct(Traversable $iterator, int $mode = RecursiveIteratorIterator::LEAVES_ONLY, int $flags = 0)
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
     * @return mixed
     */
    public function key()
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
     * @return void
     */
    public function next()
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function getDepth()
    {
    }
    /**
     * @tentative-return-type
     * @return (RecursiveIterator | null)
     */
    public function getSubIterator(?int $level = null)
    {
    }
    /**
     * @tentative-return-type
     * @return RecursiveIterator
     */
    public function getInnerIterator()
    {
    }
    /**
     * @tentative-return-type
     * @return void
     */
    public function beginIteration()
    {
    }
    /**
     * @tentative-return-type
     * @return void
     */
    public function endIteration()
    {
    }
    /**
     * @tentative-return-type
     * @return (bool | null)
     */
    public function callHasChildren()
    {
    }
    /**
     * @tentative-return-type
     * @return (RecursiveIterator | null)
     */
    public function callGetChildren()
    {
    }
    /**
     * @tentative-return-type
     * @return void
     */
    public function beginChildren()
    {
    }
    /**
     * @tentative-return-type
     * @return void
     */
    public function endChildren()
    {
    }
    /**
     * @tentative-return-type
     * @return void
     */
    public function nextElement()
    {
    }
    /**
     * @tentative-return-type
     * @return void
     */
    public function setMaxDepth(int $maxDepth = -1)
    {
    }
    /**
     * @tentative-return-type
     * @return (int | false)
     */
    public function getMaxDepth()
    {
    }
}