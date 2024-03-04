<?php 

/** @param resource $imap */
#[\Until('8.1')]
function imap_mailboxmsginfo($imap) : \stdClass
{
}
#[\Since('8.1')]
function imap_mailboxmsginfo(\IMAP\Connection $imap) : \stdClass
{
}