<?php 

/** @param resource $ftp */
#[\Until('8.1')]
function ftp_mkdir($ftp, string $directory) : string|false
{
}
#[\Since('8.1')]
function ftp_mkdir(\FTP\Connection $ftp, string $directory) : string|false
{
}