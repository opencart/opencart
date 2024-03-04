<?php 

#ifdef HAVE_MKFIFO
function posix_mkfifo(string $filename, int $permissions) : bool
{
}