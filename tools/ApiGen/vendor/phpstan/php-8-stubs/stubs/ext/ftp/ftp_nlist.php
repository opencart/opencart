<?php 

/** @param resource $ftp */
#[\Until('8.1')]
function ftp_nlist($ftp, string $directory) : array|false
{
}
/**
 * @return array<int, string>|false
 * @refcount 1
 */
#[\Since('8.1')]
function ftp_nlist(\FTP\Connection $ftp, string $directory) : array|false
{
}