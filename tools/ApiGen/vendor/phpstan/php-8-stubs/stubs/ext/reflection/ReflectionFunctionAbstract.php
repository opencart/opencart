<?php 

abstract class ReflectionFunctionAbstract implements \Reflector
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
    public function inNamespace()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function isClosure()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function isDeprecated()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function isInternal()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function isUserDefined()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function isGenerator()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function isVariadic()
    {
    }
    /** @tentative-return-type */
    #[\Since('8.1')]
    public function isStatic() : bool
    {
    }
    /**
     * @tentative-return-type
     * @return (object | null)
     */
    public function getClosureThis()
    {
    }
    /**
     * @tentative-return-type
     * @return (ReflectionClass | null)
     */
    public function getClosureScopeClass()
    {
    }
    /**
     * @tentative-return-type
     * @return (ReflectionClass | null)
     */
    public function getClosureCalledClass()
    {
    }
    #[\Since('8.1')]
    public function getClosureUsedVariables() : array
    {
    }
    /**
     * @tentative-return-type
     * @return (string | false)
     */
    public function getDocComment()
    {
    }
    /**
     * @tentative-return-type
     * @return (int | false)
     */
    public function getEndLine()
    {
    }
    /**
     * @tentative-return-type
     * @return (ReflectionExtension | null)
     */
    public function getExtension()
    {
    }
    /**
     * @tentative-return-type
     * @return (string | false)
     */
    public function getExtensionName()
    {
    }
    /**
     * @tentative-return-type
     * @return (string | false)
     */
    public function getFileName()
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
    public function getNamespaceName()
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function getNumberOfParameters()
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function getNumberOfRequiredParameters()
    {
    }
    /**
     * @tentative-return-type
     * @return ReflectionParameter[]
     */
    public function getParameters()
    {
    }
    /**
     * @tentative-return-type
     * @return string
     */
    public function getShortName()
    {
    }
    /**
     * @tentative-return-type
     * @return (int | false)
     */
    public function getStartLine()
    {
    }
    /**
     * @tentative-return-type
     * @return array
     */
    public function getStaticVariables()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function returnsReference()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function hasReturnType()
    {
    }
    /**
     * @tentative-return-type
     * @return (ReflectionType | null)
     */
    public function getReturnType()
    {
    }
    #[\Since('8.1')]
    public function hasTentativeReturnType() : bool
    {
    }
    #[\Since('8.1')]
    public function getTentativeReturnType() : ?ReflectionType
    {
    }
    /** @return ReflectionAttribute[] */
    public function getAttributes(?string $name = null, int $flags = 0) : array
    {
    }
}