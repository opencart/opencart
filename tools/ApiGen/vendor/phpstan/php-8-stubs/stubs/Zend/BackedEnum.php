<?php 

#[\Since('8.1')]
interface BackedEnum extends \UnitEnum
{
    public static function from(int|string $value) : static;
    public static function tryFrom(int|string $value) : ?static;
}