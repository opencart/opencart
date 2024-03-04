<?php 

/** @param resource $ftp */
#[\Until('8.1')]
function ftp_mdtm($ftp, string $filename) : int
{
}
#[\Since('8.1')]
function ftp_mdtm(\FTP\Connection $ftp, string $filename) : int
{
}