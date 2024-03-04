<?php 

/** @param resource $imap */
#[\Until('8.1')]
function imap_headers($imap) : array|false
{
}
#[\Since('8.1')]
function imap_headers(\IMAP\Connection $imap) : array|false
{
}