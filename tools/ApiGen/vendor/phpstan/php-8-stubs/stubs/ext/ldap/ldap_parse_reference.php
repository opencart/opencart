<?php 

#ifdef HAVE_LDAP_PARSE_REFERENCE
/**
 * @param resource $ldap
 * @param resource $entry
 * @param array $referrals
 */
#[\Until('8.1')]
function ldap_parse_reference($ldap, $entry, &$referrals) : bool
{
}
#ifdef HAVE_LDAP_PARSE_REFERENCE
/** @param array $referrals */
#[\Since('8.1')]
function ldap_parse_reference(\LDAP\Connection $ldap, \LDAP\ResultEntry $entry, &$referrals) : bool
{
}