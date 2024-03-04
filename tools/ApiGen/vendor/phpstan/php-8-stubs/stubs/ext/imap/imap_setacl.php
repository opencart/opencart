<?php 

/** @param resource $imap */
#[\Until('8.1')]
function imap_setacl($imap, string $mailbox, string $user_id, string $rights) : bool
{
}
#[\Since('8.1')]
function imap_setacl(\IMAP\Connection $imap, string $mailbox, string $user_id, string $rights) : bool
{
}