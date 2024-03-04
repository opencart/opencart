<?php 

/** @param resource $imap */
#[\Until('8.1')]
function imap_fetch_overview($imap, string $sequence, int $flags = 0) : array|false
{
}
#[\Since('8.1')]
function imap_fetch_overview(\IMAP\Connection $imap, string $sequence, int $flags = 0) : array|false
{
}