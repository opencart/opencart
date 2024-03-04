<?php 

#[\Until('8.2')]
function arsort(array &$array, int $flags = SORT_REGULAR) : bool
{
}
#[\Since('8.2')]
function arsort(array &$array, int $flags = SORT_REGULAR) : true
{
}