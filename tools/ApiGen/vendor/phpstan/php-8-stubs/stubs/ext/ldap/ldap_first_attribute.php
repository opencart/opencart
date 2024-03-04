<?php 

/**
 * @param resource $ldap
 * @param resource $entry
 */
#[\Until('8.1')]
function ldap_first_attribute($ldap, $entry) : string|false
{
}
#[\Since('8.1')]
function ldap_first_attribute(\LDAP\Connection $ldap, \LDAP\ResultEntry $entry) : string|false
{
}