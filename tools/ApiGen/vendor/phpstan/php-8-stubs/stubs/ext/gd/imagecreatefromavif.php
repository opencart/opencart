<?php 

#ifdef HAVE_GD_AVIF
/** @refcount 1 */
#[\Since('8.1')]
function imagecreatefromavif(string $filename) : \GdImage|false
{
}