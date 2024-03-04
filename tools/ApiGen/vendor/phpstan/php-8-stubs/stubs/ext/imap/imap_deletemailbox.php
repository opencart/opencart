<?php 

/** @param resource $imap */
#[\Until('8.1')]
function imap_deletemailbox($imap, string $mailbox) : bool
{
}
#[\Since('8.1')]
function imap_deletemailbox(\IMAP\Connection $imap, string $mailbox) : bool
{
}