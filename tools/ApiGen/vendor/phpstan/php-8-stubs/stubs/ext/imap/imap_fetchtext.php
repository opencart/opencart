<?php 

/**
 * @param resource $imap
 * @alias imap_body
 */
#[\Until('8.1')]
function imap_fetchtext($imap, int $message_num, int $flags = 0) : string|false
{
}
/** @alias imap_body */
#[\Since('8.1')]
function imap_fetchtext(\IMAP\Connection $imap, int $message_num, int $flags = 0) : string|false
{
}