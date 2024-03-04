<?php 

/** @param resource $imap */
#[\Until('8.1')]
function imap_num_msg($imap) : int|false
{
}
#[\Since('8.1')]
function imap_num_msg(\IMAP\Connection $imap) : int|false
{
}