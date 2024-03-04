<?php 

class ReflectionParameter implements \Reflector
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
    /** @param string|array|object $function */
    public function __construct($function, int|string $param)
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
     * @return bool
     */
    public function isPassedByReference()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function canBePassedByValue()
    {
    }
    /**
     * @tentative-return-type
     * @return ReflectionFunctionAbstract
     */
    public function getDeclaringFunction()
    {
    }
    /**
     * @tentative-return-type
     * @return (ReflectionClass | null)
     */
    public function getDeclaringClass()
    {
    }
    /**
     * @tentative-return-type
     * @deprecated Use ReflectionParameter::getType() instead
     * @return (ReflectionClass | null)
     */
    public function getClass()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function hasType()
    {
    }
    /**
     * @tentative-return-type
     * @return (ReflectionType | null)
     */
    public function getType()
    {
    }
    /**
     * @tentative-return-type
     * @deprecated Use ReflectionParameter::getType() instead
     * @return bool
     */
    public function isArray()
    {
    }
    /**
     * @tentative-return-type
     * @deprecated Use ReflectionParameter::getType() instead
     * @return bool
     */
    public function isCallable()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function allowsNull()
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function getPosition()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function isOptional()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function isDefaultValueAvailable()
    {
    }
    /**
     * @tentative-return-type
     * @return mixed
     */
    public function getDefaultValue()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function isDefaultValueConstant()
    {
    }
    /**
     * @tentative-return-type
     * @return (string | null)
     */
    public function getDefaultValueConstantName()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function isVariadic()
    {
    }
    public function isPromoted() : bool
    {
    }
    /** @return ReflectionAttribute[] */
    public function getAttributes(?string $name = null, int $flags = 0) : array
    {
    }
}