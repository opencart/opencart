<?php 

/** @param resource $imap */
#[\Until('8.1')]
function imap_search($imap, string $criteria, int $flags = SE_FREE, string $charset = "") : array|false
{
}
#[\Since('8.1')]
function imap_search(\IMAP\Connection $imap, string $criteria, int $flags = SE_FREE, string $charset = "") : array|false
{
}