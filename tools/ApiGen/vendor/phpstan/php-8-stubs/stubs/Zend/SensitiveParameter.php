<?php 

/**
 * @strict-properties
 */
#[\Attribute(Attribute::TARGET_PARAMETER)]
#[\Since('8.2')]
final class SensitiveParameter
{
    public function __construct()
    {
    }
}