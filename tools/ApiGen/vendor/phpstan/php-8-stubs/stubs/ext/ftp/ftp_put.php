<?php 

/** @param resource $ftp */
#[\Until('8.1')]
function ftp_put($ftp, string $remote_filename, string $local_filename, int $mode = FTP_BINARY, int $offset = 0) : bool
{
}
#[\Since('8.1')]
function ftp_put(\FTP\Connection $ftp, string $remote_filename, string $local_filename, int $mode = FTP_BINARY, int $offset = 0) : bool
{
}