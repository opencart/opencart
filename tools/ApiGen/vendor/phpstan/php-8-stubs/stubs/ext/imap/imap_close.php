<?php 

/**
 * @param resource $imap
 */
#[\Until('8.1')]
function imap_close($imap, int $flags = 0) : bool
{
}
#[\Since('8.1')]
function imap_close(\IMAP\Connection $imap, int $flags = 0) : bool
{
}