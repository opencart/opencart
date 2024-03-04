<?php 

interface IteratorAggregate extends \Traversable
{
    /**
     * @tentative-return-type
     * @return Traversable
     */
    public function getIterator();
}