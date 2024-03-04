<?php 

abstract class FilterIterator extends \IteratorIterator
{
    /**
     * @tentative-return-type
     * @return bool
     */
    public abstract function accept();
    public function __construct(Iterator $iterator)
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
     * @return void
     */
    public function next()
    {
    }
}