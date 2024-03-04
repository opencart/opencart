<?php 

/**
 * @param resource $ldap
 * @return resource|false
 */
#[\Until('8.1')]
function ldap_delete_ext($ldap, string $dn, ?array $controls = null)
{
}
#[\Since('8.1')]
function ldap_delete_ext(\LDAP\Connection $ldap, string $dn, ?array $controls = null) : \LDAP\Result|false
{
}