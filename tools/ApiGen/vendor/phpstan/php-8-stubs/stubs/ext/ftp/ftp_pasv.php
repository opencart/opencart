<?php 

/** @param resource $ftp */
#[\Until('8.1')]
function ftp_pasv($ftp, bool $enable) : bool
{
}
#[\Since('8.1')]
function ftp_pasv(\FTP\Connection $ftp, bool $enable) : bool
{
}