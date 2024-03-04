<?php 

/** @param resource $ftp */
#[\Until('8.1')]
function ftp_nb_continue($ftp) : int
{
}
#[\Since('8.1')]
function ftp_nb_continue(\FTP\Connection $ftp) : int
{
}