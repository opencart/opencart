<?php 

class ParentIterator extends \RecursiveFilterIterator
{
    public function __construct(RecursiveIterator $iterator)
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias RecursiveFilterIterator::hasChildren
     * @return bool
     */
    public function accept()
    {
    }
}