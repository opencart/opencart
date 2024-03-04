<?php 

#endif
#ifdef HAVE_MKNOD
function posix_mknod(string $filename, int $flags, int $major = 0, int $minor = 0) : bool
{
}