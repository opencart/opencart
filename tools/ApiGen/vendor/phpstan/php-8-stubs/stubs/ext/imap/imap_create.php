<?php 

/**
 * @param resource $imap
 * @alias imap_createmailbox
 */
#[\Until('8.1')]
function imap_create($imap, string $mailbox) : bool
{
}
/** @alias imap_createmailbox */
#[\Since('8.1')]
function imap_create(\IMAP\Connection $imap, string $mailbox) : bool
{
}