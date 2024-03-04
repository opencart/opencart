<?php 

/** @param resource $ftp */
#[\Until('8.1')]
function ftp_chdir($ftp, string $directory) : bool
{
}
#[\Since('8.1')]
function ftp_chdir(\FTP\Connection $ftp, string $directory) : bool
{
}