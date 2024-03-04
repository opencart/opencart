<?php 

/** @param resource $ldap */
#[\Until('8.1')]
function ldap_delete($ldap, string $dn, ?array $controls = null) : bool
{
}
#[\Since('8.1')]
function ldap_delete(\LDAP\Connection $ldap, string $dn, ?array $controls = null) : bool
{
}