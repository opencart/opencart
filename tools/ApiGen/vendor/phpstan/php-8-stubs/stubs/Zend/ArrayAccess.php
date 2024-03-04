<?php 

interface ArrayAccess
{
    /**
     * @tentative-return-type
     * @return bool
     */
    public function offsetExists(mixed $offset);
    /**
     * Actually this should be return by ref but atm cannot be.
     * @tentative-return-type
     * @return mixed
     */
    public function offsetGet(mixed $offset);
    /**
     * @tentative-return-type
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value);
    /**
     * @tentative-return-type
     * @return void
     */
    public function offsetUnset(mixed $offset);
}