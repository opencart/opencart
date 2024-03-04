<?php 

#[\Until('8.1')]
function ini_set(string $option, string $value) : string|false
{
}
#[\Since('8.1')]
function ini_set(string $option, string|int|float|bool|null $value) : string|false
{
}