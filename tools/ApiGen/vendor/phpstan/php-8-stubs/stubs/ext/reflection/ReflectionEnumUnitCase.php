<?php 

#[\Since('8.1')]
class ReflectionEnumUnitCase extends \ReflectionClassConstant
{
    public function __construct(object|string $class, string $constant)
    {
    }
    public function getEnum() : ReflectionEnum
    {
    }
    /**
     * @implementation-alias ReflectionClassConstant::getValue
     * @no-verify
     */
    public function getValue() : UnitEnum
    {
    }
}