<?php 

#[\Until('8.2')]
function uasort(array &$array, callable $callback) : bool
{
}
#[\Since('8.2')]
function uasort(array &$array, callable $callback) : true
{
}