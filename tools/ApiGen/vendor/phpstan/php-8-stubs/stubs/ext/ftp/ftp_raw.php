<?php 

/** @param resource $ftp */
#[\Until('8.1')]
function ftp_raw($ftp, string $command) : ?array
{
}
/**
 * @return array<int, string>|null
 * @refcount 1
 */
#[\Since('8.1')]
function ftp_raw(\FTP\Connection $ftp, string $command) : ?array
{
}