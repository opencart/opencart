<?php 

#if defined(HAVE_IMAP2000) || defined(HAVE_IMAP2001)
/** @param resource $imap */
#[\Until('8.1')]
function imap_get_quota($imap, string $quota_root) : array|false
{
}
#if defined(HAVE_IMAP2000) || defined(HAVE_IMAP2001)
#[\Since('8.1')]
function imap_get_quota(\IMAP\Connection $imap, string $quota_root) : array|false
{
}