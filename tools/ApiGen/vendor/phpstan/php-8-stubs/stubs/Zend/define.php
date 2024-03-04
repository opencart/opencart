<?php 

/** @param mixed $value */
#[\Until('8.1')]
function define(string $constant_name, $value, bool $case_insensitive = false) : bool
{
}
#[\Since('8.1')]
function define(string $constant_name, mixed $value, bool $case_insensitive = false) : bool
{
}