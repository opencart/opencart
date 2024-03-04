<?php 

/** @param resource $ftp */
#[\Until('8.1')]
function ftp_exec($ftp, string $command) : bool
{
}
#[\Since('8.1')]
function ftp_exec(\FTP\Connection $ftp, string $command) : bool
{
}