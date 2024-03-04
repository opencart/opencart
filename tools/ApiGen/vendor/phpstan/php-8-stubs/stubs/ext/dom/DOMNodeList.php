<?php 

class DOMNodeList implements \IteratorAggregate, \Countable
{
    /**
     * @tentative-return-type
     * @return (int | false)
     */
    public function count()
    {
    }
    public function getIterator() : Iterator
    {
    }
    /** @return DOMNode|null */
    public function item(int $index)
    {
    }
}