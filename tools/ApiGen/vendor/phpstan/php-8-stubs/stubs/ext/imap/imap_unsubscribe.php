<?php 

/** @param resource $imap */
#[\Until('8.1')]
function imap_unsubscribe($imap, string $mailbox) : bool
{
}
#[\Since('8.1')]
function imap_unsubscribe(\IMAP\Connection $imap, string $mailbox) : bool
{
}