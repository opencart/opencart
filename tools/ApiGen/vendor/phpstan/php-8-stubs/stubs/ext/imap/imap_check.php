<?php 

/** @param resource $imap */
#[\Until('8.1')]
function imap_check($imap) : \stdClass|false
{
}
#[\Since('8.1')]
function imap_check(\IMAP\Connection $imap) : \stdClass|false
{
}