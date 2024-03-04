<?php 

/**
 * @param resource $ftp
 * @param resource $stream
 */
#[\Until('8.1')]
function ftp_nb_fput($ftp, string $remote_filename, $stream, int $mode = FTP_BINARY, int $offset = 0) : int
{
}
/** @param resource $stream */
#[\Since('8.1')]
function ftp_nb_fput(\FTP\Connection $ftp, string $remote_filename, $stream, int $mode = FTP_BINARY, int $offset = 0) : int
{
}