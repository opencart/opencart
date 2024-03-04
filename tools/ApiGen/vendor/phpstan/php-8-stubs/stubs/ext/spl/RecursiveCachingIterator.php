<?php 

class RecursiveCachingIterator extends \CachingIterator implements \RecursiveIterator
{
    public function __construct(Iterator $iterator, int $flags = RecursiveCachingIterator::CALL_TOSTRING)
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
     * @return (RecursiveCachingIterator | null)
     */
    public function getChildren()
    {
    }
}