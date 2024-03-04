<?php 

#[\Until('8.1')]
function imagestringup(\GdImage $image, int $font, int $x, int $y, string $string, int $color) : bool
{
}
#[\Since('8.1')]
function imagestringup(\GdImage $image, \GdFont|int $font, int $x, int $y, string $string, int $color) : bool
{
}