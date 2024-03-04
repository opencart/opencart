<?php 

/**
 * @param resource $imap
 */
#[\Until('8.1')]
function imap_undelete($imap, string $message_nums, int $flags = 0) : bool
{
}
#[\Since('8.1')]
function imap_undelete(\IMAP\Connection $imap, string $message_nums, int $flags = 0) : bool
{
}