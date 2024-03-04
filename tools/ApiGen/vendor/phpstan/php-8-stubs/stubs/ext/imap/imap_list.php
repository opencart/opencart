<?php 

/** @param resource $imap */
#[\Until('8.1')]
function imap_list($imap, string $reference, string $pattern) : array|false
{
}
#[\Since('8.1')]
function imap_list(\IMAP\Connection $imap, string $reference, string $pattern) : array|false
{
}