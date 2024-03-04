<?php 

#ifdef HAVE_FTP_SSL
/** @return resource|false */
#[\Until('8.1')]
function ftp_ssl_connect(string $hostname, int $port = 21, int $timeout = 90)
{
}
#ifdef HAVE_FTP_SSL
#[\Since('8.1')]
function ftp_ssl_connect(string $hostname, int $port = 21, int $timeout = 90) : \FTP\Connection|false
{
}