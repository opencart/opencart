<?php 

/**
 * @param resource $imap
 */
#[\Until('8.1')]
function imap_delete($imap, string $message_nums, int $flags = 0) : bool
{
}
#[\Since('8.1')]
function imap_delete(\IMAP\Connection $imap, string $message_nums, int $flags = 0) : bool
{
}