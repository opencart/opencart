<?php 

/** @param resource $imap */
#[\Until('8.1')]
function imap_subscribe($imap, string $mailbox) : bool
{
}
#[\Since('8.1')]
function imap_subscribe(\IMAP\Connection $imap, string $mailbox) : bool
{
}