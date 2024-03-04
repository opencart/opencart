<?php 

#[\Until('8.2')]
function iterator_count(\Traversable $iterator) : int
{
}
#[\Since('8.2')]
function iterator_count(iterable $iterator) : int
{
}