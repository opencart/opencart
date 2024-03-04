<?php 

/**
 * @param resource $ldap
 * @param resource $entry
 */
#[\Until('8.1')]
function ldap_get_values_len($ldap, $entry, string $attribute) : array|false
{
}
/**
 * @return array<int|string, int|string>|false
 * @refcount 1
 */
#[\Since('8.1')]
function ldap_get_values_len(\LDAP\Connection $ldap, \LDAP\ResultEntry $entry, string $attribute) : array|false
{
}