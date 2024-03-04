<?php 

interface SeekableIterator extends \Iterator
{
    /**
     * @tentative-return-type
     * @return void
     */
    public function seek(int $offset);
}