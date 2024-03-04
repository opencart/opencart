<?php 

/** @param resource $ftp */
#[\Until('8.1')]
function ftp_size($ftp, string $filename) : int
{
}
#[\Since('8.1')]
function ftp_size(\FTP\Connection $ftp, string $filename) : int
{
}