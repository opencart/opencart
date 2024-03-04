<?php 

class ReflectionFunction extends \ReflectionFunctionAbstract
{
    public function __construct(Closure|string $function)
    {
    }
    public function __toString() : string
    {
    }
    #[\Since('8.2')]
    public function isAnonymous() : bool
    {
    }
    /**
     * @tentative-return-type
     * @deprecated ReflectionFunction can no longer be constructed for disabled functions
     * @return bool
     */
    public function isDisabled()
    {
    }
    /**
     * @tentative-return-type
     * @return mixed
     */
    public function invoke(mixed ...$args)
    {
    }
    /**
     * @tentative-return-type
     * @return mixed
     */
    public function invokeArgs(array $args)
    {
    }
    /**
     * @tentative-return-type
     * @return Closure
     */
    public function getClosure()
    {
    }
}