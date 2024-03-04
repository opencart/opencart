<?php 

/**
 * @param resource $ldap
 * @param resource $entry
 */
#[\Until('8.1')]
function ldap_get_dn($ldap, $entry) : string|false
{
}
#[\Since('8.1')]
function ldap_get_dn(\LDAP\Connection $ldap, \LDAP\ResultEntry $entry) : string|false
{
}