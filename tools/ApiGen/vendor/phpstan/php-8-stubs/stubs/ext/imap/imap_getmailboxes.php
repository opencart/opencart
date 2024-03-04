<?php 

/** @param resource $imap */
#[\Until('8.1')]
function imap_getmailboxes($imap, string $reference, string $pattern) : array|false
{
}
#[\Since('8.1')]
function imap_getmailboxes(\IMAP\Connection $imap, string $reference, string $pattern) : array|false
{
}