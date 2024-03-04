<?php 

/* syslog.c */
#ifdef HAVE_SYSLOG_H
#[\Until('8.2')]
function openlog(string $prefix, int $flags, int $facility) : bool
{
}
/* syslog.c */
#ifdef HAVE_SYSLOG_H
#[\Since('8.2')]
function openlog(string $prefix, int $flags, int $facility) : true
{
}