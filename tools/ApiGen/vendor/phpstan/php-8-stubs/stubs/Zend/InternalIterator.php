<?php 

final class InternalIterator implements \Iterator
{
    /** @return mixed */
    #[\Until('8.1')]
    public function current();
    /** @return mixed */
    #[\Until('8.1')]
    public function key();
    private function __construct();
    #[\Since('8.1')]
    public function current() : mixed
    {
    }
    #[\Since('8.1')]
    public function key() : mixed
    {
    }
    public function next() : void;
    public function valid() : bool;
    public function rewind() : void;
}