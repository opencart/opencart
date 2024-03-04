<?php 

/**
 * @param resource $ftp
 * @param string $response
 */
#[\Until('8.1')]
function ftp_alloc($ftp, int $size, &$response = null) : bool
{
}
/** @param string $response */
#[\Since('8.1')]
function ftp_alloc(\FTP\Connection $ftp, int $size, &$response = null) : bool
{
}