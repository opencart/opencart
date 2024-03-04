<?php 

#endif
/* net.c */
#if defined(PHP_WIN32) || HAVE_GETIFADDRS || defined(__PASE__)
function net_get_interfaces() : array|false
{
}