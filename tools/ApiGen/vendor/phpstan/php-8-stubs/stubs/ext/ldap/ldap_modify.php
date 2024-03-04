<?php 

/**
 * @param resource $ldap
 * @alias ldap_mod_replace
 */
#[\Until('8.1')]
function ldap_modify($ldap, string $dn, array $entry, ?array $controls = null) : bool
{
}
/** @alias ldap_mod_replace */
#[\Since('8.1')]
function ldap_modify(\LDAP\Connection $ldap, string $dn, array $entry, ?array $controls = null) : bool
{
}