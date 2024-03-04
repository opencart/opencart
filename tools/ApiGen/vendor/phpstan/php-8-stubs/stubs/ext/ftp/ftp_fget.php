<?php 

/**
 * @param resource $ftp
 * @param resource $stream
 */
#[\Until('8.1')]
function ftp_fget($ftp, $stream, string $remote_filename, int $mode = FTP_BINARY, int $offset = 0) : bool
{
}
/** @param resource $stream */
#[\Since('8.1')]
function ftp_fget(\FTP\Connection $ftp, $stream, string $remote_filename, int $mode = FTP_BINARY, int $offset = 0) : bool
{
}