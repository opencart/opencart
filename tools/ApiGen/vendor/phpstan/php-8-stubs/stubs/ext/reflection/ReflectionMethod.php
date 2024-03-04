<?php 

class ReflectionMethod extends \ReflectionFunctionAbstract
{
    public function __construct(object|string $objectOrMethod, ?string $method = null)
    {
    }
    public function __toString() : string
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function isPublic()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function isPrivate()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function isProtected()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function isAbstract()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function isFinal()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function isConstructor()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function isDestructor()
    {
    }
    /**
     * @tentative-return-type
     * @return Closure
     */
    public function getClosure(?object $object = null)
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function getModifiers()
    {
    }
    /**
     * @tentative-return-type
     * @return mixed
     */
    public function invoke(?object $object, mixed ...$args)
    {
    }
    /**
     * @tentative-return-type
     * @return mixed
     */
    public function invokeArgs(?object $object, array $args)
    {
    }
    /**
     * @tentative-return-type
     * @return ReflectionClass
     */
    public function getDeclaringClass()
    {
    }
    /**
     * @tentative-return-type
     * @return ReflectionMethod
     */
    public function getPrototype()
    {
    }
    #[\Since('8.2')]
    public function hasPrototype() : bool
    {
    }
    /**
     * @tentative-return-type
     * @return void
     */
    public function setAccessible(bool $accessible)
    {
    }
}