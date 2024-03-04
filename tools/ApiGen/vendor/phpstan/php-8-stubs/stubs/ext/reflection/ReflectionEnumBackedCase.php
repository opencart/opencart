<?php 

#[\Since('8.1')]
class ReflectionEnumBackedCase extends \ReflectionEnumUnitCase
{
    public function __construct(object|string $class, string $constant)
    {
    }
    public function getBackingValue() : int|string
    {
    }
}