<?php 

#[\Until('8.2')]
function imagecolorset(\GdImage $image, int $color, int $red, int $green, int $blue, int $alpha = 0) : ?bool
{
}
#[\Since('8.2')]
function imagecolorset(\GdImage $image, int $color, int $red, int $green, int $blue, int $alpha = 0) : false|null
{
}