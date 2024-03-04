<?php 

/** @param resource $ftp */
#[\Until('8.1')]
function ftp_nb_get($ftp, string $local_filename, string $remote_filename, int $mode = FTP_BINARY, int $offset = 0) : int
{
}
#[\Since('8.1')]
function ftp_nb_get(\FTP\Connection $ftp, string $local_filename, string $remote_filename, int $mode = FTP_BINARY, int $offset = 0) : int|false
{
}