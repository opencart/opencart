<?php 

/** @param resource $imap */
#[\Until('8.1')]
function imap_ping($imap) : bool
{
}
#[\Since('8.1')]
function imap_ping(\IMAP\Connection $imap) : bool
{
}