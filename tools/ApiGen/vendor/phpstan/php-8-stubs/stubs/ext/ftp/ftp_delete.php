<?php 

/** @param resource $ftp */
#[\Until('8.1')]
function ftp_delete($ftp, string $filename) : bool
{
}
#[\Since('8.1')]
function ftp_delete(\FTP\Connection $ftp, string $filename) : bool
{
}