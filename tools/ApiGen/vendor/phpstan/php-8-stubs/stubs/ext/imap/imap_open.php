<?php 

/** @generate-function-entries */
/**
 * @return resource|false
 */
#[\Until('8.1')]
function imap_open(string $mailbox, string $user, string $password, int $flags = 0, int $retries = 0, array $options = [])
{
}
#[\Since('8.1')]
function imap_open(string $mailbox, string $user, string $password, int $flags = 0, int $retries = 0, array $options = []) : \IMAP\Connection|false
{
}