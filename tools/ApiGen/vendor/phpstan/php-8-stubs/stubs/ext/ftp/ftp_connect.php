<?php 

/** @generate-function-entries */
/** @return resource|false */
#[\Until('8.1')]
function ftp_connect(string $hostname, int $port = 21, int $timeout = 90)
{
}
#[\Since('8.1')]
function ftp_connect(string $hostname, int $port = 21, int $timeout = 90) : \FTP\Connection|false
{
}