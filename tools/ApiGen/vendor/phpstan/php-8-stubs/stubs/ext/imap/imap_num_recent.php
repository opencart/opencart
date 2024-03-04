<?php 

/** @param resource $imap */
#[\Until('8.1')]
function imap_num_recent($imap) : int
{
}
#[\Since('8.1')]
function imap_num_recent(\IMAP\Connection $imap) : int
{
}