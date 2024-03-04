<?php 

/**
 * @param resource $imap
 */
#[\Until('8.1')]
function imap_gc($imap, int $flags) : bool
{
}
#[\Since('8.1')]
function imap_gc(\IMAP\Connection $imap, int $flags) : bool
{
}