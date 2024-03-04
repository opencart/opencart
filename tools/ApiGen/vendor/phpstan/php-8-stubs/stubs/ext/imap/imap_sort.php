<?php 

/** @param resource $imap */
#[\Until('8.1')]
function imap_sort($imap, int $criteria, bool $reverse, int $flags = 0, ?string $search_criteria = null, ?string $charset = null) : array|false
{
}
#[\Since('8.1')]
function imap_sort(\IMAP\Connection $imap, int $criteria, bool $reverse, int $flags = 0, ?string $search_criteria = null, ?string $charset = null) : array|false
{
}