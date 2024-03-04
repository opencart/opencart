<?php 

/** @param resource $ftp */
#[\Until('8.1')]
function ftp_site($ftp, string $command) : bool
{
}
#[\Since('8.1')]
function ftp_site(\FTP\Connection $ftp, string $command) : bool
{
}