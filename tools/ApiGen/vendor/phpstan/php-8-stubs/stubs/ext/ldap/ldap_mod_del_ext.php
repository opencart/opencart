<?php 

/**
 * @param resource $ldap
 * @return resource|false
 */
#[\Until('8.1')]
function ldap_mod_del_ext($ldap, string $dn, array $entry, ?array $controls = null)
{
}
#[\Since('8.1')]
function ldap_mod_del_ext(\LDAP\Connection $ldap, string $dn, array $entry, ?array $controls = null) : \LDAP\Result|false
{
}