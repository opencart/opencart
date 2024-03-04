<?php 

abstract class RecursiveFilterIterator extends \FilterIterator implements \RecursiveIterator
{
    public function __construct(RecursiveIterator $iterator)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function hasChildren()
    {
    }
    /**
     * @tentative-return-type
     * @return (RecursiveFilterIterator | null)
     */
    public function getChildren()
    {
    }
}