<?php 

/** @param resource $ftp */
#[\Until('8.1')]
function ftp_pwd($ftp) : string|false
{
}
#[\Since('8.1')]
function ftp_pwd(\FTP\Connection $ftp) : string|false
{
}