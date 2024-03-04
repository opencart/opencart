<?php 

#ifdef HAVE_GD_AVIF
/** @param resource|string|null $file */
#[\Since('8.1')]
function imageavif(\GdImage $image, $file = null, int $quality = -1, int $speed = -1) : bool
{
}