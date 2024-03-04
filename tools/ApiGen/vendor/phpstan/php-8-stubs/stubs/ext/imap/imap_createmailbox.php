<?php 

/** @param resource $imap */
#[\Until('8.1')]
function imap_createmailbox($imap, string $mailbox) : bool
{
}
#[\Since('8.1')]
function imap_createmailbox(\IMAP\Connection $imap, string $mailbox) : bool
{
}