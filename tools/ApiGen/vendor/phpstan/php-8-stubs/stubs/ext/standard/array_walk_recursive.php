<?php 

#[\Until('8.2')]
function array_walk_recursive(array|object &$array, callable $callback, mixed $arg = UNKNOWN) : bool
{
}
#[\Since('8.2')]
function array_walk_recursive(array|object &$array, callable $callback, mixed $arg = UNKNOWN) : true
{
}