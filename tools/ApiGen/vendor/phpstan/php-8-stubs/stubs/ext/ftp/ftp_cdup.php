<?php 

/** @param resource $ftp */
#[\Until('8.1')]
function ftp_cdup($ftp) : bool
{
}
#[\Since('8.1')]
function ftp_cdup(\FTP\Connection $ftp) : bool
{
}