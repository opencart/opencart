<?php 

/** @param resource $imap */
#[\Until('8.1')]
function imap_getacl($imap, string $mailbox) : array|false
{
}
#[\Since('8.1')]
function imap_getacl(\IMAP\Connection $imap, string $mailbox) : array|false
{
}