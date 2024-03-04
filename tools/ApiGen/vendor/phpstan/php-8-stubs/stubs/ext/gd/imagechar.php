<?php 

#[\Until('8.1')]
function imagechar(\GdImage $image, int $font, int $x, int $y, string $char, int $color) : bool
{
}
#[\Since('8.1')]
function imagechar(\GdImage $image, \GdFont|int $font, int $x, int $y, string $char, int $color) : bool
{
}