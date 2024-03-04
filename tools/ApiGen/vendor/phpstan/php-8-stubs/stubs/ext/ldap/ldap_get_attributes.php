<?php 

/**
 * @param resource $ldap
 * @param resource $entry
 */
#[\Until('8.1')]
function ldap_get_attributes($ldap, $entry) : array
{
}
/**
 * @return array<int|string, int|string|array>
 * @refcount 1
 */
#[\Since('8.1')]
function ldap_get_attributes(\LDAP\Connection $ldap, \LDAP\ResultEntry $entry) : array
{
}