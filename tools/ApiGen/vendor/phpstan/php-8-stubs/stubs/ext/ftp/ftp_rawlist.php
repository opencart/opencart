<?php 

/** @param resource $ftp */
#[\Until('8.1')]
function ftp_rawlist($ftp, string $directory, bool $recursive = false) : array|false
{
}
/**
 * @return array<int, string>|false
 * @refcount 1
 */
#[\Since('8.1')]
function ftp_rawlist(\FTP\Connection $ftp, string $directory, bool $recursive = false) : array|false
{
}