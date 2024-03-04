<?php 

#[\Until('8.1')]
function imagefontwidth(int $font) : int
{
}
#[\Since('8.1')]
function imagefontwidth(\GdFont|int $font) : int
{
}