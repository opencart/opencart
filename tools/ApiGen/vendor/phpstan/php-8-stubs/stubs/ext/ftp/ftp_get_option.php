<?php 

/** @param resource $ftp */
#[\Until('8.1')]
function ftp_get_option($ftp, int $option) : int|bool
{
}
#[\Since('8.1')]
function ftp_get_option(\FTP\Connection $ftp, int $option) : int|bool
{
}