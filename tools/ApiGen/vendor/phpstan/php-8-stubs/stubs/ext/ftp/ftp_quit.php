<?php 

/**
 * @param resource $ftp
 * @alias ftp_close
 */
#[\Until('8.1')]
function ftp_quit($ftp) : bool
{
}
/** @alias ftp_close */
#[\Since('8.1')]
function ftp_quit(\FTP\Connection $ftp) : bool
{
}