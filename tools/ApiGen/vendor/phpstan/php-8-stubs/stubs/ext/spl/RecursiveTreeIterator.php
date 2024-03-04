<?php 

class RecursiveTreeIterator extends \RecursiveIteratorIterator
{
    /** @param RecursiveIterator|IteratorAggregate $iterator */
    public function __construct($iterator, int $flags = RecursiveTreeIterator::BYPASS_KEY, int $cachingIteratorFlags = CachingIterator::CATCH_GET_CHILD, int $mode = RecursiveTreeIterator::SELF_FIRST)
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
     * @return string
     */
    public function getPrefix()
    {
    }
    /**
     * @tentative-return-type
     * @return void
     */
    public function setPostfix(string $postfix)
    {
    }
    /**
     * @tentative-return-type
     * @return void
     */
    public function setPrefixPart(int $part, string $value)
    {
    }
    /**
     * @tentative-return-type
     * @return string
     */
    public function getEntry()
    {
    }
    /**
     * @tentative-return-type
     * @return string
     */
    public function getPostfix()
    {
    }
}