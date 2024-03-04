<?php 

/**
 * @param resource $imap
 * @alias imap_renamemailbox
 */
#[\Until('8.1')]
function imap_rename($imap, string $from, string $to) : bool
{
}
/** @alias imap_renamemailbox */
#[\Since('8.1')]
function imap_rename(\IMAP\Connection $imap, string $from, string $to) : bool
{
}