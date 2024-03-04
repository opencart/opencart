<?php 

#ifdef PHP_WIN32
function imagegrabwindow(int $handle, bool $client_area = false) : \GdImage|false
{
}