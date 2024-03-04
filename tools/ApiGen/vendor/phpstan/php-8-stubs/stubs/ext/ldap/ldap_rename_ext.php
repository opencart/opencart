<?php 

/**
 * @param resource $ldap
 * @return resource|false
 */
#[\Until('8.1')]
function ldap_rename_ext($ldap, string $dn, string $new_rdn, string $new_parent, bool $delete_old_rdn, ?array $controls = null)
{
}
#[\Since('8.1')]
function ldap_rename_ext(\LDAP\Connection $ldap, string $dn, string $new_rdn, string $new_parent, bool $delete_old_rdn, ?array $controls = null) : \LDAP\Result|false
{
}