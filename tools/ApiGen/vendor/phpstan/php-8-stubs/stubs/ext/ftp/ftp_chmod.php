<?php 

/** @param resource $ftp */
#[\Until('8.1')]
function ftp_chmod($ftp, int $permissions, string $filename) : int|false
{
}
#[\Since('8.1')]
function ftp_chmod(\FTP\Connection $ftp, int $permissions, string $filename) : int|false
{
}