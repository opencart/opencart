<?php

namespace RdKafka\Metadata;

class Collection implements \Iterator, \Countable
{
    /**
     * @return mixed
     */
    public function current() {}

    /**
     * @return void
     */
    public function next() {}

    /**
     * @return mixed
     */
    public function key() {}

    /**
     * @return bool
     */
    public function valid() {}

    /**
     * @return void
     */
    public function rewind() {}

    /**
     * @return int
     */
    public function count() {}
}
