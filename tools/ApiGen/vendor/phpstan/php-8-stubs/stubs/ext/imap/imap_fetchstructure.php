<?php 

/** @param resource $imap */
#[\Until('8.1')]
function imap_fetchstructure($imap, int $message_num, int $flags = 0) : \stdClass|false
{
}
#[\Since('8.1')]
function imap_fetchstructure(\IMAP\Connection $imap, int $message_num, int $flags = 0) : \stdClass|false
{
}