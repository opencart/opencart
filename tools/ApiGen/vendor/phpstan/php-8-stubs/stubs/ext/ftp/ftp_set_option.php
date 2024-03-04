<?php 

/**
 * @param resource $ftp
 * @param int|bool $value
 */
#[\Until('8.1')]
function ftp_set_option($ftp, int $option, $value) : bool
{
}
/** @param int|bool $value */
#[\Since('8.1')]
function ftp_set_option(\FTP\Connection $ftp, int $option, $value) : bool
{
}