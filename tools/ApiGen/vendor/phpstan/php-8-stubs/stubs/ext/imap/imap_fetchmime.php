<?php 

/** @param resource $imap */
#[\Until('8.1')]
function imap_fetchmime($imap, int $message_num, string $section, int $flags = 0) : string|false
{
}
#[\Since('8.1')]
function imap_fetchmime(\IMAP\Connection $imap, int $message_num, string $section, int $flags = 0) : string|false
{
}