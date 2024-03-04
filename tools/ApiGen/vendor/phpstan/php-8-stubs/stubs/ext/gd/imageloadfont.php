<?php 

#[\Until('8.1')]
function imageloadfont(string $filename) : int|false
{
}
#[\Since('8.1')]
function imageloadfont(string $filename) : \GdFont|false
{
}