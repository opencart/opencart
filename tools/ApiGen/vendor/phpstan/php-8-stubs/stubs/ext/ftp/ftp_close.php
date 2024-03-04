<?php 

/** @param resource $ftp */
#[\Until('8.1')]
function ftp_close($ftp) : bool
{
}
#[\Since('8.1')]
function ftp_close(\FTP\Connection $ftp) : bool
{
}