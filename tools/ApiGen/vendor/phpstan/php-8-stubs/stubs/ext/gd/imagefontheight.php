<?php 

#[\Until('8.1')]
function imagefontheight(int $font) : int
{
}
#[\Since('8.1')]
function imagefontheight(\GdFont|int $font) : int
{
}