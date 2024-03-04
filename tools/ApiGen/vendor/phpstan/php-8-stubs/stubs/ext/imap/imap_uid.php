<?php 

/** @param resource $imap */
#[\Until('8.1')]
function imap_uid($imap, int $message_num) : int|false
{
}
#[\Since('8.1')]
function imap_uid(\IMAP\Connection $imap, int $message_num) : int|false
{
}