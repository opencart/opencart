<?php 

interface RecursiveIterator extends \Iterator
{
    /**
     * @tentative-return-type
     * @return bool
     */
    public function hasChildren();
    /**
     * @tentative-return-type
     * @return (RecursiveIterator | null)
     */
    public function getChildren();
}