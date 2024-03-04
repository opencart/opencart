<?php 

/** @param resource $ftp */
#[\Until('8.1')]
function ftp_mlsd($ftp, string $directory) : array|false
{
}
/**
 * @return array<int, array>|false
 * @refcount 1
 */
#[\Since('8.1')]
function ftp_mlsd(\FTP\Connection $ftp, string $directory) : array|false
{
}