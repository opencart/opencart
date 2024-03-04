<?php 

class ReflectionClass implements \Reflector
{
    #[\Until('8.1')]
    private final function __clone() : void
    {
    }
    #[\Since('8.1')]
    private function __clone() : void
    {
    }
    public function __construct(object|string $objectOrClass)
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
    public function isAnonymous()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function isInstantiable()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function isCloneable()
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
     * @return (int | false)
     */
    public function getStartLine()
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
     * @return (string | false)
     */
    public function getDocComment()
    {
    }
    /**
     * @tentative-return-type
     * @return (ReflectionMethod | null)
     */
    public function getConstructor()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function hasMethod(string $name)
    {
    }
    /**
     * @tentative-return-type
     * @return ReflectionMethod
     */
    public function getMethod(string $name)
    {
    }
    /**
     * @tentative-return-type
     * @return ReflectionMethod[]
     */
    public function getMethods(?int $filter = null)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function hasProperty(string $name)
    {
    }
    /**
     * @tentative-return-type
     * @return ReflectionProperty
     */
    public function getProperty(string $name)
    {
    }
    /**
     * @tentative-return-type
     * @return ReflectionProperty[]
     */
    public function getProperties(?int $filter = null)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function hasConstant(string $name)
    {
    }
    /**
     * @tentative-return-type
     * @return array
     */
    public function getConstants(?int $filter = null)
    {
    }
    /**
     * @tentative-return-type
     * @return ReflectionClassConstant[]
     */
    public function getReflectionConstants(?int $filter = null)
    {
    }
    /**
     * @tentative-return-type
     * @return mixed
     */
    public function getConstant(string $name)
    {
    }
    /**
     * @tentative-return-type
     * @return (ReflectionClassConstant | false)
     */
    public function getReflectionConstant(string $name)
    {
    }
    /**
     * @tentative-return-type
     * @return ReflectionClass[]
     */
    public function getInterfaces()
    {
    }
    /**
     * @tentative-return-type
     * @return string[]
     */
    public function getInterfaceNames()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function isInterface()
    {
    }
    /**
     * @tentative-return-type
     * @return ReflectionClass[]
     */
    public function getTraits()
    {
    }
    /**
     * @tentative-return-type
     * @return string[]
     */
    public function getTraitNames()
    {
    }
    /**
     * @tentative-return-type
     * @return string[]
     */
    public function getTraitAliases()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function isTrait()
    {
    }
    #[\Since('8.1')]
    public function isEnum() : bool
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
    #[\Since('8.2')]
    public function isReadOnly() : bool
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
     * @return bool
     */
    public function isInstance(object $object)
    {
    }
    /**
     * @tentative-return-type
     * @return object
     */
    public function newInstance(mixed ...$args)
    {
    }
    /**
     * @tentative-return-type
     * @return object
     */
    public function newInstanceWithoutConstructor()
    {
    }
    /**
     * @tentative-return-type
     * @return object
     */
    public function newInstanceArgs(array $args = [])
    {
    }
    /**
     * @tentative-return-type
     * @return (ReflectionClass | false)
     */
    public function getParentClass()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function isSubclassOf(ReflectionClass|string $class)
    {
    }
    /**
     * @tentative-return-type
     * @return (array | null)
     */
    public function getStaticProperties()
    {
    }
    /**
     * @tentative-return-type
     * @return mixed
     */
    public function getStaticPropertyValue(string $name, mixed $default = UNKNOWN)
    {
    }
    /**
     * @tentative-return-type
     * @return void
     */
    public function setStaticPropertyValue(string $name, mixed $value)
    {
    }
    /**
     * @tentative-return-type
     * @return array
     */
    public function getDefaultProperties()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function isIterable()
    {
    }
    /**
     * @tentative-return-type
     * @alias ReflectionClass::isIterable
     * @return bool
     */
    public function isIterateable()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function implementsInterface(ReflectionClass|string $interface)
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
     * @return bool
     */
    public function inNamespace()
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
     * @return string
     */
    public function getShortName()
    {
    }
    /** @return ReflectionAttribute[] */
    public function getAttributes(?string $name = null, int $flags = 0) : array
    {
    }
}