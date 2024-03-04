<?php 

/** @param resource $ftp */
#[\Until('8.1')]
function ftp_rmdir($ftp, string $directory) : bool
{
}
#[\Since('8.1')]
function ftp_rmdir(\FTP\Connection $ftp, string $directory) : bool
{
}