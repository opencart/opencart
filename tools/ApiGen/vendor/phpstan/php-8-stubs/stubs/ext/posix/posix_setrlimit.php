<?php 

#endif
#ifdef HAVE_SETRLIMIT
function posix_setrlimit(int $resource, int $soft_limit, int $hard_limit) : bool
{
}