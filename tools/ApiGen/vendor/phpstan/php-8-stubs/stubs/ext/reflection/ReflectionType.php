<?php 

abstract class ReflectionType implements \Stringable
{
    /** @implementation-alias ReflectionClass::__clone */
    #[\Until('8.1')]
    private final function __clone() : void
    {
    }
    /** @implementation-alias ReflectionClass::__clone */
    #[\Since('8.1')]
    private function __clone() : void
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function allowsNull()
    {
    }
    public function __toString() : string
    {
    }
}