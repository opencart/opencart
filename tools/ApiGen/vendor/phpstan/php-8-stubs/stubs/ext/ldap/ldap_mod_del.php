<?php 

/** @param resource $ldap */
#[\Until('8.1')]
function ldap_mod_del($ldap, string $dn, array $entry, ?array $controls = null) : bool
{
}
#[\Since('8.1')]
function ldap_mod_del(\LDAP\Connection $ldap, string $dn, array $entry, ?array $controls = null) : bool
{
}