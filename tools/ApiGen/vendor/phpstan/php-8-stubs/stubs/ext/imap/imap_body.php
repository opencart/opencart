<?php 

/** @param resource $imap */
#[\Until('8.1')]
function imap_body($imap, int $message_num, int $flags = 0) : string|false
{
}
#[\Since('8.1')]
function imap_body(\IMAP\Connection $imap, int $message_num, int $flags = 0) : string|false
{
}