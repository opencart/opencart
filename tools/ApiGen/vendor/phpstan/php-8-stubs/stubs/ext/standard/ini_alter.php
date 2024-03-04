<?php 

/** @alias ini_set */
#[\Until('8.1')]
function ini_alter(string $option, string $value) : string|false
{
}
/** @alias ini_set */
#[\Since('8.1')]
function ini_alter(string $option, string|int|float|bool|null $value) : string|false
{
}