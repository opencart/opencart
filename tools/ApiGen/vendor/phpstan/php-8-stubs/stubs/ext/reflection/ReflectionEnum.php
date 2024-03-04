<?php 

#[\Since('8.1')]
class ReflectionEnum extends \ReflectionClass
{
    public function __construct(object|string $objectOrClass)
    {
    }
    public function hasCase(string $name) : bool
    {
    }
    public function getCase(string $name) : ReflectionEnumUnitCase
    {
    }
    public function getCases() : array
    {
    }
    public function isBacked() : bool
    {
    }
    #[\Until('8.2')]
    public function getBackingType() : ?ReflectionType
    {
    }
    #[\Since('8.2')]
    public function getBackingType() : ?ReflectionNamedType
    {
    }
}