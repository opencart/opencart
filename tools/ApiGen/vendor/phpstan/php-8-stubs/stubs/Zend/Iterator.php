<?php 

interface Iterator extends \Traversable
{
    /**
     * @tentative-return-type
     * @return mixed
     */
    public function current();
    /**
     * @tentative-return-type
     * @return void
     */
    public function next();
    /**
     * @tentative-return-type
     * @return mixed
     */
    public function key();
    /**
     * @tentative-return-type
     * @return bool
     */
    public function valid();
    /**
     * @tentative-return-type
     * @return void
     */
    public function rewind();
}