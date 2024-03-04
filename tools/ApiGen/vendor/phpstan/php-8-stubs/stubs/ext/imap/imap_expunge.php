<?php 

/**
 * @param resource $imap
 */
#[\Until('8.1')]
function imap_expunge($imap) : bool
{
}
#[\Since('8.1')]
function imap_expunge(\IMAP\Connection $imap) : bool
{
}