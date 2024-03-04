<?php 

/** @param resource $ftp */
#[\Until('8.1')]
function ftp_systype($ftp) : string|false
{
}
#[\Since('8.1')]
function ftp_systype(\FTP\Connection $ftp) : string|false
{
}