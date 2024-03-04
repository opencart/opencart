<?php 

#endif
/** @param resource $ftp */
#[\Until('8.1')]
function ftp_login($ftp, string $username, string $password) : bool
{
}
#endif
#[\Since('8.1')]
function ftp_login(\FTP\Connection $ftp, string $username, string $password) : bool
{
}