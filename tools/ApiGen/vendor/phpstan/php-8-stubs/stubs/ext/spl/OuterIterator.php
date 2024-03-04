<?php 

interface OuterIterator extends \Iterator
{
    /**
     * @tentative-return-type
     * @return (Iterator | null)
     */
    public function getInnerIterator();
}