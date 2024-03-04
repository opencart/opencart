<?php 

#ifdef HAVE_GD_PNG
/** @param resource|string|null $file */
function imagepng(\GdImage $image, $file = null, int $quality = -1, int $filters = -1) : bool
{
}