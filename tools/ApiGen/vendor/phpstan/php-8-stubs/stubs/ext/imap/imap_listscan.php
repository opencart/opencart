<?php 

/** @param resource $imap */
#[\Until('8.1')]
function imap_listscan($imap, string $reference, string $pattern, string $content) : array|false
{
}
#[\Since('8.1')]
function imap_listscan(\IMAP\Connection $imap, string $reference, string $pattern, string $content) : array|false
{
}