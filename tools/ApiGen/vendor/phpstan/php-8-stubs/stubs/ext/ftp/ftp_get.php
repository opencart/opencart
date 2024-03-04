<?php 

/** @param resource $ftp */
#[\Until('8.1')]
function ftp_get($ftp, string $local_filename, string $remote_filename, int $mode = FTP_BINARY, int $offset = 0) : bool
{
}
#[\Since('8.1')]
function ftp_get(\FTP\Connection $ftp, string $local_filename, string $remote_filename, int $mode = FTP_BINARY, int $offset = 0) : bool
{
}