<?php 

#if HAVE_NANOSLEEP
/**
 * @refcount 1
 */
function time_nanosleep(int $seconds, int $nanoseconds) : array|bool
{
}