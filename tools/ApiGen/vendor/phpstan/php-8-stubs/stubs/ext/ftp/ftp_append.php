<?php 

/** @param resource $ftp */
#[\Until('8.1')]
function ftp_append($ftp, string $remote_filename, string $local_filename, int $mode = FTP_BINARY) : bool
{
}
#[\Since('8.1')]
function ftp_append(\FTP\Connection $ftp, string $remote_filename, string $local_filename, int $mode = FTP_BINARY) : bool
{
}