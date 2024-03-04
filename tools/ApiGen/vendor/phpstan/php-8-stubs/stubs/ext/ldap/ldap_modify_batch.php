<?php 

/** @param resource $ldap */
#[\Until('8.1')]
function ldap_modify_batch($ldap, string $dn, array $modifications_info, ?array $controls = null) : bool
{
}
#[\Since('8.1')]
function ldap_modify_batch(\LDAP\Connection $ldap, string $dn, array $modifications_info, ?array $controls = null) : bool
{
}