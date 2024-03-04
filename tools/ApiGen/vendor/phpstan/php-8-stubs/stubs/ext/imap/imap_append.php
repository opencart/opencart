<?php 

/** @param resource $imap */
#[\Until('8.1')]
function imap_append($imap, string $folder, string $message, ?string $options = null, ?string $internal_date = null) : bool
{
}
#[\Since('8.1')]
function imap_append(\IMAP\Connection $imap, string $folder, string $message, ?string $options = null, ?string $internal_date = null) : bool
{
}