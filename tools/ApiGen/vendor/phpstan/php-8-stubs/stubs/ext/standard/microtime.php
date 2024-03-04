<?php 

/* microtime.c */
#ifdef HAVE_GETTIMEOFDAY
function microtime(bool $as_float = false) : string|float
{
}