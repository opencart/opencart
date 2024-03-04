<?php 

/**
 * @param resource $imap
 * @return stdClass|false
 */
#[\Until('8.1')]
function imap_status($imap, string $mailbox, int $flags)
{
}
#[\Since('8.1')]
function imap_status(\IMAP\Connection $imap, string $mailbox, int $flags) : \stdClass|false
{
}