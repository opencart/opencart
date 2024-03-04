<?php 

class IteratorIterator implements \OuterIterator
{
    public function __construct(Traversable $iterator, ?string $class = null)
    {
    }
    /**
     * @tentative-return-type
     * @return (Iterator | null)
     */
    public function getInnerIterator()
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
}