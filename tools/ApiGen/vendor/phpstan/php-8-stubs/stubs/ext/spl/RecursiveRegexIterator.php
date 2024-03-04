<?php 

class RecursiveRegexIterator extends \RegexIterator implements \RecursiveIterator
{
    public function __construct(RecursiveIterator $iterator, string $pattern, int $mode = RecursiveRegexIterator::MATCH, int $flags = 0, int $pregFlags = 0)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function accept()
    {
    }
    /**
     * @tentative-return-type
     * @implementation-alias RecursiveFilterIterator::hasChildren
     * @return bool
     */
    public function hasChildren()
    {
    }
    /**
     * @tentative-return-type
     * @return RecursiveRegexIterator
     */
    public function getChildren()
    {
    }
}