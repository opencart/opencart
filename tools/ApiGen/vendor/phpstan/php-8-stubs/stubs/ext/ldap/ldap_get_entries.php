<?php 

/**
 * @param resource $ldap
 * @param resource $result
 */
#[\Until('8.1')]
function ldap_get_entries($ldap, $result) : array|false
{
}
/**
 * @return array<int|string, int|array>|false
 * @refcount 1
 */
#[\Since('8.1')]
function ldap_get_entries(\LDAP\Connection $ldap, \LDAP\Result $result) : array|false
{
}