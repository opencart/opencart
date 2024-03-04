<?php 

class ReflectionZendExtension implements \Reflector
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
    public function __construct(string $name)
    {
    }
    public function __toString() : string
    {
    }
    /**
     * @tentative-return-type
     * @return string
     */
    public function getName()
    {
    }
    /**
     * @tentative-return-type
     * @return string
     */
    public function getVersion()
    {
    }
    /**
     * @tentative-return-type
     * @return string
     */
    public function getAuthor()
    {
    }
    /**
     * @tentative-return-type
     * @return string
     */
    public function getURL()
    {
    }
    /**
     * @tentative-return-type
     * @return string
     */
    public function getCopyright()
    {
    }
}