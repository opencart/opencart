<?php 

/** @param resource $imap */
#[\Until('8.1')]
function imap_get_quotaroot($imap, string $mailbox) : array|false
{
}
#[\Since('8.1')]
function imap_get_quotaroot(\IMAP\Connection $imap, string $mailbox) : array|false
{
}