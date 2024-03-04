<?php 

#[\Until('8.2')]
function usort(array &$array, callable $callback) : bool
{
}
#[\Since('8.2')]
function usort(array &$array, callable $callback) : true
{
}