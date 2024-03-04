<?php 

/** @param resource $ftp */
#[\Until('8.1')]
function ftp_rename($ftp, string $from, string $to) : bool
{
}
#[\Since('8.1')]
function ftp_rename(\FTP\Connection $ftp, string $from, string $to) : bool
{
}